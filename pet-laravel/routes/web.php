<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DogController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\FootballController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MyCatController;
use App\Http\Controllers\MyCityController;
use App\Http\Controllers\MyCountryController;
use App\Http\Controllers\MyPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/my_page', [MyPageController::class, 'index']);

Route::get('/my_city', [MyCityController::class, 'index']);

Route::get('/my_cat', [MyCatController::class, 'index']);

Route::get('/my_country', [MyCountryController::class, 'index']);

Route::get('/dog', [DogController::class, 'index']);

Route::get('/football', [FootballController::class, 'index']);

Route::get('/food', [FoodController::class, 'index']);

Route::get('/book', [BookController::class, 'index'])->name('book.index');

Route::get('/book/filter/{filter}', [BookController::class, 'filter']);

Route::get('/book/create', [BookController::class, 'create'])->name('book.create');

Route::post('/book', [BookController::class, 'store'])->name('book.store');

Route::get('/book/{book}', [BookController::class, 'show'])->name('book.show');

Route::get('/book/{book}/edit', [BookController::class, 'edit'])->name('book.edit');

Route::patch('/book/{book}', [BookController::class, 'update'])->name('book.update');

Route::delete('/book/{book}', [BookController::class, 'destroy'])->name('book.destroy');

Route::get('/book/update', [BookController::class, 'update']);

Route::get('/book/delete', [BookController::class, 'delete']);

Route::get('/book/first_or_create', [BookController::class, 'firstOrCreate']);

Route::get('/book/update_or_create', [BookController::class, 'updateOrCreate']);

Route::get('/about', [AboutController::class, 'index'])->name('about.index');

Route::get('/contacts', [ContactController::class, 'index'])->name('contact.index');

Route::get('/main', [MainController::class, 'index'])->name('main.index');
