<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});

// Rute untuk admin
Route::middleware(['auth', 'check.level:admin'])->group(function () {
    Route::resource('user', UserController::class);
    Route::resource('obat', ObatController::class);
    Route::resource('transaksi', TransaksiController::class);
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/download', [LaporanController::class, 'download'])->name('laporan.download');
});

// Rute untuk operator
Route::middleware(['auth', 'check.level:operator,admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard.index'); // Admin Dashboard
    Route::get('/dashboard/operator', [DashboardController::class, 'index'])->name('dashboard.operator'); // Operator Dashboard
    Route::resource('transaksi', TransaksiController::class);
    Route::get('/transaksi/{id}/print', [TransaksiController::class, 'print'])->name('transaksi.print');
});
