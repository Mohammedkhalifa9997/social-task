<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\Post\PostController;
use App\Http\Controllers\Front\Profile\ProfileController;
use App\Http\Controllers\Front\Like\LikeController;
use App\Http\Controllers\Front\Comment\CommentController;


Route::get('/', [PostController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::controller(PostController::class)
        ->as('posts.')
        ->group(function () {

            Route::post('/posts', 'store')
                ->name('store');
            Route::put('/posts/{post}', 'update')
                ->name('update');
            Route::delete('/posts/{post}', 'destroy')
                ->name('destroy');
        });

    Route::controller(LikeController::class)
        ->as('likes.')
        ->group(function () {

            Route::post('/likes/toggle', 'toggleLike')
                ->name('toggle');
            Route::get('/likes/users', 'getLikedUsers')
                ->name('users');
        });

    Route::controller(CommentController::class)
        ->as('comments.')
        ->group(function () {
            Route::post('/comments', 'store')
                ->name('store');
            Route::get('/comments', 'index')
                ->name('index');
        });
});

require __DIR__ . '/auth.php';
