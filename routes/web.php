<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuickLoginController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to dashboard (if authenticated) or login page (if guest)
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Tantangan Tambahan: Tombol Login Instan (Auto-Login Test Accounts) untuk kemudahan evaluasi/demo
Route::get('/quick-login/{role}', [QuickLoginController::class, 'login'])->name('quick.login');

// Area Terproteksi Autentikasi Laravel Breeze & Otorisasi RBAC Spatie
Route::middleware(['auth'])->group(function () {
    // 1. Dashboard Agregat Backoffice AdminLTE
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Modul Data Mahasiswa (CRUD lengkap + filter when() + pagination withQueryString)
    Route::resource('students', StudentController::class);

    // 3. Modul Data Dosen (CRUD lengkap + filter when() + pagination withQueryString)
    Route::resource('lecturers', LecturerController::class);

    // 4. Modul Data Mata Kuliah (CRUD lengkap + filter when() + pagination withQueryString)
    Route::resource('courses', CourseController::class);

    // 5. Modul Manajemen Pengguna & Otorisasi RBAC (Eksklusif Admin)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');

    // 6. Matriks Dokumentasi RBAC Spatie
    Route::get('/rbac-info', function () {
        return view('rbac-info');
    })->name('rbac.info');

    // Breeze Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
