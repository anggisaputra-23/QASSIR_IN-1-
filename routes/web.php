<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\SelfOrderController;
use App\Http\Controllers\QRCodeController;

// Redirect root ke halaman dashboard
Route::get('/', fn () => redirect()->route('dashboard'));

// Dashboard admin
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// CRUD Menu
Route::resource('/menu', MenuController::class);

// Transaksi Kasir
Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
Route::post('/checkout', [TransaksiController::class, 'checkout'])->name('checkout');
Route::get('/riwayat', [TransaksiController::class, 'riwayat'])->name('riwayat');
Route::delete('/transaksi/{id}/batal', [TransaksiController::class, 'batalkan'])->name('transaksi.batal');
Route::get('/transaksi/{id}/struk', [TransaksiController::class, 'cetakStruk'])->name('transaksi.struk');

// Self Order untuk pelanggan
Route::get('/selforder', [SelfOrderController::class, 'form'])->name('selforder.form');
Route::post('/selforder', [SelfOrderController::class, 'store'])->name('selforder.store');
Route::get('/selforder/konfirmasi/{id}', [SelfOrderController::class, 'konfirmasi'])->name('selforder.konfirmasi');
Route::get('/selforder/invoice/{id}', [SelfOrderController::class, 'invoice'])->name('selforder.invoice');

// Manajemen Self Order oleh admin
Route::get('/admin/selforders', [SelfOrderController::class, 'adminIndex'])->name('admin.selforders');
Route::post('/admin/selforders/{id}/proses', [SelfOrderController::class, 'proses'])->name('admin.selforders.proses');
Route::delete('/admin/selforders/{id}/batal', [SelfOrderController::class, 'batalkan'])->name('admin.selforders.batal');

// Halaman untuk menampilkan QR Code Self Order
Route::get('/admin/qrcode', [QRCodeController::class, 'index'])->name('admin.qrcode');
