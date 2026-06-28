# Langkah Pengerjaan Dev A — ISP Billing System
> Deadline: Senin | Estimasi: 21 jam | Mode: Full scope

---

## Aturan Selama Pengerjaan

```
1. Jangan skip urutan — setiap tahap ada dependency ke tahap berikutnya
2. Commit setiap tahap selesai — kalau ada yang rusak mudah rollback
3. Jangan perfectionist — selesai dulu, rapikan belakangan
4. Kalau stuck > 30 menit di satu masalah — skip, lanjut, balik lagi
```

---

## SABTU — Target: Fondasi + Auth + Dashboard

---

### TAHAP 1 — Migration & Model (2 jam)
> Fondasi semua fitur, harus selesai sebelum apapun

**Urutan migration (wajib berurutan):**

- [ ] `create_users_table`
- [ ] `create_paket_internet_table`
- [ ] `create_pelanggan_table`
- [ ] `create_invoice_table`
- [ ] `create_pembayaran_table`
- [ ] `create_catatan_pajak_table`

**Perubahan dari revisi yang harus diterapkan saat buat migration:**

```php
// paket_internet — JANGAN buat kolom ini
// harga_pajak dan total_harga dihapus, pindah ke accessor
 
// pembayaran — status harus include expired
$table->enum('status', ['success', 'pending', 'failed', 'expired']);
// JANGAN ada ->unique() pada invoice_id

// invoice — tambah kolom snapshot
$table->string('nama_paket');
$table->string('bandwidth');

// semua tabel kritis — tambah softDeletes
$table->softDeletes();
```

**Install package dulu sebelum buat model:**
```bash
composer require spatie/laravel-activitylog
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan migrate
```

**Buat semua model:**
- [ ] `User.php`
- [ ] `PaketInternet.php` → tambah accessor `hargaPajak` & `totalHarga`
- [ ] `Pelanggan.php` → tambah SoftDeletes, LogsActivity, hash `pppoe_password`
- [ ] `Invoice.php` → tambah SoftDeletes, LogsActivity, relasi `hasMany` pembayaran
- [ ] `Pembayaran.php` → tambah SoftDeletes
- [ ] `CatatanPajak.php` → tambah SoftDeletes

**Setelah selesai:**
```bash
git add .
git commit -m "feat: migration, model, revisi case 1-5"
```

---

### TAHAP 2 — Seeder (30 menit)
> Data dummy untuk development & testing

- [ ] `UserSeeder` — 1 superadmin, 1 admin, 3 user
- [ ] `PaketInternet Seeder` — 3 paket berbeda harga
- [ ] `PelangganSeeder` — 5 pelanggan dummy
- [ ] `InvoiceSeeder` — invoice dengan status pending, paid, overdue

```bash
php artisan db:seed
git commit -m "feat: seeder data dummy"
```

---

### TAHAP 3 — Auth & Role System (2 jam)
> Login, middleware, redirect per role

- [ ] `AuthController.php` — login, logout
- [ ] `SuperadminMiddleware.php`
- [ ] `AdminMiddleware.php`
- [ ] `UserMiddleware.php`
- [ ] Daftarkan middleware di `Kernel.php`
- [ ] Setup route per role di `api.php` atau `web.php`

**Vue:**
- [ ] `Login.vue`
- [ ] `store/auth.js` — simpan token & role
- [ ] `SuperadminLayout.vue`
- [ ] `AdminLayout.vue`
- [ ] Route guard per role di Vue Router

**Test:**
```
Login superadmin → redirect ke /superadmin ✓
Login admin → redirect ke /admin ✓
Login user → redirect ke /user ✓
Akses route salah role → redirect ✓
```

```bash
git commit -m "feat: auth & role system"
```

---

### TAHAP 4 — Dashboard Superadmin (3 jam)
> Stats, grafik, tabel invoice terbaru, log aktivitas

**Backend:**
- [ ] `SuperadminController.php`
  - `stats()` — total pelanggan, revenue bulan ini, invoice pending, overdue
  - `revenueChart()` — data 6 bulan terakhir
  - `pelangganChart()` — pertumbuhan pelanggan
  - `statusPembayaranChart()` — pie chart pending/paid/overdue
  - `invoiceTerbaru()` — 10 invoice terbaru
  - `logAktivitas()` — dari spatie activity log

