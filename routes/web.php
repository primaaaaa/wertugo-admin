<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
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
    return view('landing_page.landing-page');
})->name('landing-page');

Route::middleware(['guest.admin'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', function () {
    return view('pages.landing.index');
});



Route::middleware(['admin.auth'])->group(function () {
    
    // Tampilkan Halaman
    Route::get('/admin', [DashboardController::class, 'index']);
    Route::get('/admin/users', [UserController::class, 'index'])->name('daftar-user');
    Route::get('/admin/umkm', [UmkmController::class, 'index'])->name('daftar-umkm');
    Route::get('/admin/verifikasi-umkm', [VerificationController::class, 'index'])->name('daftar-verifikasi');

    Route::get('/admin/users/{id}/detail', [UserController::class, 'showDetail'])->name('user.detail');

    Route::get('/admin/report', [ReportController::class, 'index'])->name('report.index');
    Route::post('/admin/report/{id}/tindak', [ReportController::class, 'tindakReport'])->name('report.tindak');

    Route::get('/admin/umkm/{id}', [UmkmController::class, 'showDetail'])->name('umkm.detail');
    Route::get('/admin/verifikasi/pending', [VerificationController::class, 'pendingList'])->name('verifikasi.pending');
    Route::put('/admin/verifikasi/{id}/verify', [UmkmController::class, 'verifyUmkm'])->name('umkm.verify');
    Route::get('/admin/verifikasi/{id}/detail', [VerificationController::class, 'showDetail'])->name('verifikasi.detail');
});


// ROUTES SEMENTARA UNTUK TESTING ERROR
Route::get('/test-403', function () {
    abort(403); // Memaksa sistem mengeluarkan error 403
});

Route::get('/test-500', function () {
    abort(500); // Memaksa sistem mengeluarkan error 500
});