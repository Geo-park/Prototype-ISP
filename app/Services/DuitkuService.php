<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DuitkuService
{
    protected ?string $merchantCode;
    protected ?string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->merchantCode = config('services.duitku.merchant_code');
        $this->apiKey       = config('services.duitku.api_key');
        // Sandbox by default, production: https://passport.duitku.com
        $this->baseUrl      = config('services.duitku.sandbox', true)
            ? 'https://sandbox.duitku.com'
            : 'https://passport.duitku.com';
    }

    public function isConfigured(): bool
    {
        return !empty($this->merchantCode) && !empty($this->apiKey);
    }

    /**
     * Buat transaksi pembayaran di Duitku
     *
     * @param array $params [
     *   'merchant_order_id' => string,
     *   'payment_amount'    => int,
     *   'payment_method'    => string (e.g. 'VC' for QRIS, 'VA' for Virtual Account),
     *   'product_details'   => string,
     *   'customer_name'     => string,
     *   'customer_email'    => string,
     *   'callback_url'      => string,
     *   'return_url'        => string,
     * ]
     * @return array
     */
    public function createTransaction(array $params): array
    {
        if (!$this->isConfigured()) {
            return [
                'success'    => true,
                'simulasi'   => true,
                'message'    => '[SIMULASI] Transaksi Duitku dibuat',
                'reference'  => 'SIM-' . now()->format('YmdHis'),
                'payment_url' => null,
            ];
        }

        $signature = md5(
            $this->merchantCode
            . $params['merchant_order_id']
            . $params['payment_amount']
            . $this->apiKey
        );

        try {
            $response = Http::post($this->baseUrl . '/webapi/api/merchant/v2/inquiry', [
                'merchantCode'         => $this->merchantCode,
                'paymentAmount'        => $params['payment_amount'],
                'paymentMethod'        => $params['payment_method'] ?? 'VC',
                'merchantOrderId'      => $params['merchant_order_id'],
                'productDetails'       => $params['product_details'] ?? 'Pembayaran ISP',
                'customerVaName'       => $params['customer_name'] ?? 'Pelanggan',
                'email'                => $params['customer_email'] ?? '',
                'callbackUrl'          => $params['callback_url'] ?? url('/api/duitku/callback'),
                'returnUrl'            => $params['return_url'] ?? url('/'),
                'signature'            => $signature,
                'expiryPeriod'         => 1440, // 24 hours
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['paymentUrl'])) {
                return [
                    'success'     => true,
                    'simulasi'    => false,
                    'reference'   => $data['reference'] ?? null,
                    'payment_url' => $data['paymentUrl'],
                    'va_number'   => $data['vaNumber'] ?? null,
                    'data'        => $data,
                ];
            }

            Log::warning('Duitku API error', ['response' => $data]);
            return [
                'success' => false,
                'message' => $data['Message'] ?? 'Gagal membuat transaksi',
                'data'    => $data,
            ];
        } catch (\Throwable $e) {
            Log::error('Duitku exception', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Error koneksi Duitku: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Validasi callback signature dari Duitku
     */
    public function validateCallback(string $merchantCode, string $amount, string $merchantOrderId, string $signature): bool
    {
        $expected = md5($merchantCode . $amount . $merchantOrderId . $this->apiKey);
        return hash_equals($expected, $signature);
    }
}
