<?php

use App\Http\Controllers\Admin\AbsensiController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\BatchController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\Pos\PosCrhController;
use App\Http\Controllers\Admin\Pos\PosKlimatologiController;
use App\Http\Controllers\Admin\Pos\PosTmaController;
use App\Http\Controllers\Admin\RecentlyController;
use App\Http\Controllers\Admin\SubdasController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\WaterQualityController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Pages\CRHController;
use App\Http\Controllers\Pages\PagesKlimatologiController;
use App\Http\Controllers\Pages\PagesQualityWater;
use App\Http\Controllers\Pages\TelementriTMAController;
use App\Http\Controllers\Pages\TelemtriCRHController;
use App\Http\Controllers\Pages\TMAController;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Artisan;
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

Route::get('/', [TMAController::class, 'index'])->name('home');
Route::get('/pos/{id}', [TMAController::class, 'show'])->name('pos.show');
Route::post('/generatePDF', [TMAController::class, 'generatePDF'])->name('pos.generatePDF');

Route::prefix('curah-hujan')->group(function () {
    Route::get('/', [CRHController::class, 'index'])->name('guest.pos.crh');
    Route::get('/{id}/show', [CRHController::class, 'show'])->name('guest.pos.crh.show');
    Route::post('generatePDF', [CRHController::class, 'generatePDF'])->name('guest.pos.crh.generatePDF');
});

Route::prefix('klimatologis')->group(function () {
    Route::get('/', [PagesKlimatologiController::class, 'index'])->name('guest.pos.klimatologi');
    Route::get('/{id}/show', [PagesKlimatologiController::class, 'show'])->name('guest.pos.klimatologi.show');
    Route::post('generatePDF', [PagesKlimatologiController::class, 'generatePDF'])->name('guest.pos.klimatologi.generatePDF');
});

Route::prefix('qualitys')->group(function () {
    Route::get('/', [PagesQualityWater::class, 'index'])->name('guest.pos.quality');
    Route::get('/{id}/show', [PagesQualityWater::class, 'show'])->name('guest.pos.quality.show');
    Route::get('/{id}/pdf', [WaterQualityController::class, 'generatePDFQualityWater'])->name('guest.pos.quality.generatePDFQualityWater');
});

Route::prefix('telementris')->group(function () {
    Route::prefix('tmas')->group(function () {
        Route::get('/', [TelementriTMAController::class, 'index'])->name('telementri.tma');
        Route::get('/{sensor_company_id}/show', [TelementriTMAController::class, 'show'])->name('telementri.show');
        Route::post('generatePdf', [TelementriTMAController::class, 'generatePDF'])->name('telementri.generatePDF');
    });

    Route::prefix('crhs')->group(function () {
        Route::get('/', [TelemtriCRHController::class, 'index'])->name('telementri.crh');
        Route::get('/{sensor_company_id}/show', [TelemtriCRHController::class, 'show'])->name('telementri.crh.show');
    });
});

