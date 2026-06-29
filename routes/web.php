<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PetaController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KeluhanController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


// Welcome
Route::get('/', function () {
    if (auth()->check()) {
        return match(auth()->user()->role) {
            'superadmin' => redirect()->route('superadmin.dashboard'),
            'admin'      => redirect()->route('admin.dashboard'),
            'user'       => redirect()->route('user.dashboard'),
            default      => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
})->name('welcome');

// Auth routes (dari Breeze)
require __DIR__.'/auth.php';

// Superadmin routes
Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperadminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard-data', [SuperadminController::class, 'dashboardData'])->name('dashboard-data');
    Route::get('/stats', [SuperadminController::class, 'stats'])->name('stats');
    Route::get('/revenue-chart', [SuperadminController::class, 'revenueChart'])->name('revenue-chart');
    Route::get('/pelanggan-chart', [SuperadminController::class, 'pelangganChart'])->name('pelanggan-chart');
    Route::get('/status-pembayaran', [SuperadminController::class, 'statusPembayaran'])->name('status-pembayaran');
    Route::get('/invoice-terbaru', [SuperadminController::class, 'invoiceTerbaru'])->name('invoice-terbaru');
    Route::get('/aktivitas-log', [SuperadminController::class, 'aktivitasLog'])->name('aktivitas-log');
    Route::get('/statistik-daerah', [SuperadminController::class, 'statistikDaerah'])->name('statistik-daerah');
    Route::get('/peta', [PetaController::class, 'index'])->name('peta');
    Route::get('/users', [SuperadminController::class, 'users'])->name('users');
    Route::get('/users/page', [SuperadminController::class, 'usersPage'])->name('users.page');
    Route::get('/users/tambah-admin', [SuperadminController::class, 'tambahAdminPage'])->name('users.tambah-admin.page');
    Route::post('/users/tambah-admin', [SuperadminController::class, 'simpanAdmin'])->name('users.tambah-admin');
    Route::get('/users/tambah-user', [SuperadminController::class, 'tambahUserPage'])->name('users.tambah-user.page');
    Route::post('/users/tambah-user', [AdminController::class, 'simpanPelanggan'])->name('users.tambah-user.simpan');
    Route::get('/keluhan', [KeluhanController::class, 'index'])->name('keluhan.index');
    Route::put('/keluhan/{id}/status', [KeluhanController::class, 'updateStatus'])->name('keluhan.status');
    Route::get('/laporan-keluhan', [KeluhanController::class, 'laporanKeluhan'])->name('laporan-keluhan');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard-data', [AdminController::class, 'dashboardData'])->name('dashboard-data');
    Route::get('/get-odps', [AdminController::class, 'getOdps'])->name('get-odps');
    Route::get('/stats', [AdminController::class, 'stats'])->name('stats');
    Route::get('/paket', [AdminController::class, 'paket'])->name('paket');
    Route::get('/pelanggan/tambah', [AdminController::class, 'formTambahPelanggan'])->name('pelanggan.tambah');
    Route::post('/pelanggan/tambah', [AdminController::class, 'simpanPelanggan'])->name('pelanggan.simpan');
    Route::get('/pelanggan', [AdminController::class, 'pelanggan'])->name('pelanggan');
    Route::post('/koneksi/matikan/{id}', [AdminController::class, 'matikanKoneksi'])->name('koneksi.matikan');
    Route::post('/koneksi/hidupkan/{id}', [AdminController::class, 'hidupkanKoneksi'])->name('koneksi.hidupkan');
    Route::get('/peta', [PetaController::class, 'index'])->name('peta');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/tambah', [AdminController::class, 'simpanUser'])->name('users.simpan');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{id}/nonaktifkan', [AdminController::class, 'nonaktifkanUser'])->name('users.nonaktifkan');
    Route::post('/users/{id}/aktifkan', [AdminController::class, 'aktifkanUser'])->name('users.aktifkan');
    Route::get('/keluhan', [KeluhanController::class, 'index'])->name('keluhan.index');
    Route::put('/keluhan/{id}/status', [KeluhanController::class, 'updateStatus'])->name('keluhan.status');
    Route::get('/laporan-keluhan', [KeluhanController::class, 'laporanKeluhan'])->name('laporan-keluhan');
});

