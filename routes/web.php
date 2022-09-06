<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
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


Route::controller(LoginController::class)->group(function () {
    Route::get('/', 'index')->name('login');
    Route::post('/login', 'login')->name('auth');
    Route::post('/logout', 'logout')->name('logout');
});
Route::middleware(['isLogin'])->group(function () {
    Route::group(['prefix' => ''], function () {
        Route::get('/home', [HomeController::class, 'index'])->name('home');
    });
});
