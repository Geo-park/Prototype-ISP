<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PetaController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\UserController;
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
    Route::get('/stats', [SuperadminController::class, 'stats'])->name('stats');
    Route::get('/revenue-chart', [SuperadminController::class, 'revenueChart'])->name('revenue-chart');
    Route::get('/pelanggan-chart', [SuperadminController::class, 'pelangganChart'])->name('pelanggan-chart');
    Route::get('/status-pembayaran', [SuperadminController::class, 'statusPembayaran'])->name('status-pembayaran');
    Route::get('/invoice-terbaru', [SuperadminController::class, 'invoiceTerbaru'])->name('invoice-terbaru');
    Route::get('/aktivitas-log', [SuperadminController::class, 'aktivitasLog'])->name('aktivitas-log');
    Route::get('/statistik-daerah', [SuperadminController::class, 'statistikDaerah'])->name('statistik-daerah');
    Route::get('/peta', [PetaController::class, 'index'])->name('peta');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/stats', [AdminController::class, 'stats'])->name('stats');
    Route::get('/paket', [AdminController::class, 'paket'])->name('paket');
    Route::get('/laporan-keluhan', [AdminController::class, 'laporanKeluhan'])->name('laporan-keluhan');
    Route::get('/pelanggan/tambah', [AdminController::class, 'formTambahPelanggan'])->name('pelanggan.tambah');
    Route::post('/pelanggan/tambah', [AdminController::class, 'simpanPelanggan'])->name('pelanggan.simpan');
    Route::get('/pelanggan', [AdminController::class, 'pelanggan'])->name('pelanggan');
    Route::post('/koneksi/matikan/{id}', [AdminController::class, 'matikanKoneksi'])->name('koneksi.matikan');
    Route::post('/koneksi/hidupkan/{id}', [AdminController::class, 'hidupkanKoneksi'])->name('koneksi.hidupkan');
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
    Route::get('/profil-koneksi', [UserController::class, 'profilKoneksi'])->name('profil-koneksi');
    Route::get('/tagihan-aktif', [UserController::class, 'tagihanAktif'])->name('tagihan-aktif');
    Route::get('/riwayat-pembayaran', [UserController::class, 'riwayatPembayaran'])->name('riwayat-pembayaran');
    Route::get('/riwayat-pajak', [UserController::class, 'riwayatPajak'])->name('riwayat-pajak');
});

// Invoice routes (superadmin & admin)
Route::middleware(['auth', 'admin'])->prefix('invoice')->name('invoice.')->group(function () {
    Route::get('/', [InvoiceController::class, 'index'])->name('index');
    Route::get('/{id}', [InvoiceController::class, 'show'])->name('show');
    Route::post('/', [InvoiceController::class, 'store'])->name('store');
    Route::post('/{id}/simulasi-bayar', [InvoiceController::class, 'simulasiBayar'])->name('simulasi-bayar');
});
