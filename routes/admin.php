<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\TagController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::get('articles/{article}/history', [ArticleController::class, 'history'])->name('articles.history');
    Route::post('articles/{article}/history/{activity}/restore', [ArticleController::class, 'restore'])->name('articles.history.restore');
    Route::patch('articles/{article}/slug', [ArticleController::class, 'updateSlug'])->name('articles.slug.update');
    Route::post('articles/{article}/submit-review', [ArticleController::class, 'submitForReview'])->name('articles.submit-review');
    Route::post('articles/{article}/approve', [ArticleController::class, 'approve'])->name('articles.approve');
    Route::post('articles/{article}/publish', [ArticleController::class, 'publish'])->name('articles.publish');

    Route::resource('articles', ArticleController::class);
    Route::resource('articles.comments', CommentController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('tags', TagController::class)->except(['show']);
});
