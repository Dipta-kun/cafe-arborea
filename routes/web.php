<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\MejaController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PesananController;
use App\Http\Controllers\Customer\KeranjangController;
use App\Http\Controllers\Customer\MenuController as CustomerMenuController;
use App\Http\Controllers\Customer\PesananController as CustomerPesananController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer Routes (tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/menu/{nomorMeja}', [CustomerMenuController::class, 'index'])->name('customer.menu');
Route::get('/menu/{nomorMeja}/data', [CustomerMenuController::class, 'getData'])->name('customer.menu.data');

// Keranjang (session-based)
Route::prefix('keranjang')->name('keranjang.')->group(function () {
    Route::get('/', [KeranjangController::class, 'index'])->name('index');
    Route::post('/tambah', [KeranjangController::class, 'tambah'])->name('tambah');
    Route::patch('/update/{id}', [KeranjangController::class, 'update'])->name('update');
    Route::delete('/hapus/{id}', [KeranjangController::class, 'hapus'])->name('hapus');
    Route::delete('/kosongkan', [KeranjangController::class, 'kosongkan'])->name('kosongkan');
    Route::get('/count', [KeranjangController::class, 'count'])->name('count');
});

// Checkout & Pesanan Customer
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::post('/proses', [CustomerPesananController::class, 'proses'])->name('proses');
});

Route::prefix('tracking')->name('tracking.')->group(function () {
    Route::get('/{kodePesanan}', [CustomerPesananController::class, 'tracking'])->name('index');
    Route::get('/{kodePesanan}/status', [CustomerPesananController::class, 'getStatus'])->name('status');
});

// Pembayaran (Simulasi QRIS & Scan)
Route::get('/bayar/{kodePesanan}', [CustomerPesananController::class, 'bayar'])->name('pembayaran.index');
Route::get('/bayar/{kodePesanan}/scan', [CustomerPesananController::class, 'scan'])->name('pembayaran.scan');
Route::post('/bayar/{kodePesanan}/simulasi', [CustomerPesananController::class, 'simulasiSukses'])->name('pembayaran.simulasi');
Route::get('/bayar/{kodePesanan}/status', [CustomerPesananController::class, 'getPaymentStatus'])->name('pembayaran.status');

/*
|--------------------------------------------------------------------------
| Admin Routes (dengan login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/chart/penjualan-bulanan', [DashboardController::class, 'chartPenjualanBulanan'])->name('chart.penjualan');
    Route::get('/chart/menu-terlaris', [DashboardController::class, 'chartMenuTerlaris'])->name('chart.menu');
    Route::get('/chart/pesanan-harian', [DashboardController::class, 'chartPesananHarian'])->name('chart.pesanan');

    // Kategori CRUD
    Route::resource('kategori', KategoriController::class);
    Route::patch('kategori/{kategori}/toggle', [KategoriController::class, 'toggle'])->name('kategori.toggle');

    // Menu CRUD
    Route::resource('menu', MenuController::class);
    Route::patch('menu/{menu}/toggle', [MenuController::class, 'toggle'])->name('menu.toggle');

    // Meja CRUD + QR Code
    Route::resource('meja', MejaController::class);
    Route::get('meja/{meja}/qrcode/download', [MejaController::class, 'downloadQr'])->name('meja.qrcode.download');
    Route::get('meja/{meja}/qrcode/inline', [MejaController::class, 'inlineQr'])->name('meja.qrcode.inline');
    Route::get('meja/{meja}/qrcode/cetak', [MejaController::class, 'cetakQr'])->name('meja.qrcode.cetak');

    // Pesanan
    Route::get('pesanan', [PesananController::class, 'index'])->name('pesanan.index');
    Route::get('pesanan/{pesanan}', [PesananController::class, 'show'])->name('pesanan.show');
    Route::patch('pesanan/{pesanan}/status', [PesananController::class, 'updateStatus'])->name('pesanan.status');
    Route::patch('pesanan/{pesanan}/konfirmasi-pembayaran', [PesananController::class, 'konfirmasiPembayaran'])->name('pesanan.konfirmasi_pembayaran');

    // Laporan
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    Route::get('laporan/data', [LaporanController::class, 'getData'])->name('laporan.data');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Root redirect
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});
