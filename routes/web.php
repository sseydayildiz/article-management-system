<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Route;

// --- Ziyaretçiye Açık Rotalar ---
Route::get('/', [FrontController::class, 'index'])->name('front.index');
Route::get('/article/{id}', [FrontController::class, 'show'])->name('front.show');
Route::post('/article/{id}/comment', [FrontController::class, 'storeComment'])->name('front.comment.store');

// --- Giriş Yapmış Kullanıcı / Yazar Rotaları ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('articles', ArticleController::class);

    // Yorum Yönetim Rotaları
    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::patch('/comments/{id}/approve', [CommentController::class, 'approve'])->name('comments.approve');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Profil Rotaları
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';