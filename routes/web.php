<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceController;
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

// User routes
Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('user/Dashboard');
    })->name('dashboard');
});

// Invoice routes (superadmin & admin)
Route::middleware(['auth', 'admin'])->prefix('invoice')->name('invoice.')->group(function () {
    Route::get('/', [InvoiceController::class, 'index'])->name('index');
    Route::get('/{id}', [InvoiceController::class, 'show'])->name('show');
    Route::post('/', [InvoiceController::class, 'store'])->name('store');
    Route::post('/{id}/simulasi-bayar', [InvoiceController::class, 'simulasiBayar'])->name('simulasi-bayar');
});
