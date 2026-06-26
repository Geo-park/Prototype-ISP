# PLANNING PROTOTYPE — ISP Management System
**Status**: Prototype (Negosiasi)  
**Budget Prototype**: Rp 1.000.000  
**Target**: ~12 hari kerja  
**Stack**: Laravel + Vite + Vue 3 + PostgreSQL  
**Tim**: 2 orang (Dev A & Dev B)  

---

## Pembagian Peran

| | Dev A | Dev B |
|--|-------|-------|
| **Fokus** | Backend Laravel + Auth + Data | Frontend Vue + UI + Peta |
| **Tanggung Jawab** | API routes, migration, seeder, middleware | Komponen Vue, layout, chart, peta |
| **Overlap** | Fase 0 dikerjakan bersama | Fase 8 dikerjakan bersama |

---

## Struktur Folder

```
project/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── SuperadminController.php
│   │   │   ├── AdminController.php
│   │   │   ├── UserController.php
│   │   │   ├── InvoiceController.php
│   │   │   ├── PetaController.php
│   │   │   └── NotifikasiController.php
│   │   └── Middleware/
│   │       ├── SuperadminMiddleware.php
│   │       ├── AdminMiddleware.php
│   │       └── UserMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Pelanggan.php
│       ├── Invoice.php
│       ├── Pembayaran.php
│       ├── CatatanPajak.php
│       ├── PopOlt.php
│       ├── Odc.php
│       └── Odp.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── js/
│       ├── components/
│       │   ├── shared/
│       │   │   ├── Sidebar.vue
│       │   │   ├── Navbar.vue
│       │   │   ├── StatsCard.vue
│       │   │   ├── DataTable.vue
│       │   │   └── BadgeStatus.vue
│       │   ├── superadmin/
│       │   │   ├── DashboardStats.vue
│       │   │   ├── RevenueChart.vue
│       │   │   ├── PelangganChart.vue
│       │   │   └── StatusPembayaranChart.vue
│       │   ├── admin/
│       │   │   ├── DashboardAdmin.vue
│       │   │   └── TabelPelanggan.vue
│       │   ├── user/
│       │   │   ├── StatusKoneksi.vue
│       │   │   ├── TagihanCard.vue
│       │   │   └── RiwayatPembayaran.vue
│       │   ├── peta/
│       │   │   ├── PetaJaringan.vue
│       │   │   ├── MarkerPopup.vue
│       │   │   └── FilterPanel.vue
│       │   ├── invoice/
│       │   │   ├── ListInvoice.vue
│       │   │   ├── DetailInvoice.vue
│       │   │   └── SimulasiPayment.vue
│       │   └── notifikasi/
│       │       ├── TemplateWA.vue
│       │       └── BubbleWA.vue
│       ├── layouts/
│       │   ├── SuperadminLayout.vue
│       │   ├── AdminLayout.vue
│       │   └── UserLayout.vue
│       ├── pages/
│       │   ├── auth/
│       │   │   └── Login.vue
│       │   ├── superadmin/
│       │   │   ├── Dashboard.vue
│       │   │   └── Peta.vue
│       │   ├── admin/
│       │   │   └── Dashboard.vue
│       │   ├── user/
│       │   │   └── Dashboard.vue
│       │   ├── invoice/
│       │   │   ├── Index.vue
│       │   │   └── Detail.vue
│       │   └── notifikasi/
│       │       └── TemplateWA.vue
│       ├── router/
│       │   └── index.js
│       ├── stores/
│       │   ├── auth.js
│       │   ├── dashboard.js
│       │   ├── invoice.js
│       │   └── peta.js
│       └── app.js
└── routes/
    └── api.php
```

---

## Dependency

### Laravel
```
laravel/sanctum
```

### Vue / Frontend
```
vue@3
vue-router@4
pinia
axios
tailwindcss
chart.js
vue-chartjs
leaflet
@vue-leaflet/vue-leaflet
@heroicons/vue
```

---

## FASE 0 — Setup Project
**Durasi**: 1 hari  
**Dikerjakan**: Dev A & Dev B bersama  

### Dev A
- [ ] Install Laravel
- [ ] Setup PostgreSQL + konfigurasi .env
- [ ] Install Laravel Sanctum (auth API)
- [ ] Buat migration semua tabel dummy
- [ ] Buat seeder data realistis
- [ ] Setup API routes struktur awal

### Dev B
- [ ] Setup Vite + Vue 3
- [ ] Install Tailwind CSS
- [ ] Install Vue Router
- [ ] Install Pinia (state management)
- [ ] Install Chart.js + vue-chartjs
- [ ] Install Leaflet + vue-leaflet
- [ ] Setup struktur folder frontend
- [ ] Setup axios + base API config

---

## FASE 1 — Auth & Role System
**Durasi**: 1 hari  

