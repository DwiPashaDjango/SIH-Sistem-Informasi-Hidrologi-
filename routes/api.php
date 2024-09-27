<?php

use App\Http\Controllers\Api\TelementriController;
use App\Http\Controllers\API\TMAController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//API route for register new user
Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
//API route for login user
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);

Route::get('/post/{id}', [App\Http\Controllers\API\PosController::class, 'index']);
Route::get('/post/all/get', [App\Http\Controllers\API\PosController::class, 'semua']);


Route::get('/getAllPosTMA', [App\Http\Controllers\API\PosController::class, 'getAllPosTMA']);
Route::get('/getAllPosCRH', [App\Http\Controllers\API\PosController::class, 'getAllPosCRH']);
Route::get('/getAllPosKlimatologi', [App\Http\Controllers\API\PosController::class, 'getAllPosKlimatologi']);
Route::get('/getAllQualityWater', [App\Http\Controllers\API\PosController::class, 'getAllQualityWater']);
Route::post('/getRegencie', [App\Http\Controllers\API\PosController::class, 'getRegencie']);

Route::get('/telementriTMA', [App\Http\Controllers\API\TelementriController::class, 'tmas']);
Route::get('/telementriCRH', [App\Http\Controllers\API\TelementriController::class, 'crhs']);

Route::get('/post/full/{id}', [App\Http\Controllers\API\PosController::class, 'full']);
Route::get('/post/detail/{id}', [App\Http\Controllers\API\PosController::class, 'detail']);

Route::get('/post/titikhujan/{id}/{tanggal}', [App\Http\Controllers\API\PosController::class, 'titikhujan']);
Route::get('/post/titikmuka/{id}/{tanggal}', [App\Http\Controllers\API\PosController::class, 'titikmuka']);

Route::get('/jenis', [App\Http\Controllers\API\JenisController::class, 'index']);
Route::get('/jenis/{id}', [App\Http\Controllers\API\JenisController::class, 'detail']);
Route::get('/sms', [App\Http\Controllers\API\SMSController::class, 'index']);
Route::get('/dashboard', [App\Http\Controllers\API\AuthController::class, 'dashboard']);
Route::get('/sms-coba', [App\Http\Controllers\API\SMSController::class, 'coba']);



//filterDayNow
Route::get('/curah-hujan/day-now', [App\Http\Controllers\API\CurahHujanController::class, 'filterDayNow']);
Route::get('/tma/day-now', [App\Http\Controllers\API\TMAController::class, 'filterDayNow']);
Route::get('/klimatologi/day-now', [App\Http\Controllers\API\KlimatologiController::class, 'filterDayNow']);


Route::get('/curah-hujan/day-now/{id}', [App\Http\Controllers\API\CurahHujanController::class, 'dayNowByPosId']);
Route::get('/tma/day-now/{id}', [App\Http\Controllers\API\TMAController::class, 'dayNowByPosId']);
Route::get('/klimatologi/day-now/{id}', [App\Http\Controllers\API\KlimatologiController::class, 'dayNowByPosId']);

Route::get('/curah-hujan/full/{id}/{startDate}/{endDate}', [App\Http\Controllers\API\CurahHujanController::class, 'filterAll']);

Route::get('/curah-hujan/semua/data/{id}', [App\Http\Controllers\API\CurahHujanController::class, 'semuadata']);
Route::get('/tma/semua/data/{id}', [App\Http\Controllers\API\TMAController::class, 'semuadata']);
Route::get('/klimatologi/semua/data/{id}', [App\Http\Controllers\API\KlimatologiController::class, 'semuadata']);

Route::get('/curah-hujan/semua/datafull/{id}', [App\Http\Controllers\API\CurahHujanController::class, 'semuadatafull']);
Route::get('/tma/semua/datafull/{id}', [App\Http\Controllers\API\TMAController::class, 'semuadatafull']);
Route::get('/klimatologi/semua/datafull/{id}', [App\Http\Controllers\API\KlimatologiController::class, 'semuadatafull']);

