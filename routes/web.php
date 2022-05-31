<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', fn () => redirect()->route('articles.index'));

Route::get('/articles', [ArticleController::class, 'index'])->middleware(['auth', 'remember'])->name('articles.index');
Route::delete('/articles/{articleId}', [ArticleController::class, 'destroy'])->middleware(['auth'])->name('articles.destroy');

require __DIR__.'/auth.php';