### Dev A — Backend
- [ ] Model User dengan role enum (superadmin/admin/user)
- [ ] AuthController (login, logout)
- [ ] Middleware SuperadminMiddleware
- [ ] Middleware AdminMiddleware
- [ ] Middleware UserMiddleware
- [ ] API endpoint: POST /api/login
- [ ] API endpoint: POST /api/logout
- [ ] API endpoint: GET /api/me
- [ ] Seeder 3 akun demo:
  - superadmin@demo.com / demo1234
  - admin@demo.com / demo1234
  - user@demo.com / demo1234

### Dev B — Frontend
- [ ] Halaman Login.vue
- [ ] Store auth.js (Pinia)
- [ ] Vue Router setup dengan guard per role
- [ ] Layout SuperadminLayout.vue (sidebar + navbar)
- [ ] Layout AdminLayout.vue
- [ ] Layout UserLayout.vue
- [ ] Redirect otomatis sesuai role setelah login
- [ ] Tombol "Preview as Role" untuk demo

---

## FASE 2 — Dashboard Superadmin
**Durasi**: 2 hari  

### Dev A — Backend
- [ ] SuperadminController
- [ ] GET /api/superadmin/stats
  - total pelanggan aktif/nonaktif
  - revenue bulan ini
  - tagihan pending
  - jaringan bermasalah
- [ ] GET /api/superadmin/revenue-chart (6 bulan)
- [ ] GET /api/superadmin/pelanggan-chart
- [ ] GET /api/superadmin/status-pembayaran
- [ ] GET /api/superadmin/invoice-terbaru
- [ ] GET /api/superadmin/aktivitas-log
- [ ] GET /api/superadmin/status-jaringan

### Dev B — Frontend
- [ ] Dashboard.vue (superadmin)
- [ ] StatsCard.vue (reusable)
- [ ] RevenueChart.vue (bar chart)
- [ ] PelangganChart.vue (line chart)
- [ ] StatusPembayaranChart.vue (pie chart)
- [ ] TabelInvoiceTerbaru.vue
- [ ] TabelAktivitas.vue
- [ ] StatusJaringanSummary.vue

---

## FASE 3 — Peta Jaringan
**Durasi**: 2 hari  

### Dev A — Backend
- [ ] Model PopOlt, Odc, Odp, Pelanggan
- [ ] Migration + seeder koordinat dummy
- [ ] PetaController
- [ ] GET /api/peta/pop-olt
- [ ] GET /api/peta/odc
- [ ] GET /api/peta/odp
- [ ] GET /api/peta/pelanggan
- [ ] GET /api/peta/semua (single endpoint)

### Dev B — Frontend
- [ ] PetaJaringan.vue (Leaflet wrapper)
- [ ] Custom marker icon per tipe:
  - POP → biru
  - ODC L1 → hijau
  - ODC L2 → kuning
  - ODP → orange
  - Pelanggan aktif + bayar → hijau
  - Pelanggan aktif + belum bayar → kuning
  - Pelanggan nonaktif → merah
- [ ] Garis koneksi antar titik
- [ ] MarkerPopup.vue per tipe marker
- [ ] FilterPanel.vue (filter by status/tipe)
- [ ] Toggle layer show/hide

---

## FASE 4 — Invoice & Simulasi Payment
**Durasi**: 2 hari  

### Dev A — Backend
- [ ] Model Invoice, Pembayaran, CatatanPajak
- [ ] InvoiceController
- [ ] GET /api/invoice (list)
- [ ] GET /api/invoice/:id (detail)
- [ ] POST /api/invoice/:id/simulasi-bayar
  - update status → paid
  - generate catatan pajak dummy
  - return response sukses
- [ ] GET /api/catatan-pajak/:id
- [ ] Seeder invoice dummy (pending/paid/overdue)

### Dev B — Frontend
- [ ] ListInvoice.vue
  - tabel + badge status
  - filter by status
  - search by nama / no invoice
- [ ] DetailInvoice.vue
  - info pelanggan
  - breakdown: subtotal + pajak (11%) + total
  - tombol kirim WA (dummy toast)
  - tombol bayar sekarang
- [ ] SimulasiPayment.vue
  - pilih metode: QRIS / VA / Transfer
  - instruksi pembayaran dummy
  - tombol "Simulasi Bayar Berhasil"
  - animasi sukses
  - redirect ke detail invoice
- [ ] DetailCatatanPajak.vue

---

## FASE 5 — Preview WA Notifikasi
**Durasi**: 1 hari  

### Dev A — Backend
- [ ] GET /api/notifikasi/templates
  - invoice
  - reminder_h3
  - reminder_h1
  - overdue
  - koneksi_mati
  - koneksi_hidup
  - bukti_bayar
- [ ] POST /api/notifikasi/simulasi-kirim
  - return dummy response sukses