// Peta routes (superadmin + admin)
Route::middleware(['auth', 'admin'])->prefix('peta')->name('peta.')->group(function () {
    Route::get('/semua', [PetaController::class, 'semua'])->name('semua');
    Route::get('/pop-olt', [PetaController::class, 'popOlt'])->name('pop-olt');
    Route::get('/odc', [PetaController::class, 'odc'])->name('odc');
    Route::get('/odp', [PetaController::class, 'odp'])->name('odp');
    Route::get('/pelanggan', [PetaController::class, 'pelanggan'])->name('pelanggan');
});

// Notifikasi routes (superadmin + admin)
Route::middleware(['auth', 'admin'])->prefix('notifikasi')->name('notifikasi.')->group(function () {
    Route::get('/', [NotifikasiController::class, 'index'])->name('index');
    Route::get('/templates', [NotifikasiController::class, 'templates'])->name('templates');
    Route::post('/simulasi-kirim', [NotifikasiController::class, 'simulasiKirim'])->name('simulasi-kirim');
});

// User routes
Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard-data', [UserController::class, 'dashboardData'])->name('dashboard-data');
    Route::get('/profil-koneksi', [UserController::class, 'profilKoneksi'])->name('profil-koneksi');
    Route::get('/tagihan-aktif', [UserController::class, 'tagihanAktif'])->name('tagihan-aktif');
    Route::get('/riwayat-pembayaran', [UserController::class, 'riwayatPembayaran'])->name('riwayat-pembayaran');
    Route::get('/riwayat-pajak', [UserController::class, 'riwayatPajak'])->name('riwayat-pajak');
    Route::post('/beli-paket', [UserController::class, 'beliPaket'])->name('beli-paket');
});

// Profil & Pusat Bantuan routes (semua role: superadmin, admin, user)
Route::middleware(['auth'])->group(function () {
    Route::get('/profil', function () {
        return Inertia::render('shared/Profil');
    })->name('profil');
    Route::get('/profil/data', function () {
        $user = auth()->user();
        $pelanggan = null;
        if ($user->role === 'user') {
            $pelanggan = \App\Models\Pelanggan::with('paket')
                ->where('user_id', $user->id)
                ->first();
        }
        return response()->json([
            'user'      => $user,
            'pelanggan' => $pelanggan,
        ]);
    })->name('profil.data');
    Route::get('/pusat-bantuan', [KeluhanController::class, 'pusatBantuan'])->name('pusat-bantuan');
    Route::post('/keluhan', [KeluhanController::class, 'store'])->name('keluhan.store');
    Route::get('/paket-internet', function () {
        return Inertia::render('shared/Paket');
    })->name('paket-internet');
    Route::get('/paket/list', function () {
        return response()->json(\App\Models\PaketInternet::all());
    })->name('paket.list');
    Route::get('/tentang', function () {
        return Inertia::render('shared/TentangPT');
    })->name('tentang');
    Route::get('/syarat-ketentuan', function () {
        return Inertia::render('shared/SyaratKetentuan');
    })->name('syarat-ketentuan');
});

Route::middleware(['auth'])->prefix('invoice')->name('invoice.')->group(function () {
    Route::middleware(['admin'])->get('/', [InvoiceController::class, 'index'])->name('index');
    Route::middleware(['admin'])->post('/', [InvoiceController::class, 'store'])->name('store');
    Route::get('/{id}', [InvoiceController::class, 'show'])->name('show');
    Route::post('/{id}/simulasi-bayar', [InvoiceController::class, 'simulasiBayar'])->name('simulasi-bayar');
});