**Vue:**
- [ ] `pages/superadmin/Dashboard.vue`
- [ ] `components/superadmin/DashboardStats.vue`
- [ ] `components/superadmin/RevenueChart.vue`
- [ ] `components/superadmin/PelangganChart.vue`
- [ ] `components/superadmin/StatusPembayaranChart.vue`
- [ ] `store/dashboard.js`

```bash
git commit -m "feat: dashboard superadmin"
```

---

## MINGGU — Target: Invoice + Duitku + Dashboard Admin

---

### TAHAP 5 — Invoice (2 jam)
> List invoice, detail invoice, breakdown pajak

**Backend:**
- [ ] `InvoiceController.php`
  - `index()` — list semua invoice dengan filter status
  - `show()` — detail invoice + breakdown pajak
  - `store()` — buat invoice baru dengan snapshot harga paket

**Pastikan saat `store()`:**
```php
// Ambil dari paket saat ini, bukan hitung ulang nanti
'nama_paket'    => $paket->nama,
'bandwidth'     => $paket->bandwidth_down . ' ' . $paket->satuan,
'subtotal'      => $paket->harga,
'persen_pajak'  => $paket->persen_pajak,
'nominal_pajak' => $paket->harga * $paket->persen_pajak / 100,
'total'         => $paket->harga + ($paket->harga * $paket->persen_pajak / 100),
```

**Vue:**
- [ ] `pages/invoice/Index.vue`
- [ ] `pages/invoice/Detail.vue`
- [ ] `components/invoice/ListInvoice.vue`
- [ ] `components/invoice/DetailInvoice.vue`
- [ ] `components/invoice/DetailCatatanPajak.vue`
- [ ] `store/invoice.js`

```bash
git commit -m "feat: invoice list & detail"
```

---

### TAHAP 6 — Integrasi Duitku (4 jam)
> Generate VA & QRIS, webhook, update status otomatis

**Daftar sandbox Duitku dulu:**
```
1. Buka duitku.com
2. Daftar akun
3. Masuk ke mode Sandbox
4. Ambil Merchant Code & API Key
5. Simpan di .env
```

**.env:**
```
DUITKU_MERCHANT_CODE=your_merchant_code
DUITKU_API_KEY=your_api_key
DUITKU_SANDBOX=true
DUITKU_CALLBACK_URL=http://yourdomain/api/duitku/callback
```

**Backend:**
- [ ] `DuitkuService.php` — service class untuk handle API Duitku

```php
class DuitkuService
{
    public function createTransaction($invoice, $metode)
    {
        // Hit API Duitku untuk generate VA/QRIS
        // Return referensi & instruksi pembayaran
    }

    public function verifyCallback($request)
    {
        // Validasi signature dari Duitku
        // Return true/false
    }
}
```

- [ ] Tambah route webhook:
```php
Route::post('/duitku/callback', [InvoiceController::class, 'handleCallback']);
```

- [ ] `handleCallback()` di `InvoiceController.php`:
```php
public function handleCallback(Request $request)
{
    // 1. Verifikasi signature
    if (!$this->duitkuService->verifyCallback($request)) {
        return response('Invalid signature', 403);
    }

    // 2. Cari pembayaran by referensi
    $pembayaran = Pembayaran::where('referensi', $request->merchantOrderId)->first();

    // 3. Update status
    if ($request->resultCode === '00') {
        $pembayaran->update(['status' => 'success']);
        $pembayaran->invoice->update(['status' => 'paid']);

        // 4. Generate catatan pajak
        CatatanPajak::create([...]);
    } else {
        $pembayaran->update(['status' => 'failed']);
    }

    return response('OK', 200);
}
```

**Vue:**
- [ ] `components/invoice/SimulasiPayment.vue`
  - Pilih metode (VA / QRIS)
  - Expire existing pending → buat pembayaran baru
  - Tampilkan instruksi pembayaran
  - Polling status tiap 5 detik
  - Redirect ke sukses jika paid

