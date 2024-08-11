<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpController;
use App\Http\Controllers\CicilKompenController;
use App\Http\Controllers\RevisiPresensiController;
use App\Http\Controllers\KompenMahasiswaController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\MahasiswaController;

Route::get('/Dashboard-sp',[spController::class, 'Dashboardsp']); 
// (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

//Cicil Kompen
Route::get('/Dashboard-cicil',[CicilKompenController::class, 'DashboardCicil']);
Route::post("/Tambah-cicil", [CicilKompenController::class, 'tambahCicilKompen']);
Route::patch('/Update-cicil', [CicilKompenController::class, 'updateCicilKompen']);
Route::delete('/Delete-cicil', [CicilKompenController::class, 'deleteCicilKompen']);

Route::get('/Dashboard-Kompen',[KompenMahasiswaController::class,'Dashboard_Kompen']);

Route::get('/Profil-Kompen', [KompenMahasiswaController::class,'Profil_Kompen']);

Route::get('/Revisi-Presensi', [RevisiPresensiController::class, 'DashboardRevisiPresensi']);

Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::post('/register', [RegisteredUserController::class, 'store']);

Route::get('/Dashboard-Mahasiswa', [MahasiswaController::class, 'DashboardMahasiswa']);