### Dev B — Frontend
- [ ] TemplateWA.vue (halaman utama)
- [ ] BubbleWA.vue (reusable)
  - style mirip tampilan WA
  - bubble hijau + avatar + timestamp
- [ ] Render semua template WA
- [ ] Variable replacement preview:
  - [Nama] → nama pelanggan dummy
  - [Total] → angka invoice dummy
- [ ] Tombol "Simulasi Kirim"
  - toast: "Pesan terkirim ke 08xx"

---

## FASE 6 — Dashboard Admin
**Durasi**: 1 hari  

### Dev A — Backend
- [ ] AdminController
- [ ] GET /api/admin/stats
- [ ] GET /api/admin/pelanggan
- [ ] GET /api/admin/tiket-aktif
- [ ] POST /api/admin/koneksi/matikan (dummy)
- [ ] POST /api/admin/koneksi/hidupkan (dummy)

### Dev B — Frontend
- [ ] Dashboard.vue (admin)
- [ ] TabelPelanggan.vue
  - nama, paket, status koneksi, status bayar
  - tombol matikan/hidupkan koneksi
  - modal konfirmasi aksi
- [ ] ListTiketAktif.vue

---

## FASE 7 — Dashboard User/Pelanggan
**Durasi**: 1 hari  

### Dev A — Backend
- [ ] UserController
- [ ] GET /api/user/profil-koneksi
- [ ] GET /api/user/tagihan-aktif
- [ ] GET /api/user/riwayat-pembayaran
- [ ] GET /api/user/riwayat-pajak

### Dev B — Frontend
- [ ] Dashboard.vue (user)
- [ ] StatusKoneksi.vue
  - badge besar AKTIF / NONAKTIF
  - info paket + kecepatan
  - tanggal jatuh tempo
- [ ] TagihanCard.vue
  - nominal + status
  - tombol bayar → flow fase 4
- [ ] RiwayatPembayaran.vue
- [ ] RiwayatCatatanPajak.vue

---

## FASE 8 — Polish & Demo Prep
**Durasi**: 1 hari  
**Dikerjakan**: Dev A & Dev B bersama  

### Dev A
- [ ] Pastikan semua API response konsisten
- [ ] Cek semua seeder data realistis
- [ ] Buat dokumentasi akun demo
- [ ] Setup environment production dummy

### Dev B
- [ ] Responsive check semua halaman
- [ ] Loading state semua komponen
- [ ] Transisi halaman smooth
- [ ] Empty state semua tabel
- [ ] Error handling UI
- [ ] Cek semua tombol ada feedback

### Bersama
- [ ] End-to-end walkthrough semua flow
- [ ] Fix bug visual
- [ ] Siapkan script demo untuk presentasi ke klien

---

## Ringkasan Timeline

| Fase | Task | Durasi | Dev A | Dev B |
|------|------|--------|-------|-------|
| 0 | Setup Project | 1 hari | ✅ | ✅ |
| 1 | Auth + Role System | 1 hari | ✅ | ✅ |
| 2 | Dashboard Superadmin | 2 hari | ✅ | ✅ |
| 3 | Peta Jaringan | 2 hari | ✅ | ✅ |
| 4 | Invoice + Simulasi Payment | 2 hari | ✅ | ✅ |
| 5 | Preview WA Notifikasi | 1 hari | ✅ | ✅ |
| 6 | Dashboard Admin | 1 hari | ✅ | ✅ |
| 7 | Dashboard User | 1 hari | ✅ | ✅ |
| 8 | Polish + Demo Prep | 1 hari | ✅ | ✅ |
| **Total** | | **~12 hari** | | |

---

## Akun Demo

| Role | Email | Password |
|------|-------|----------|
| Superadmin | superadmin@demo.com | demo1234 |
| Admin | admin@demo.com | demo1234 |
| User | user@demo.com | demo1234 |

---

## Yang Tidak Masuk Prototype

| Komponen | Status |
|----------|--------|
| MikroTik REST API | ❌ Tidak perlu |
| FreeRADIUS | ❌ Tidak perlu |
| Duitku real | ❌ Tidak perlu |
| Fonnte real | ❌ Tidak perlu |
| Mekari API | ❌ Tidak perlu |
| Cron / Scheduler | ❌ Tidak perlu |
| Queue Jobs | ❌ Tidak perlu |

---

## Catatan Penting

- Semua data DUMMY, tidak ada koneksi ke layanan eksternal
- Prototype hanya untuk validasi UI dan alur ke klien
- API Mekari belum diterima dari klien — masuk full project
- Setelah MOU ditandatangani, development full dimulai dari base ini
- Budget prototype: Rp 1.000.000
- Budget full project: Rp 16.000.000 (negosiasi setelah prototype)
- Deadline full project: 3 bulan