Route::post('/curah-hujan/batch', [App\Http\Controllers\API\CurahHujanController::class, 'batch']);

//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('/post', [App\Http\Controllers\API\PosController::class, 'store']);
    Route::put('/post/{id}', [App\Http\Controllers\API\PosController::class, 'update']);
    Route::delete('/post/{id}', [App\Http\Controllers\API\PosController::class, 'delete']);

    Route::post('/change-password', [App\Http\Controllers\API\AuthController::class, 'changePassword']);

    //User
    Route::get('/user', [App\Http\Controllers\API\UserController::class, 'index']);
    Route::get('/profile', [App\Http\Controllers\API\UserController::class, 'profile']);
    Route::post('/user', [App\Http\Controllers\API\UserController::class, 'store']);
    Route::put('/user/{id}', [App\Http\Controllers\API\UserController::class, 'update']);
    Route::delete('/user/{id}', [App\Http\Controllers\API\UserController::class, 'delete']);
    Route::get('/user/{id}', [App\Http\Controllers\API\UserController::class, 'detail']);


    // API route for logout user
    Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);

    //Curah Hujan
    Route::get('/curah-hujan/{id}/{startDate}/{endDate}', [App\Http\Controllers\API\CurahHujanController::class, 'filter']);
    // Route::get('/curah-hujan/full/{id}/{startDate}/{endDate}', [App\Http\Controllers\API\CurahHujanController::class, 'filterAll']);
    Route::get('/curah-hujan/{id}', [App\Http\Controllers\API\CurahHujanController::class, 'index']);
    Route::get('/curah-hujan/detail/{id}', [App\Http\Controllers\API\CurahHujanController::class, 'detail']);
    Route::get('/curah-hujan/yearly/{id}/{year}/{type}', [App\Http\Controllers\API\CurahHujanController::class, 'getYearly']);
    Route::post('/curah-hujan', [App\Http\Controllers\API\CurahHujanController::class, 'store']);
    Route::post('/curah-hujan/delete/{id}', [App\Http\Controllers\API\CurahHujanController::class, 'delete']);


    //TMA
    Route::get('/tma/{id}/{startDate}/{endDate}', [App\Http\Controllers\API\TMAController::class, 'filter']);
    Route::get('/tma/full/{id}/{startDate}/{endDate}', [App\Http\Controllers\API\TMAController::class, 'filterAll']);
    Route::get('/tma/{id}', [App\Http\Controllers\API\TMAController::class, 'index']);
    Route::get('/tma/yearly/{id}/{year}/{type}', [App\Http\Controllers\API\TMAController::class, 'getYearly']);
    Route::post('/tma', [App\Http\Controllers\API\TMAController::class, 'store']);
    Route::get('/tma/detail/{id}', [App\Http\Controllers\API\TMAController::class, 'detail']);
    Route::post('/tma/delete/{id}', [App\Http\Controllers\API\TMAController::class, 'delete']);
    Route::post('/tma/batch', [App\Http\Controllers\API\TMAController::class, 'batch']);


    //Klimatologi
    Route::get('/klimatologi/{id}/{startDate}/{endDate}', [App\Http\Controllers\API\KlimatologiController::class, 'filter']);
    Route::get('/klimatologi/full/{id}/{startDate}/{endDate}', [App\Http\Controllers\API\KlimatologiController::class, 'filterAll']);
    Route::get('/klimatologi/{id}', [App\Http\Controllers\API\KlimatologiController::class, 'index']);
    Route::post('/klimatologi', [App\Http\Controllers\API\KlimatologiController::class, 'store']);
    Route::get('/klimatologi/detail/{id}', [App\Http\Controllers\API\KlimatologiController::class, 'detail']);
    Route::post('/klimatologi/delete/{id}', [App\Http\Controllers\API\KlimatologiController::class, 'delete']);
    Route::post('/klimatologi/batch', [App\Http\Controllers\API\KlimatologiController::class, 'batch']);
});
