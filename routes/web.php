<?php

use App\Http\Controllers\Admin\CEController;
use App\Http\Controllers\Admin\CPLController;
use App\Http\Controllers\Admin\CPMKController;
use App\Http\Controllers\Admin\MainController;
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
Route::middleware(['isLogin'])->group(function () {
    Route::group(['prefix' => ''], function () {
        Route::get('/admin/home', [MainController::class, 'index'])->name('admin.home');
        Route::get('/admin/cpl/index', [CPLController::class, 'index'])->name('admin.cpl.index');
        Route::get('/admin/cpl/store', [CPLController::class, 'store'])->name('admin.cpl.store');
        Route::get('/admin/cpl/update/{id}', [CPLController::class, 'update'])->name('admin.cpl.update');
        Route::get('/admin/cpl/delete/{id}', [CPLController::class, 'delete'])->name('admin.cpl.delete');
        Route::get('/admin/cpmk/index', [CPMKController::class, 'index'])->name('admin.cpmk.index');
        Route::get('/admin/ce/matriks', [CEController::class, 'index'])->name('admin.ce.matriks');
        Route::get('/admin/data-master/mahasiswa', [MainController::class, 'index'])->name('admin.mahasiswa.index');
        Route::get('/admin/data-master/matkul', [MainController::class, 'index'])->name('admin.matkul.index');
    });
});
