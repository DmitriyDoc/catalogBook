<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'registration'])->middleware('guest');
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::get('/categories', [\App\Http\Controllers\Api\CategoriesController::class, 'index']);
Route::get('/categories/{slug}', [\App\Http\Controllers\Api\CategoriesController::class, 'show']);
Route::get('/categories/show/{id}', [\App\Http\Controllers\Api\CategoriesController::class, 'showById']);

Route::get('/books', [\App\Http\Controllers\Api\BooksController::class, 'index']);
Route::get('/books/show/{id}', [\App\Http\Controllers\Api\BooksController::class, 'show']);

Route::get('/writers', [\App\Http\Controllers\Api\WritersController::class, 'index']);
Route::get('/writers/show/{id}', [\App\Http\Controllers\Api\WritersController::class, 'show']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::controller(\App\Http\Controllers\Api\BooksController::class)->group(function () {
        Route::post('/books/add', 'store');
        Route::post('/books/update', 'update');
        Route::delete('/books/del', 'destroy');
    });
    Route::controller(\App\Http\Controllers\Api\WritersController::class)->group(function () {
        Route::post('/writers/add', 'store');
        Route::post('/writers/update', 'update');
        Route::delete('/writers/del', 'destroy');
    });
    Route::controller(\App\Http\Controllers\Api\CategoriesController::class)->group(function () {
        Route::post('/categories/add', 'store');
        Route::post('/categories/update', 'update');
        Route::delete('/categories/del', 'destroy');
    });
});


