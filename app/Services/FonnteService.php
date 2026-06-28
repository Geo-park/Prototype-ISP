<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected ?string $token;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
    }

    public function isConfigured(): bool
    {
        return !empty($this->token);
    }

    /**
     * Kirim pesan WhatsApp via Fonnte API
     *
     * @param string $target Nomor WA tujuan (format 08xx atau 628xx)
     * @param string $message Isi pesan
     * @return array ['success' => bool, 'message' => string, 'data' => mixed]
     */
    public function send(string $target, string $message): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => true,
                'message' => '[SIMULASI] Pesan terkirim ke ' . $target,
                'data'    => null,
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post('https://api.fonnte.com/send', [
                'target'  => $target,
                'message' => $message,
            ]);

            $data = $response->json();

            if ($response->successful() && ($data['status'] ?? false)) {
                return [
                    'success' => true,
                    'message' => 'Pesan berhasil dikirim ke ' . $target,
                    'data'    => $data,
                ];
            }

            Log::warning('Fonnte API gagal', ['response' => $data]);
            return [
                'success' => false,
                'message' => 'Gagal mengirim: ' . ($data['reason'] ?? 'Unknown error'),
                'data'    => $data,
            ];
        } catch (\Throwable $e) {
            Log::error('Fonnte exception', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Error koneksi: ' . $e->getMessage(),
                'data'    => null,
            ];
        }
    }
}
