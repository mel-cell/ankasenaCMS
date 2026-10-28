<?php

use App\Http\Controllers\AnalitikController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

// ---- Halaman situs ----
Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/tentang', [SiteController::class, 'tentang'])->name('tentang');
Route::get('/kontak', [SiteController::class, 'kontak'])->name('kontak');
Route::get('/kabinet', [SiteController::class, 'kabinet'])->name('kabinet');
Route::get('/divisi/{slug}', [SiteController::class, 'divisi'])->name('divisi.show');
Route::get('/blog', [SiteController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [SiteController::class, 'artikel'])->name('blog.show');
Route::post('/saran', [SiteController::class, 'saran'])->name('saran');

// ---- Sistem voting (fase 2) ----
Route::get('/vote', [VoteController::class, 'index'])->name('vote.index');
Route::post('/vote', [VoteController::class, 'store'])->name('vote.store');
Route::get('/vote/hasil', [VoteController::class, 'hasil'])->name('vote.hasil');

// ---- Login Google untuk pemilih ----
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');

// ---- Analitik penuh superadmin (di-embed iframe di dashboard Filament) ----
Route::get('/analitik-cp', [AnalitikController::class, 'cp'])->name('analitik.embed');
