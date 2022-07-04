<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\URLShortenerController;

/**
 * Get URLs list
 * Create Short URL
 * Get original URL from shorten URL
 *
 */
Route::prefix('url-shorten')->group(function () {
    Route::post('/', [URLShortenerController::class, 'store'])->name('url-shorten.store');
    Route::get('/', [URLShortenerController::class, 'index'])->name('url-shorten.index');
    Route::get('/get-url/{hash}', [URLShortenerController::class, 'getOriginalUrl'])->name('url-shorten.showOriginal');
});
