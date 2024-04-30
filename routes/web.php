<?php

use App\Http\Controllers\Admin\CEController;
use App\Http\Controllers\Admin\CPLController;
use App\Http\Controllers\Admin\CPMKController;
use App\Http\Controllers\Admin\IKController;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\SubCPMKController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\BobotController;
use App\Http\Controllers\Admin\MKPilihanController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\FakultasController;
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
        Route::get('/dosen/home', [DosenController::class, 'index'])->name('dosen.home');

        //CPMK
        Route::get('/dosen/cpmk/index', [DosenController::class, 'index_cpmk'])->name('dosen.cpmk.index');
        Route::get('/dosen/cpmk/listmatakuliah', [DosenController::class, 'listmatakuliah'])->name('dosen.cpmk.listmatakuliah');
        Route::get('/dosen/cpmk/kelola/{id}', [DosenController::class, 'kelola'])->name('dosen.cpmk.kelola');
        Route::post('/dosen/cpmk/store', [DosenController::class, 'store'])->name('dosen.cpmk.store');
        Route::post('/dosen/cpmk/update/{id}', [DosenController::class, 'update'])->name('dosen.cpmk.update');
        Route::get('/dosen/cpmk/delete/{id}', [DosenController::class, 'delete'])->name('dosen.cpmk.delete');
        Route::get('/dosen/cpmk/cpl/{idcpmk}', [DosenController::class, 'cpl'])->name('dosen.cpmk.cpl');
        Route::post('/dosen/cpmk/store_cpmk_cpl', [DosenController::class, 'store_cpmk_cpl'])->name('dosen.cpmk.store_cpmk_cpl');
        Route::post('/dosen/cpmk/update_cpmk_cpl/{id}', [DosenController::class, 'update_cpmk_cpl'])->name('dosen.cpmk.update_cpmk_cpl');
        Route::get('/dosen/cpmk/delete_cpmk_cpl/{id}', [DosenController::class, 'delete_cpmk_cpl'])->name('dosen.cpmk.delete_cpmk_cpl');

        // SUB-CPMK
        Route::get('/dosen/subcpmk/index/{id}', [DosenController::class, 'index_subcpmk'])->name('dosen.subcpmk.index');
        Route::post('/dosen/subcpmk/store', [DosenController::class, 'store_subcpmk'])->name('dosen.subcpmk.store');
        Route::post('/dosen/subcpmk/update/{id}', [DosenController::class, 'update_subcpmk'])->name('dosen.subcpmk.update');
        Route::get('/dosen/subcpmk/delete/{id}', [DosenController::class, 'delete_subcpmk'])->name('dosen.subcpmk.delete');

        // BOBOT
        Route::get('/dosen/bobot/{id}', [DosenController::class, 'bobot'])->name('dosen.bobot');
        Route::post('/dosen/bobot/store', [DosenController::class, 'store_bobot'])->name('dosen.bobot.store');

        // MASTER MK
        Route::get('/dosen/data-master/matkul', [MainController::class, 'index_matkul'])->name('dosen.matkul.index');
        Route::get('/dosen/data-master/listmahasiswa', [MainController::class, 'listmahasiswa'])->name('dosen.mahasiswa.listmahasiswa');
        Route::get('/dosen/data-master/matkul/listmatakuliah', [MainController::class, 'listmatakuliah'])->name('dosen.matkul.listmatakuliah');
        Route::post('/dosen/data-master/matkul', [MainController::class, 'storemk'])->name('dosen.matkul.storemk');
        Route::post('/dosen/data-master', [MainController::class, 'storemhs'])->name('dosen.matkul.storemhs');
    });

    Route::group(['prefix' => ''], function () {
        Route::get('/fakultas/home', [FakultasController::class, 'index'])->name('fakultas.home');

        //CPMK
        Route::get('/fakultas/cpmk/index', [FakultasController::class, 'index_cpmk'])->name('fakultas.cpmk.index');
        Route::get('/fakultas/cpmk/listmatakuliah', [FakultasController::class, 'listmatakuliah'])->name('fakultas.cpmk.listmatakuliah');
        Route::get('/fakultas/cpmk/kelola/{id}', [FakultasController::class, 'kelola'])->name('fakultas.cpmk.kelola');
        Route::post('/fakultas/cpmk/store', [FakultasController::class, 'store'])->name('fakultas.cpmk.store');
        Route::post('/fakultas/cpmk/update/{id}', [FakultasController::class, 'update'])->name('fakultas.cpmk.update');
        Route::get('/fakultas/cpmk/delete/{id}', [FakultasController::class, 'delete'])->name('fakultas.cpmk.delete');
        Route::get('/fakultas/cpmk/cpl/{idcpmk}', [FakultasController::class, 'cpl'])->name('fakultas.cpmk.cpl');
        Route::post('/fakultas/cpmk/store_cpmk_cpl', [FakultasController::class, 'store_cpmk_cpl'])->name('fakultas.cpmk.store_cpmk_cpl');
        Route::post('/fakultas/cpmk/update_cpmk_cpl/{id}', [FakultasController::class, 'update_cpmk_cpl'])->name('fakultas.cpmk.update_cpmk_cpl');
        Route::get('/fakultas/cpmk/delete_cpmk_cpl/{id}', [FakultasController::class, 'delete_cpmk_cpl'])->name('fakultas.cpmk.delete_cpmk_cpl');

        // SUB-CPMK
        Route::get('/fakultas/subcpmk/index/{id}', [FakultasController::class, 'index_subcpmk'])->name('fakultas.subcpmk.index');
        Route::post('/fakultas/subcpmk/store', [FakultasController::class, 'store_subcpmk'])->name('fakultas.subcpmk.store');
        Route::post('/fakultas/subcpmk/update/{id}', [FakultasController::class, 'update_subcpmk'])->name('fakultas.subcpmk.update');
        Route::get('/fakultas/subcpmk/delete/{id}', [FakultasController::class, 'delete_subcpmk'])->name('fakultas.subcpmk.delete');

        // BOBOT
        Route::get('/fakultas/bobot/{id}', [FakultasController::class, 'bobot'])->name('fakultas.bobot');
        Route::post('/fakultas/bobot/store', [FakultasController::class, 'store_bobot'])->name('fakultas.bobot.store');

        // MASTER MK
        Route::get('/fakultas/data-master/matkul', [FakultasController::class, 'index_matkul'])->name('fakultas.matkul.index');
        Route::get('/fakultas/data-master/matkul/listmatakuliah', [FakultasController::class, 'listmk'])->name('fakultas.matkul.listmatakuliah');
    });

    Route::group(['prefix' => ''], function () {
        Route::get('/admin/home', [MainController::class, 'index'])->name('admin.home');
        Route::get('/admin/cpl/index', [CPLController::class, 'index'])->name('admin.cpl.index');
        Route::post('/admin/cpl/store', [CPLController::class, 'store'])->name('admin.cpl.store');
        Route::post('/admin/cpl/update/{id}', [CPLController::class, 'update'])->name('admin.cpl.update');
        Route::get('/admin/cpl/delete/{id}', [CPLController::class, 'delete'])->name('admin.cpl.delete');
        Route::get('/admin/cpl/delete/{id}', [CPLController::class, 'delete'])->name('admin.cpl.delete');

        //Bobot CPL Padu
        Route::get('/admin/cpl/getBobotCPLPadu/', [CPLController::class, 'getBobotCPLPadu'])->name('admin.cpl.getBobotCPLPadu');
        Route::post('/admin/cpl/storeBobotCPLPadu', [CPLController::class, 'storeBobotCPLPadu'])->name('admin.cpl.storeBobotCPLPadu');

        //Bobot CPL MK 
        Route::get('/admin/cpl/matriksCPLMK/', [CPLController::class, 'matriksCPLMK'])->name('admin.cpl.matriksCPLMK');

        //Bobot CPL 
        Route::get('/admin/cpl/matriksCPL/', [CPLController::class, 'matriksCPL'])->name('admin.cpl.matriksCPL');

        //MK Pilihan 
        Route::get('/admin/mkp/index/', [MKPilihanController::class, 'index'])->name('admin.mkp.index');
        Route::post('/admin/mkp/store', [MKPilihanController::class, 'store'])->name('admin.mkp.store');
        //IK
        Route::get('/admin/cpl/ik/{id}', [IKController::class, 'index'])->name('admin.cpl.ik');
        Route::post('/admin/cpl/ik/store', [IKController::class, 'store'])->name('admin.cpl.ik.store');
        Route::post('/admin/cpl/ik/update/{id}', [IKController::class, 'update'])->name('admin.cpl.ik.update');
        Route::get('/admin/cpl/ik/delete/{id}', [IKController::class, 'delete'])->name('admin.cpl.ik.delete');


        //CPMK
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
        Route::post('/admin/cpmk/store_pengampu', [CPMKController::class, 'store_pengampu'])->name('admin.cpmk.store_pengampu');


        //SubCPMK
        Route::get('/admin/subcpmk/index/{id}', [SubCPMKController::class, 'index'])->name('admin.subcpmk.index');
        Route::post('/admin/subcpmk/store', [SubCPMKController::class, 'store'])->name('admin.subcpmk.store');
        Route::post('/admin/subcpmk/update/{id}', [SubCPMKController::class, 'update'])->name('admin.subcpmk.update');
        Route::get('/admin/subcpmk/delete/{id}', [SubCPMKController::class, 'delete'])->name('admin.subcpmk.delete');

        Route::get('/admin/bobot/{id}', [BobotController::class, 'bobot'])->name('admin.bobot');
        Route::post('/admin/bobot/store', [BobotController::class, 'store'])->name('admin.bobot.store');

        Route::get('/admin/ce/matriks', [CEController::class, 'index'])->name('admin.ce.matriks');
        Route::get('/admin/ce/matrikscpl', [CEController::class, 'matriks'])->name('admin.ce.matrikscpl');
        Route::get('/admin/data-master/mahasiswa', [MainController::class, 'index_mahasiswa'])->name('admin.mahasiswa.index');
        Route::get('/admin/data-master/mahasiswa/nilai/{nim}', [MainController::class, 'nilai_mahasiswa'])->name('admin.mahasiswa.nilai');
        Route::get('/admin/data-master/mahasiswa/cpl/{nim}', [CPLController::class, 'cpl_mahasiswa'])->name('admin.mahasiswa.cpl');
        Route::get('/admin/data-master/matkul', [MainController::class, 'index_matkul'])->name('admin.matkul.index');
        Route::get('/admin/data-master/listmahasiswa', [MainController::class, 'listmahasiswa'])->name('admin.mahasiswa.listmahasiswa');
        Route::get('/admin/data-master/matkul/listmatakuliah', [MainController::class, 'listmatakuliah'])->name('admin.matkul.listmatakuliah');
        Route::post('/admin/data-master/matkul', [MainController::class, 'storemk'])->name('admin.matkul.storemk');
        Route::post('/admin/data-master', [MainController::class, 'storemhs'])->name('admin.matkul.storemhs');


        Route::get('/admin/data-master/mahasiswa/subCPMK/{nim}', [SubCPMKController::class, 'sc_mahasiswa'])->name('admin.mahasiswa.sc');
        Route::get('/admin/data-master/mahasiswa/SubCPMK/Detail/{nim}', [SubCPMKController::class, 'sc_mahasiswadetail'])->name('admin.mahasiswa.sc_detail');

        // for chart 
        Route::get('/admin/data-charts/labelCPL/{data}', [CPLController::class, 'getLabelCPLChart'])->name('admin.cpl.getLabelCPLChart');
        Route::post('/admin/data-charts/labelCPLBySemester', [CPLController::class, 'getLabelCPLChartBySemester'])->name('admin.cpl.getLabelCPLChartBySemester');
        Route::post('/admin/data-charts/labelCPLMhsBySemester', [CPLController::class, 'getLabelCPLChartMhsBySemester'])->name('admin.cpl.getLabelCPLChartMhsBySemester');


        // Lulusan
        Route::get('/admin/data-master/lulusan', [MainController::class, 'index_lulusan'])->name('admin.lulusan.index');
        Route::get('/admin/data-master/listlulusan', [MainController::class, 'listlulusan'])->name('admin.lulusan.listlulusan');
        Route::post('/admin/data-master/storelulusan', [MainController::class, 'storelulusan'])->name('admin.lulusan.store');
        Route::get('/admin/data-master/storelulusan/{id}', [MainController::class, 'deletelulusan'])->name('admin.lulusan.delete');
        Route::get('/admin/data-master/printskpi/{nim}', [MainController::class, 'printskpi'])->name('admin.lulusan.printskpi');
    });
});