Route::prefix('login')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/check', [AuthController::class, 'login'])->name('login.post');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('subdas')->group(function () {
        Route::get('/', [SubdasController::class, 'index'])->name('subdas');
        Route::get('/create', [SubdasController::class, 'create'])->name('subdas.create');
        Route::post('/store', [SubdasController::class, 'store'])->name('subdas.store');
        Route::get('/{id}/edit', [SubdasController::class, 'edit'])->name('subdas.edit');
        Route::put('/{id}/update', [SubdasController::class, 'update'])->name('subdas.update');
        Route::delete('/{id}/destroy', [SubdasController::class, 'destroy'])->name('subdas.destroy');
    });

    Route::prefix('post')->group(function () {
        Route::get('/create/{jenisPos}', [PosTmaController::class, 'create'])->name('pos.create');
        Route::get('/{id}/edit/{jenisPos}', [PosTmaController::class, 'edit'])->name('pos.edit');

        Route::prefix('tmas')->group(function () {
            Route::get('/', [PosTmaController::class, 'index'])->name('pos.tma');
            Route::post('/store', [PosTmaController::class, 'store'])->name('pos.tma.store');
            Route::get('/{id}/list', [PosTmaController::class, 'show'])->name('pos.tma.show');
            Route::put('/{id}/update', [PosTmaController::class, 'update'])->name('pos.tma.update');
            Route::delete('/{id}/destroy', [PosTmaController::class, 'destroy'])->name('pos.tma.destroy');
            Route::post('/getRegencie', [PosTmaController::class, 'getRegencie'])->name('pos.tma.getRegencie');
            Route::get('/{start_date}/{end_date}/{id}/pdf', [PosTmaController::class, 'generatePDFTMA'])->name('pos.tma.generatePDFTMA');
            Route::get('/{start_date}/{end_date}/{id}/export', [PosTmaController::class, 'generateExcelTMA'])->name('pos.tma.generateExcelTMA');

            Route::get('/{id}/addTMA', [PosTmaController::class, 'addTMA'])->name('pos.tma.addTMA');
            Route::get('/{id}/editTMA', [PosTmaController::class, 'editTMA'])->name('pos.tma.editTMA');
            Route::post('storeTMA', [PosTmaController::class, 'storeTMA'])->name('pos.tma.storeTMA');
            Route::put('/{id}/updateTMA', [PosTmaController::class, 'updateTMA'])->name('pos.tma.updateTMA');
            Route::delete('/{id}/destroyTMA', [PosTmaController::class, 'destroyTMA'])->name('pos.tma.destroyTMA');

            Route::post('/moveToQualityWater', [PosTmaController::class, 'moveToQualityWater'])->name('admin.moveToQualityWater');
        });

        Route::prefix('crh')->group(function () {
            Route::get('/', [PosCrhController::class, 'index'])->name('pos.crh');
            Route::get('/{id}/list', [PosCrhController::class, 'show'])->name('pos.crh.show');
            Route::get('/{id}/createCRH', [PosCrhController::class, 'createCRH'])->name('pos.crh.createCRH');
            Route::post('/storeCRH', [PosCrhController::class, 'storeCRH'])->name('pos.crh.storeCRH');
            Route::get('/{id}/editCRH', [PosCrhController::class, 'editCRH'])->name('pos.crh.editCRH');
            Route::put('/{id}/updateCRH', [PosCrhController::class, 'updateCRH'])->name('pos.crh.updateCRH');
            Route::delete('/{id}/destroyCRH', [PosCrhController::class, 'destroyCRH'])->name('pos.crh.destroyCRH');

            Route::get('{start_date}/{end_date}/{id}/pdf', [PosCrhController::class, 'generatePDFCRH'])->name('pos.crh.generatePDFCRH');
            Route::get('{start_date}/{end_date}/{id}/export', [PosCrhController::class, 'generateExcelTMA'])->name('pos.crh.generateExcelTMA');
        });

        Route::prefix('klimatologi')->group(function () {
            Route::get('/', [PosKlimatologiController::class, 'index'])->name('pos.klimatologi');
            Route::get('/{id}/show', [PosKlimatologiController::class, 'show'])->name('pos.klimatologi.show');
            Route::get('/{id}/create', [PosKlimatologiController::class, 'createKlimatologi'])->name('pos.klimatologi.createKlimatologi');
            Route::post('store', [PosKlimatologiController::class, 'storeKlimatologi'])->name('pos.klimatologi.storeKlimatologi');
            Route::get('/{id}/edit', [PosKlimatologiController::class, 'editKlima'])->name('pos.klimatologi.editKlima');
            Route::put('/{id}/update', [PosKlimatologiController::class, 'updateKilma'])->name('pos.klimatologi.updateKilma');
            Route::delete('/{id}/destroy', [PosKlimatologiController::class, 'destroyKlima'])->name('pos.klimatologi.destroyKlima');
            Route::get('/{start_date}/{end_date}/{id}/pdf', [PosKlimatologiController::class, 'generatePDFKlima'])->name('pos.klimatologi.generatePDFKlima');
            Route::get('/{start_date}/{end_date}/{id}/excel', [PosKlimatologiController::class, 'generateExcelKlima'])->name('pos.klimatologi.generateExcelKlima');
        });

        Route::get('/{pos_id}/{tanggal}/{jenis}/absensi', [AbsensiController::class, 'generateAbsen'])->name('admin.absensi');
        Route::post('/create/absensi', [AbsensiController::class, 'saveAbsensi'])->name('admin.absensi.save');
    });

    Route::prefix('absensi')->group(function () {
        Route::get('/rekap', [AbsensiController::class, 'rekapAbsen'])->name('admin.rekap.absen');
        Route::get('/rekap/{jenis_id}/{month}/{years}/pdf', [AbsensiController::class, 'generatePdf'])->name('admin.rekap.absen.generatePdf');
    });

    Route::prefix('recently-pos')->group(function () {
        Route::get('/', [RecentlyController::class, 'index'])->name('recently.pos');
        Route::put('/{id}/restore', [RecentlyController::class, 'restore'])->name('recently.restore');
    });

    Route::prefix('water-quality')->group(function () {
        Route::get('/', [WaterQualityController::class, 'index'])->name('water.quality');
        Route::get('/{id}/create', [WaterQualityController::class, 'testQualityWater'])->name('water.quality.testQualityWater');
        Route::post('/store', [WaterQualityController::class, 'generateTestQualityWater'])->name('water.quality.generateTestQualityWater');
        Route::get('/{id}/result', [WaterQualityController::class, 'resultQualityWater'])->name('water.quality.resultQualityWater');
        Route::get('/{id}/history', [WaterQualityController::class, 'historyQualityControl'])->name('water.quality.historyQualityControl');
        Route::get('/{id}/pdf', [WaterQualityController::class, 'generatePDFQualityWater'])->name('water.quality.generatePDFQualityWater');
        Route::get('/{id}/show', [WaterQualityController::class, 'show'])->name('water.quality.show');
        Route::get('/{id}/edit', [WaterQualityController::class, 'edit'])->name('water.quality.edit');
        Route::put('/{id}/update', [WaterQualityController::class, 'update'])->name('water.quality.update');
        Route::delete('/{id}/destroy', [WaterQualityController::class, 'destroy'])->name('water.quality.destroy');
        Route::delete('/{id}/deletePosInQualityWater', [WaterQualityController::class, 'deletePosInQualityWater'])->name('water.quality.deletePosInQualityWater');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UsersController::class, 'index'])->name('users');
        Route::get('/create', [UsersController::class, 'create'])->name('users.create');
        Route::get('/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
        Route::post('/store', [UsersController::class, 'store'])->name('users.store');
        Route::put('/{id}/update', [UsersController::class, 'update'])->name('users.update');
        Route::delete('/{id}/destroy', [UsersController::class, 'destroy'])->name('users.destroy');
        Route::post('/getPosByJenis', [UsersController::class, 'getPosByJenis'])->name('users.getPosByJenis');
    });

    Route::prefix('batch')->group(function () {
        Route::prefix('tmas')->group(function () {
            Route::get('/', [BatchController::class, 'batchTMA'])->name('batch.batchTMA');
            Route::post('/importTMA', [BatchController::class, 'importTMA'])->name('batch.importTMA');
        });

        Route::prefix('crhs')->group(function () {
            Route::get('/', [BatchController::class, 'batchCRH'])->name('batch.batchCRH');
            Route::post('/importCRH', [BatchController::class, 'importCRH'])->name('batch.importCRH');
        });

        Route::prefix('klimatologis')->group(function () {
            Route::get('/', [BatchController::class, 'batchKlima'])->name('batch.batchKlima');
            Route::post('/importKlima', [BatchController::class, 'importKlima'])->name('batch.importKlima');
        });
    });

    Route::prefix('history')->group(function () {
        Route::prefix('tmas')->group(function () {
            Route::get('/', [HistoryController::class, 'tmaHistory'])->name('tma.history');
        });

        Route::prefix('crhs')->group(function () {
            Route::get('/', [HistoryController::class, 'crhHistory'])->name('crh.history');
        });

        Route::prefix('klimatologis')->group(function () {
            Route::get('/', [HistoryController::class, 'klimaHistory'])->name('klimatologi.history');
        });
    });

    Route::prefix('accounts')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('account');
        Route::post('/', [AccountController::class, 'changePassword'])->name('account.changePassword');
    });
});

Route::get('/foo', function () {
    Artisan::call('storage:link');

    return "Storage Sukses!";
});
