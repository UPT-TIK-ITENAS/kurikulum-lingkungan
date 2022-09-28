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
        Route::post('/admin/cpl/store', [CPLController::class, 'store'])->name('admin.cpl.store');
        Route::post('/admin/cpl/update/{id}', [CPLController::class, 'update'])->name('admin.cpl.update');
        Route::get('/admin/cpl/delete/{id}', [CPLController::class, 'delete'])->name('admin.cpl.delete');
        Route::get('/admin/cpmk/index', [CPMKController::class, 'index'])->name('admin.cpmk.index');
        Route::get('/admin/cpmk/listmatakuliah', [CPMKController::class, 'listmatakuliah'])->name('admin.cpmk.listmatakuliah');
        Route::get('/admin/cpmk/kelola/{id}', [CPMKController::class, 'kelola'])->name('admin.cpmk.kelola');
        Route::post('/admin/cpmk/store', [CPMKController::class, 'store'])->name('admin.cpmk.store');
        Route::post('/admin/cpmk/update/{id}', [CPMKController::class, 'update'])->name('admin.cpmk.update');
        Route::get('/admin/cpmk/delete/{id}', [CPMKController::class, 'delete'])->name('admin.cpmk.delete');
        Route::get('/admin/cpmk/cpl/{idcpmk}', [CPMKController::class, 'cpl'])->name('admin.cpmk.cpl');
        Route::post('/admin/cpmk/store_cpmk_cpl', [CPMKController::class, 'store_cpmk_cpl'])->name('admin.cpmk.store_cpmk_cpl');
        Route::post('/admin/cpmk/update_cpmk_cpl/{id}', [CPMKController::class, 'update_cpmk_cpl'])->name('admin.cpmk.update_cpmk_cpl');
        Route::get('/admin/cpmk/delete_cpmk_cpl/{id}', [CPMKController::class, 'delete_cpmk_cpl'])->name('admin.cpmk.delete_cpmk_cpl');

        Route::get('/admin/ce/matriks', [CEController::class, 'index'])->name('admin.ce.matriks');
        Route::get('/admin/data-master/mahasiswa', [MainController::class, 'index_mahasiswa'])->name('admin.mahasiswa.index');
        Route::get('/admin/data-master/mahasiswa/nilai/{nim}', [MainController::class, 'nilai_mahasiswa'])->name('admin.mahasiswa.nilai');
        Route::get('/admin/data-master/matkul', [MainController::class, 'index_matkul'])->name('admin.matkul.index');
        Route::get('/admin/data-master/listmahasiswa', [MainController::class, 'listmahasiswa'])->name('admin.mahasiswa.listmahasiswa');
    });
});