```bash
git commit -m "feat: integrasi duitku sandbox"
```

---

### TAHAP 7 — Dashboard Admin (2 jam)
> Stats area, tabel pelanggan, list tiket aktif

**Backend:**
- [ ] `AdminController.php`
  - `stats()` — total pelanggan aktif/nonaktif
  - `pelanggan()` — list pelanggan + aksi matikan/hidupkan (dummy)
  - `tiketAktif()` — list tiket dummy

**Vue:**
- [ ] `pages/admin/Dashboard.vue`
- [ ] `components/admin/DashboardAdmin.vue`
- [ ] `components/admin/TabelPelanggan.vue`

```bash
git commit -m "feat: dashboard admin"
```

---

### TAHAP 8 — Cold Storage & Cron Job (1 jam)
> Setup SQLite arsip + cron job bulanan

- [ ] Tambah koneksi `cold` di `config/database.php`
- [ ] Buat file SQLite: `storage/cold/arsip.db`
- [ ] Buat migration cold storage & jalankan
- [ ] Buat command `ArchiveColdStorage`
- [ ] Daftarkan di `Kernel.php`

**Test:**
```bash
php artisan arsip:pindah
```

```bash
git commit -m "feat: cold storage sqlite & cron job"
```

---

### TAHAP 9 — Bug Fixing & Testing (3 jam)
> Jangan skip tahap ini

**Checklist testing:**

**Auth:**
- [ ] Login 3 role berbeda
- [ ] Akses route salah role → redirect
- [ ] Logout bersih

**Invoice:**
- [ ] Buat invoice → snapshot harga tersimpan
- [ ] Ganti metode bayar → pending lama jadi expired
- [ ] Webhook callback → status update otomatis
- [ ] Catatan pajak ter-generate setelah paid

**Dashboard:**
- [ ] Semua grafik load dengan data
- [ ] Log aktivitas muncul
- [ ] Stats card angkanya benar

**Soft Delete:**
- [ ] Hapus pelanggan → tidak hilang dari DB
- [ ] Data terhapus tidak muncul di list

```bash
git commit -m "fix: bug fixing & testing"
git push
```

---

## Ringkasan Waktu

| Tahap | Fitur | Estimasi |
|-------|-------|----------|
| 1 | Migration & Model | 2 jam |
| 2 | Seeder | 30 menit |
| 3 | Auth & Role System | 2 jam |
| 4 | Dashboard Superadmin | 3 jam |
| 5 | Invoice | 2 jam |
| 6 | Integrasi Duitku | 4 jam |
| 7 | Dashboard Admin | 2 jam |
| 8 | Cold Storage & Cron | 1 jam |
| 9 | Bug Fixing & Testing | 3 jam |
| | **Total** | **21.5 jam** |

---

## Jadwal Kasar

| Waktu | Target |
|-------|--------|
| Sabtu siang | Tahap 1-2 selesai |
| Sabtu sore | Tahap 3 selesai |
| Sabtu malam | Tahap 4 selesai |
| Minggu pagi | Tahap 5 selesai |
| Minggu siang | Tahap 6 selesai |
| Minggu sore | Tahap 7-8 selesai |
| Minggu malam | Tahap 9 — bug fixing |
| Senin pagi | Buffer — siap serah terima |

---

## Kalau Mulai Ketinggalan Jadwal

Prioritas yang tidak boleh dikurangi:
```
✅ Auth & Role — tanpa ini semua fitur tidak bisa diakses
✅ Invoice + Duitku — ini core bisnis
✅ 5 Case Revisi — sudah didiskusikan dengan tim
```

Yang boleh dikurangi kalau kepepet:
```
⚠️ Dashboard Superadmin — kurangi ke stats card + 1 grafik saja
⚠️ Dashboard Admin — tabel pelanggan saja, tiket bisa dummy hardcode
⚠️ Cold Storage — bisa dikerjakan setelah Senin
```

---

*Commit setiap tahap. Kalau ada yang error, rollback ke commit terakhir yang bersih.*
