<?php

use App\Http\Controllers\Guest\ArticleController;
use App\Http\Controllers\Guest\ContactSubmissionController;
use App\Http\Controllers\Guest\HomeController;
use App\Http\Controllers\Guest\PageController;
use Illuminate\Support\Facades\Route;

// Home pages
Route::get('/', [HomeController::class, 'index'])
    ->middleware('cacheResponse')
    ->name('home');

// Static guest pages from resources/views/guest/*
Route::controller(PageController::class)->middleware('cacheResponse')->group(function (): void {
    Route::get('about', 'about')->name('about');
    Route::get('contact', 'contact')->name('contact');
    Route::get('term', 'term')->name('term');
    Route::get('policy', 'policy')->name('policy');
});

Route::post('contact', [ContactSubmissionController::class, 'store'])
    ->middleware('throttle:20,1')
    ->name('contact.submit');

// Article pages
Route::get('articles', [ArticleController::class, 'index'])
    ->middleware('cacheResponse')
    ->name('articles.index');

Route::get('chu-de/{slug}', [ArticleController::class, 'byTag'])
    ->middleware('cacheResponse')
    ->name('tags.articles');

Route::get('{article}.html', [ArticleController::class, 'show'])
    ->where('article', '[A-Za-z0-9\-]+')
    ->middleware('cacheResponse')
    ->name('articles.show');

Route::get('{slug}', [ArticleController::class, 'byCategory'])
    ->where('slug', '^(?!(about|contact|term|policy|articles|chu-de)$)[A-Za-z0-9\-]+')
    ->middleware('cacheResponse')
    ->name('categories.articles');

require __DIR__.'/admin.php';
