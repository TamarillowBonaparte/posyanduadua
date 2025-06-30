<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AnakController;
use App\Http\Controllers\Api\PerkembanganAnakApiController;
use App\Http\Controllers\Api\JadwalApiController;
use App\Http\Controllers\Api\ImunisasiApiController;
use App\Http\Controllers\Api\VitaminApiController;
use App\Http\Controllers\Api\StuntingApiController;
use App\Http\Controllers\Api\ArtikelApiController;
use Carbon\Carbon;

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

// Rute publik
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Endpoint untuk testing (tidak memerlukan autentikasi)
if (app()->environment('local', 'development')) {
    Route::post('/test-jadwal-complete/{id}', [ImunisasiApiController::class, 'completeJadwal']);
    Route::post('/test-vitamin-complete/{id}', [VitaminApiController::class, 'completeJadwal']);
    Route::post('/test-check-imunisasi-status/{id}', [JadwalApiController::class, 'checkImunisasiStatus']);
    Route::post('/test-check-vitamin-status/{id}', [JadwalApiController::class, 'checkVitaminStatus']);
    Route::post('/test-update-imunisasi-status/{id}', [JadwalApiController::class, 'updateImunisasiStatus']);
    Route::post('/test-update-vitamin-status/{id}', [JadwalApiController::class, 'updateVitaminStatus']);
    Route::post('/test-update-pemeriksaan-status/{id}', [JadwalApiController::class, 'updatePemeriksaanStatus']);
}

