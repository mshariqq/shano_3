<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// ScrapeProducts ROUTES
Route::get('/scrape', [App\Http\Controllers\ScrapeProductsController::class, 'index'])->name('scrape');
Route::get('/scrape/{id}', [App\Http\Controllers\ScrapeProductsController::class, 'show'])->name('scrape.show');
Route::get('/scrape/{id}/edit', [App\Http\Controllers\ScrapeProductsController::class, 'edit'])->name('scrape.edit');
Route::put('/scrape/{id}', [App\Http\Controllers\ScrapeProductsController::class, 'update'])->name('scrape.update');
Route::delete('/scrape/{id}', [App\Http\Controllers\ScrapeProductsController::class, 'destroy'])->name('scrape.destroy');
// add new scrape product
Route::get('/scrape/add', [App\Http\Controllers\ScrapeProductsController::class, 'create'])->name('scrape.create');
Route::post('/scrape/add', [App\Http\Controllers\ScrapeProductsController::class, 'store'])->name('scrape.store');
// create new project in scrape
Route::post('/scrape/{id}/project/add', [App\Http\Controllers\ScrapeProductsController::class, 'createProject'])->name('scrape.project.create');

// route to run shell_exec on homecontroller
Route::post('/exec', [App\Http\Controllers\HomeController::class, 'exec'])->name('exec');
