<?php

use App\Http\Controllers\PublicController;
use App\Http\Controllers\FamilyFormController;
use App\Http\Controllers\MemberFormController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama
Route::get('/', [PublicController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Family (Keluarga)
|--------------------------------------------------------------------------
*/
Route::prefix('keluarga')->name('families.')->group(function () {
    // Public
    Route::get('/', [PublicController::class, 'families'])->name('index');
    Route::get('/{family}/pohon-keluarga', [PublicController::class, 'familyTree'])->name('tree');
    Route::get('/{family}', [PublicController::class, 'family'])->name('show');

    // Registration (Guest only)
    Route::middleware('guest.family')->group(function () {
        Route::get('/daftar', [FamilyFormController::class, 'create'])->name('create');
        Route::post('/daftar', [FamilyFormController::class, 'store'])->name('store');
    });

    // CRUD untuk keluarga (hanya owner keluarga yang login)
    Route::middleware('auth.family')->group(function () {
        Route::get('/{family}/edit', [FamilyFormController::class, 'edit'])->name('edit');
        Route::put('/{family}', [FamilyFormController::class, 'update'])->name('update');
        Route::delete('/{family}', [FamilyFormController::class, 'destroy'])->name('destroy');
    });
});

Route::get('/family-form', [FamilyFormController::class, 'create'])->name('family.form');

/*
|--------------------------------------------------------------------------
| Members (Anggota)
|--------------------------------------------------------------------------
*/
Route::prefix('anggota')->name('members.')->group(function () {
    // Public
    Route::get('/', [PublicController::class, 'members'])->name('index');
    Route::get('/{member}', [PublicController::class, 'member'])->name('show');

    // CRUD (auth only)
    Route::middleware('auth.family')->group(function () {
        Route::get('/tambah', [MemberFormController::class, 'create'])->name('create');
        Route::post('/', [MemberFormController::class, 'store'])->name('store');
        Route::get('/{member}/edit', [MemberFormController::class, 'edit'])->name('edit');
        Route::put('/{member}', [MemberFormController::class, 'update'])->name('update');
        Route::delete('/{member}', [MemberFormController::class, 'destroy'])->name('destroy');
    });
});


/*
|--------------------------------------------------------------------------
| Authentication (Login/Logout)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->name('auth.')->group(function () {
    Route::middleware('guest.family')->group(function () {
        Route::get('/login', [FamilyFormController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [FamilyFormController::class, 'login'])->name('login.submit');
    });

    Route::middleware('auth.family')->group(function () {
        Route::post('/logout', [FamilyFormController::class, 'logout'])->name('logout');
    });
});

/*
|--------------------------------------------------------------------------
| Activity Logs
|--------------------------------------------------------------------------
*/
Route::get('/riwayat-aktivitas', [PublicController::class, 'activityHistory'])->name('activity.history');
Route::get('/riwayat-perubahan', [PublicController::class, 'activityHistory'])->name('public.activity_logs');
