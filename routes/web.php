<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\UserController;

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', [HomeController::class, 'about'])->name('about');
Route::get('/akademik', [HomeController::class, 'academic'])->name('academic');
Route::get('/berita', [HomeController::class, 'news'])->name('news');
Route::get('/berita/{id}', [HomeController::class, 'newsDetail'])->name('news.detail');
Route::get('/galeri', [HomeController::class, 'gallery'])->name('gallery');
Route::get('/fasilitas', [HomeController::class, 'facilities'])->name('facilities');
Route::get('/guru-staff', [HomeController::class, 'teachers'])->name('teachers');

// Auth Routes
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes (Protected by auth middleware)
Route::prefix('admin')->middleware(['admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // News Management
    Route::get('/berita', [NewsController::class, 'index'])->name('admin.berita.index');
    Route::post('/berita', [NewsController::class, 'store'])->name('admin.berita.store');
    Route::put('/berita/{id}', [NewsController::class, 'update'])->name('admin.berita.update');
    Route::delete('/berita/{id}', [NewsController::class, 'destroy'])->name('admin.berita.destroy');
    Route::get('/berita/{id}/toggle-status', [NewsController::class, 'toggleStatus'])->name('admin.berita.toggle');

    // Teachers Management
    Route::get('/guru', [TeacherController::class, 'index'])->name('admin.guru.index');
    Route::post('/guru', [TeacherController::class, 'store'])->name('admin.guru.store');
    Route::put('/guru/{id}', [TeacherController::class, 'update'])->name('admin.guru.update');
    Route::delete('/guru/{id}', [TeacherController::class, 'destroy'])->name('admin.guru.destroy');

    // Facilities Management
    Route::get('/fasilitas', [FacilityController::class, 'index'])->name('admin.fasilitas.index');
    Route::post('/fasilitas', [FacilityController::class, 'store'])->name('admin.fasilitas.store');
    Route::put('/fasilitas/{id}', [FacilityController::class, 'update'])->name('admin.fasilitas.update');
    Route::delete('/fasilitas/{id}', [FacilityController::class, 'destroy'])->name('admin.fasilitas.destroy');

    // Gallery Management
    Route::get('/galeri', [GalleryController::class, 'index'])->name('admin.galeri.index');
    Route::post('/galeri', [GalleryController::class, 'store'])->name('admin.galeri.store');
    Route::put('/galeri/{id}', [GalleryController::class, 'update'])->name('admin.galeri.update');
    Route::delete('/galeri/{id}', [GalleryController::class, 'destroy'])->name('admin.galeri.destroy');

    // Pages Management
    Route::get('/halaman', [PageController::class, 'index'])->name('admin.halaman.index');
    Route::post('/halaman', [PageController::class, 'store'])->name('admin.halaman.store');
    Route::put('/halaman/{id}', [PageController::class, 'update'])->name('admin.halaman.update');
    Route::delete('/halaman/{id}', [PageController::class, 'destroy'])->name('admin.halaman.destroy');

    // Users Management (Admin only)
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});
