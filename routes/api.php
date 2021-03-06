<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout']);
Route::post('register', [RegisterController::class, 'register']);
Route::resource('user', UserController::class)->only(['index','show']);
Route::resource('book',BooksController::class)->except(['edit','create']);
Route::post('rentBook',[BooksController::class, 'rentBook']);
Route::post('returnBook',[BooksController::class, 'returnBook']);
