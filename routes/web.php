<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PenaltyController;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

// =========================
// HOME
// =========================
Route::get('/', function () {
    return view('welcome');
});

// =========================
// DASHBOARD
// =========================
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// =========================
// USERS
// =========================
Route::get('/users', function () {
    return view('users.index');
})->name('users.index');

// =========================
// REPORTS
// =========================
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
Route::get('/reports/download/{id}', [ReportController::class, 'download'])->name('reports.download');

// =========================
// PRODUK (MANUAL CRUD)
// =========================
Route::get('/produk', [ProdukController::class, 'index'])->name('produks.index');

Route::get('/produk/create', [ProdukController::class, 'create'])->name('produks.create');
Route::post('/produk/store', [ProdukController::class, 'store'])->name('produks.store');

Route::get('/produk/edit/{id}', [ProdukController::class, 'edit'])->name('produks.edit');
Route::put('/produk/update/{id}', [ProdukController::class, 'update'])->name('produks.update');
Route::delete('/produk/delete/{id}', [ProdukController::class, 'destroy'])->name('produks.destroy');

// =========================
// LOGIN
// =========================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/login/google', [LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [LoginController::class, 'handleGoogleCallback']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// =========================
// KATEGORI
// =========================
Route::resource('kategoris', KategoriController::class)->only([
    'index', 'store', 'update', 'destroy'
]);

// =========================
// ADMIN
// =========================
Route::resource('admins', AdminController::class)->only([
    'index', 'store', 'update', 'destroy'
]);

// =========================
// PENALTIES & RETURNS
// =========================
Route::get('/penalties', [PenaltyController::class, 'index'])->name('penalties.index');
Route::patch('/penalties/{id}/resolve', [PenaltyController::class, 'markResolved'])->name('penalties.resolve');
Route::patch('/penalties/{id}/finish', [PenaltyController::class, 'markFinished'])->name('penalties.finish');
Route::post('/penalties/send-reminder', [PenaltyController::class, 'sendReminder'])->name('penalties.send-reminder');

// =========================
// 2FA SETUP & VERIFY
// =========================
Route::get('/2fa/setup',         [LoginController::class, 'show2FASetup'])->name('2fa.setup');
Route::get('/2fa/choose',        [LoginController::class, 'show2FAChoose'])->name('2fa.choose');
Route::get('/2fa/verify',        [LoginController::class, 'show2FAVerify'])->name('2fa.verify');
Route::post('/2fa/verify',       [LoginController::class, 'verify2FA'])->name('2fa.verify.post');
Route::get('/2fa/email/send',    [LoginController::class, 'sendEmailOtp'])->name('2fa.email.send');
Route::get('/2fa/email/verify',  [LoginController::class, 'showEmailOtpVerify'])->name('2fa.email.verify');
Route::post('/2fa/email/verify', [LoginController::class, 'verifyEmailOtp'])->name('2fa.email.verify.post');
