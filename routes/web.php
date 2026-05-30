<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UmkmController;
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
    Route::get('/admin', [DashboardController::class, 'index']);
    Route::get('/admin/users', [UserController::class, 'index'])->name('daftar-user');
    Route::get('/admin/umkm', [UmkmController::class, 'index'])->name('daftar-umkm');
});


// ROUTES SEMENTARA UNTUK TESTING ERROR
Route::get('/test-403', function () {
    abort(403); // Memaksa sistem mengeluarkan error 403
});

Route::get('/test-500', function () {
    abort(500); // Memaksa sistem mengeluarkan error 500
});