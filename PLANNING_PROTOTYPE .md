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
| **Fokus** | Auth + Dashboard Superadmin + Invoice + Payment + Dashboard Admin | Peta Jaringan + WA Notifikasi + Dashboard User |
| **Model** | Fullstack per fitur | Fullstack per fitur |
| **Durasi** | ~6 hari | ~4 hari |
| **Overlap** | Fase 0 & Fase 8 dikerjakan bersama | Fase 0 & Fase 8 dikerjakan bersama |

---

## Struktur Folder

```
project/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── SuperadminController.php   ← Dev A
│   │   │   ├── AdminController.php        ← Dev A
│   │   │   ├── UserController.php         ← Dev B
│   │   │   ├── InvoiceController.php      ← Dev A
│   │   │   ├── PetaController.php         ← Dev B
│   │   │   └── NotifikasiController.php   ← Dev B
│   │   └── Middleware/
│   │       ├── SuperadminMiddleware.php   ← Dev A
│   │       ├── AdminMiddleware.php        ← Dev A
│   │       └── UserMiddleware.php         ← Dev A
│   └── Models/
│       ├── User.php                       ← Dev A
│       ├── Pelanggan.php                  ← Dev A
│       ├── Invoice.php                    ← Dev A
│       ├── Pembayaran.php                 ← Dev A
│       ├── CatatanPajak.php               ← Dev A
│       ├── PopOlt.php                     ← Dev B
│       ├── Odc.php                        ← Dev B
│       └── Odp.php                        ← Dev B
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── js/
│       ├── components/
│       │   ├── shared/                    ← Fase 0, bersama
│       │   │   ├── Sidebar.vue
│       │   │   ├── Navbar.vue
│       │   │   ├── StatsCard.vue
│       │   │   ├── DataTable.vue
│       │   │   └── BadgeStatus.vue
│       │   ├── superadmin/                ← Dev A
│       │   │   ├── DashboardStats.vue
│       │   │   ├── RevenueChart.vue
│       │   │   ├── PelangganChart.vue
│       │   │   └── StatusPembayaranChart.vue
│       │   ├── admin/                     ← Dev A
│       │   │   ├── DashboardAdmin.vue
│       │   │   └── TabelPelanggan.vue
│       │   ├── user/                      ← Dev B
│       │   │   ├── StatusKoneksi.vue
│       │   │   ├── TagihanCard.vue
│       │   │   └── RiwayatPembayaran.vue
│       │   ├── peta/                      ← Dev B
│       │   │   ├── PetaJaringan.vue
│       │   │   ├── MarkerPopup.vue
│       │   │   └── FilterPanel.vue
│       │   ├── invoice/                   ← Dev A
│       │   │   ├── ListInvoice.vue
│       │   │   ├── DetailInvoice.vue
│       │   │   └── SimulasiPayment.vue
│       │   └── notifikasi/                ← Dev B
│       │       ├── TemplateWA.vue
│       │       └── BubbleWA.vue
│       ├── layouts/
│       │   ├── SuperadminLayout.vue       ← Dev A
│       │   ├── AdminLayout.vue            ← Dev A
│       │   └── UserLayout.vue             ← Dev B
│       ├── pages/
│       │   ├── auth/
│       │   │   └── Login.vue              ← Dev A
│       │   ├── superadmin/                ← Dev A
│       │   │   ├── Dashboard.vue
│       │   │   └── Peta.vue
│       │   ├── admin/                     ← Dev A
│       │   │   └── Dashboard.vue
│       │   ├── user/                      ← Dev B
│       │   │   └── Dashboard.vue
│       │   ├── invoice/                   ← Dev A
│       │   │   ├── Index.vue
│       │   │   └── Detail.vue
│       │   └── notifikasi/                ← Dev B
│       │       └── TemplateWA.vue
│       ├── router/
│       │   └── index.js                   ← Dev A (setup) + Dev B (tambah routes)
│       ├── stores/
│       │   ├── auth.js                    ← Dev A
│       │   ├── dashboard.js               ← Dev A
│       │   ├── invoice.js                 ← Dev A
│       │   └── peta.js                    ← Dev B
│       └── app.js
└── routes/
    └── api.php                            ← Dev A (setup) + Dev B (tambah routes)
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
- [x] Install Laravel
- [x] Setup PostgreSQL + konfigurasi .env
- [x] Install Laravel Sanctum
- [x] Buat migration semua tabel dummy
- [x] Buat seeder data realistis
- [x] Setup API routes struktur awal
- [x] Buat shared component: Sidebar, Navbar, StatsCard, DataTable, BadgeStatus

### Dev B
- [x] Setup Vite + Vue 3
- [x] Install Tailwind CSS
- [x] Install Vue Router
- [x] Install Pinia
- [x] Install Chart.js + vue-chartjs
- [x] Install Leaflet + @vue-leaflet/vue-leaflet
- [x] Setup struktur folder frontend
- [x] Setup axios + base API config

---

## FASE 1 — Auth & Role System
**Durasi**: 1 hari  
**Dikerjakan**: Dev A  

### Backend
- [x] Model User dengan role enum (superadmin/admin/user)
- [x] AuthController (login, logout)
- [x] SuperadminMiddleware
- [x] AdminMiddleware
- [x] UserMiddleware
- [x] POST /api/login
- [x] POST /api/logout
- [x] GET /api/me
- [x] Seeder 3 akun demo:
  - superadmin@demo.com / demo1234
  - admin@demo.com / demo1234
  - user@demo.com / demo1234

### Frontend
- [x] Login.vue
- [x] Store auth.js (Pinia)
- [x] Vue Router + guard per role
- [x] SuperadminLayout.vue
- [x] AdminLayout.vue
- [x] Redirect otomatis sesuai role setelah login
- [x] Tombol "Preview as Role" untuk demo klien

---

## FASE 2 — Dashboard Superadmin
**Durasi**: 2 hari  
**Dikerjakan**: Dev A  

### Backend
- [x] SuperadminController
- [x] GET /api/superadmin/stats
  - total pelanggan aktif/nonaktif
  - revenue bulan ini
  - tagihan pending
  - jaringan bermasalah
- [x] GET /api/superadmin/revenue-chart (6 bulan)
- [x] GET /api/superadmin/pelanggan-chart
- [x] GET /api/superadmin/status-pembayaran
- [x] GET /api/superadmin/invoice-terbaru
- [x] GET /api/superadmin/aktivitas-log
- [x] GET /api/superadmin/status-jaringan

### Frontend
- [x] Dashboard.vue (superadmin)
- [x] StatsCard.vue (reusable)
- [x] RevenueChart.vue (bar chart 6 bulan)
- [x] PelangganChart.vue (line chart)
- [x] StatusPembayaranChart.vue (pie chart paid/pending/overdue)
- [x] TabelInvoiceTerbaru.vue
- [x] TabelAktivitas.vue
- [x] StatusJaringanSummary.vue

---

## FASE 3 — Peta Jaringan
**Durasi**: 2 hari  
**Dikerjakan**: Dev B  

### Backend
- [x] Model PopOlt, Odc, Odp, Pelanggan
- [x] Migration + seeder koordinat dummy
- [x] PetaController
- [x] GET /api/peta/pop-olt
- [x] GET /api/peta/odc
- [x] GET /api/peta/odp
- [x] GET /api/peta/pelanggan
- [x] GET /api/peta/semua (single endpoint)

### Frontend
- [x] PetaJaringan.vue (Leaflet wrapper)
- [x] Custom marker icon per tipe:
  - POP → ikon tower, biru
  - ODC L1 → ikon cabinet, hijau
  - ODC L2 → ikon cabinet, kuning
  - ODP → ikon box, orange
  - Pelanggan aktif + bayar → hijau
  - Pelanggan aktif + belum bayar → kuning
  - Pelanggan nonaktif → merah
- [x] Garis koneksi antar titik (POP→ODC→ODP→Pelanggan)
- [x] MarkerPopup.vue per tipe marker
- [x] FilterPanel.vue (filter by status/tipe)
- [x] Toggle layer show/hide

---

## FASE 4 — Invoice & Simulasi Payment
**Durasi**: 2 hari  
**Dikerjakan**: Dev A  

### Backend
- [x] Model Invoice, Pembayaran, CatatanPajak
- [x] InvoiceController
- [x] GET /api/invoice (list)
- [x] GET /api/invoice/:id (detail)
- [x] POST /api/invoice/:id/simulasi-bayar
  - update status → paid
  - generate catatan pajak dummy
  - return response sukses
- [x] GET /api/catatan-pajak/:id
- [x] Seeder invoice dummy:
  - status pending
  - status paid
  - status overdue

### Frontend
- [x] ListInvoice.vue
  - tabel + badge status
  - filter by status
  - search by nama / no invoice
- [x] DetailInvoice.vue
  - info pelanggan
  - breakdown: subtotal + pajak (11%) + total
  - tombol kirim WA (dummy toast)
  - tombol bayar sekarang
- [x] SimulasiPayment.vue
  - pilih metode: QRIS / VA / Transfer
  - instruksi pembayaran dummy
  - tombol "Simulasi Bayar Berhasil"
  - animasi sukses
  - redirect ke detail invoice dengan status PAID
- [x] DetailCatatanPajak.vue
  - no faktur, tanggal, detail item
  - subtotal, pajak, total
  - tombol download PDF (dummy)

---

## FASE 5 — Preview WA Notifikasi
**Durasi**: 1 hari  
**Dikerjakan**: Dev B  

### Backend
- [x] NotifikasiController
- [x] GET /api/notifikasi/templates
  - invoice
  - reminder_h3
  - reminder_h1
  - overdue
  - koneksi_mati
  - koneksi_hidup
  - bukti_bayar
- [x] POST /api/notifikasi/simulasi-kirim
  - return dummy response sukses

### Frontend
- [x] TemplateWA.vue (halaman utama)
- [x] BubbleWA.vue (reusable)
  - style mirip tampilan WA
  - bubble hijau + avatar + timestamp
- [x] Render semua 7 template WA
- [x] Variable replacement preview:
  - [Nama] → nama pelanggan dummy
  - [Total] → angka invoice dummy
  - [Tanggal] → tanggal dummy
- [x] Tombol "Simulasi Kirim"
  - toast: "Pesan terkirim ke 08xx"

---

## FASE 6 — Dashboard Admin
**Durasi**: 1 hari  
**Dikerjakan**: Dev A  

### Backend
- [x] AdminController
- [x] GET /api/admin/stats
  - total pelanggan area
  - tagihan pending
  - koneksi bermasalah
- [x] GET /api/admin/pelanggan
- [x] GET /api/admin/tiket-aktif
- [x] POST /api/admin/koneksi/matikan (dummy)
- [x] POST /api/admin/koneksi/hidupkan (dummy)

### Frontend
- [x] Dashboard.vue (admin)
- [x] TabelPelanggan.vue
  - nama, paket, status koneksi, status bayar
  - tombol matikan/hidupkan koneksi
  - modal konfirmasi aksi
- [x] ListTiketAktif.vue

---

## FASE 7 — Dashboard User/Pelanggan
**Durasi**: 1 hari  
**Dikerjakan**: Dev B  

### Backend
- [x] UserController
- [x] GET /api/user/profil-koneksi
- [x] GET /api/user/tagihan-aktif
- [x] GET /api/user/riwayat-pembayaran
- [x] GET /api/user/riwayat-pajak

### Frontend
- [x] UserLayout.vue
- [x] Dashboard.vue (user)
- [x] StatusKoneksi.vue
  - badge besar AKTIF / NONAKTIF
  - info paket + kecepatan
  - tanggal jatuh tempo
- [x] TagihanCard.vue
  - nominal + status
  - tombol bayar → flow fase 4
- [x] RiwayatPembayaran.vue
- [x] RiwayatCatatanPajak.vue

---

## FASE 8 — Polish & Demo Prep
**Durasi**: 1 hari  
**Dikerjakan**: Dev A & Dev B bersama  

### Dev A
- [x] Pastikan semua API response format konsisten
- [x] Cek semua seeder data realistis
- [x] Dokumentasi akun demo
- [x] Setup environment production dummy

### Dev B
- [x] Responsive check semua halaman
- [x] Loading state semua komponen
- [x] Transisi halaman smooth
- [x] Empty state semua tabel
- [x] Error handling UI
- [x] Semua tombol ada feedback

### Bersama
- [x] End-to-end walkthrough semua flow
- [x] Fix bug visual
- [x] Siapkan script demo untuk presentasi ke klien

---

## Ringkasan Timeline

| Fase | Fitur | Durasi | PIC |
|------|-------|--------|-----|
| 0 | Setup Project | 1 hari | Bersama |
| 1 | Auth + Role System | 1 hari | Dev A |
| 2 | Dashboard Superadmin | 2 hari | Dev A |
| 3 | Peta Jaringan | 2 hari | Dev B |
| 4 | Invoice + Simulasi Payment | 2 hari | Dev A |
| 5 | Preview WA Notifikasi | 1 hari | Dev B |
| 6 | Dashboard Admin | 1 hari | Dev A |
| 7 | Dashboard User | 1 hari | Dev B |
| 8 | Polish + Demo Prep | 1 hari | Bersama |
| **Total** | | **~12 hari** | |

### Beban Per Developer

| | Dev A | Dev B |
|--|-------|-------|
| **Fase** | 1, 2, 4, 6 | 3, 5, 7 |
| **Durasi** | ~6 hari | ~4 hari |
| **Catatan** | Fase 0 & 8 bersama (+1 hari masing-masing) | Fase 0 & 8 bersama (+1 hari masing-masing) |

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