// Rute yang memerlukan autentikasi
Route::middleware('auth:sanctum')->group(function () {
    // Info pengguna
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user/{id}', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // === Endpoint umum (web + mobile) ===
    
    // Endpoint Anak CRUD
    Route::apiResource('anak', AnakController::class);
    Route::get('/anak/pengguna/nik/{nik}', [AnakController::class, 'findByPenggunaNik']);
    Route::post('/anak/link-to-parent', [AnakController::class, 'linkToParent']);
    
    // Endpoint Perkembangan Anak
    Route::get('/perkembangan/anak/{anak_id}', [PerkembanganAnakApiController::class, 'getByAnakId']);
    Route::get('/perkembangan/{id}', [PerkembanganAnakApiController::class, 'show']);
    Route::post('/perkembangan', [PerkembanganAnakApiController::class, 'store']);
    Route::put('/perkembangan/{id}', [PerkembanganAnakApiController::class, 'update']);
    Route::delete('/perkembangan/{id}', [PerkembanganAnakApiController::class, 'destroy']);
    
    // Endpoint Jadwal (Schedules)
    Route::prefix('jadwal')->group(function () {
        Route::get('/', [JadwalApiController::class, 'index']);
        Route::get('/upcoming', [JadwalApiController::class, 'upcoming']);
        Route::get('/upcoming/anak/{anakId}', [JadwalApiController::class, 'upcomingForChild']);
        Route::get('/imunisasi/anak/{anakId}', [JadwalApiController::class, 'imunisasiForChild']);
        Route::get('/vitamin/anak/{anakId}', [JadwalApiController::class, 'vitaminForChild']);
        Route::get('/imunisasi/age-ranges', [JadwalApiController::class, 'imunisasiAgeRanges']);
        Route::get('/vitamin/age-ranges', [JadwalApiController::class, 'vitaminAgeRanges']);
        Route::get('/pemeriksaan', [JadwalApiController::class, 'pemeriksaan']);
        Route::get('/imunisasi', [JadwalApiController::class, 'imunisasi']);
        Route::get('/vitamin', [JadwalApiController::class, 'vitamin']);
        Route::get('/riwayat/anak/{anakId}', [JadwalApiController::class, 'riwayatAnak']);
        Route::get('/nearest/{anakId}', [JadwalApiController::class, 'nearestSchedule']);
    });
    Route::get('/jenis-imunisasi', [JadwalApiController::class, 'jenisImunisasi']);
    Route::get('/jenis-vitamin', [JadwalApiController::class, 'jenisVitamin']);
    
    // Endpoint Imunisasi
    Route::prefix('imunisasi')->group(function () {
        Route::get('/', [ImunisasiApiController::class, 'index']);
        Route::get('/{id}', [ImunisasiApiController::class, 'show']);
        Route::put('/{id}', [ImunisasiApiController::class, 'update']);
        Route::get('/anak/{anakId}', [ImunisasiApiController::class, 'getByAnakId']);
        Route::get('/jadwal/status', [ImunisasiApiController::class, 'checkImplementationStatus']);
        Route::get('/jadwal/eligible-children/{jadwalId}', [ImunisasiApiController::class, 'getEligibleChildren']);
        Route::post('/jadwal/confirm/{id}', [ImunisasiApiController::class, 'confirmImplementation']);
        Route::post('/jadwal/complete/{id}', [ImunisasiApiController::class, 'completeJadwal']);
        Route::get('/jadwal/anak/{anakId}', [ImunisasiApiController::class, 'getJadwalForAnak']);
        Route::post('/create-from-jadwal', [ImunisasiApiController::class, 'createFromJadwal']);
    });
    
    // Endpoint Vitamin
    Route::prefix('vitamin')->group(function () {
        Route::get('/', [VitaminApiController::class, 'index']);
        Route::get('/{id}', [VitaminApiController::class, 'show']);
        Route::put('/{id}', [VitaminApiController::class, 'update']);
        Route::get('/anak/{anakId}', [VitaminApiController::class, 'getByAnakId']);
        Route::get('/jadwal/status', [VitaminApiController::class, 'checkImplementationStatus']);
        Route::get('/jadwal/eligible-children/{jadwalId}', [VitaminApiController::class, 'getEligibleChildren']);
        Route::post('/jadwal/confirm/{id}', [VitaminApiController::class, 'confirmImplementation']);
        Route::post('/jadwal/complete/{id}', [VitaminApiController::class, 'completeJadwal']);
        Route::get('/jadwal/anak/{anakId}', [VitaminApiController::class, 'getJadwalForAnak']);
        Route::post('/create-from-jadwal', [VitaminApiController::class, 'createFromJadwal']);
    });
    
    // Endpoint Stunting
    Route::prefix('stunting')->group(function () {
        Route::get('/anak/{anak_id}', [StuntingApiController::class, 'getByAnakId']);
        Route::get('/{id}', [StuntingApiController::class, 'show']);
        Route::post('/', [StuntingApiController::class, 'store']);
        Route::put('/{id}', [StuntingApiController::class, 'update']);
        Route::delete('/{id}', [StuntingApiController::class, 'destroy']);
        Route::post('/calculate', [StuntingApiController::class, 'calculateStatus']);
    });
    
    // Endpoint Artikel
    Route::prefix('artikel')->group(function () {
        Route::get('/', [ArtikelApiController::class, 'index']);
        Route::get('/latest', [ArtikelApiController::class, 'latest']);
        Route::get('/{id}', [ArtikelApiController::class, 'show']);
        Route::post('/', [ArtikelApiController::class, 'store']);
        Route::post('/{id}', [ArtikelApiController::class, 'update']);
        Route::delete('/{id}', [ArtikelApiController::class, 'destroy']);
    });
    
    // === Endpoint khusus untuk aplikasi mobile ===
    Route::prefix('mobile')->group(function () {
        // Anak endpoints untuk mobile
        Route::get('/anak/pengguna/{pengguna_id}', [AnakController::class, 'getAnakByPenggunaId']);
        Route::get('/anak/{id}', [AnakController::class, 'show']);
        
        // Perkembangan anak endpoints untuk mobile
        Route::get('/perkembangan/anak/{anak_id}', [PerkembanganAnakApiController::class, 'getByAnakId']);
        Route::get('/perkembangan/{id}', [PerkembanganAnakApiController::class, 'show']);
        
        // Jadwal endpoints untuk mobile
        Route::get('/jadwal/upcoming/anak/{anakId}', [JadwalApiController::class, 'upcomingForChild']);
        Route::get('/jadwal/nearest/{anakId}', [JadwalApiController::class, 'nearestSchedule']);
        
        // Imunisasi dan vitamin endpoints untuk mobile
        Route::get('/imunisasi/anak/{anakId}', [ImunisasiApiController::class, 'getByAnakId']);
        Route::get('/vitamin/anak/{anakId}', [VitaminApiController::class, 'getByAnakId']);
        
        // Endpoint stunting jika ada
        if (class_exists(StuntingApiController::class)) {
            Route::get('/stunting/anak/{anak_id}', [StuntingApiController::class, 'getByAnakId']);
            Route::get('/stunting/{id}', [StuntingApiController::class, 'show']);
            Route::post('/stunting/calculate', [StuntingApiController::class, 'calculateStatus']);
        }
        
        // Endpoint artikel untuk mobile
        Route::get('/artikel', [ArtikelApiController::class, 'index']);
        Route::get('/artikel/latest', [ArtikelApiController::class, 'latest']);
        Route::get('/artikel/{id}', [ArtikelApiController::class, 'show']);
    });
});

// Endpoint untuk debugging (Hanya untuk development)
if (app()->environment('local')) {
    Route::get('/debug/auth-check', function (Request $request) {
        return response()->json([
            'authenticated' => auth()->check(),
            'user' => auth()->user(),
            'token_valid' => $request->bearerToken() ? true : false,
        ]);
    })->middleware('auth:sanctum');
    
    // Debugging routes lainnya...
}
