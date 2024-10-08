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
use App\Http\Controllers\BeritaAcaraController;

Route::get('/Dashboard-sp',[spController::class, 'Dashboardsp']); 
Route::get('/Detail-sp',[spController::class, 'DetailSp']); 
// (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

//Cicil Kompen
Route::get('/Dashboard-cicil',[CicilKompenController::class, 'DashboardCicil']);
Route::post("/Tambah-cicil", [CicilKompenController::class, 'tambahCicilKompen']);
Route::patch('/Update-cicil', [CicilKompenController::class, 'updateCicilKompen']);
Route::delete('/Delete-cicil', [CicilKompenController::class, 'deleteCicilKompen']);
Route::get('/Laporan-cicil',[CicilKompenController::class, 'LaporanCicil']);

Route::get('/Dashboard-Kompen',[KompenMahasiswaController::class,'Dashboard_Kompen']);
Route::get('/Dashboard-Pendataan-Kompen',[KompenMahasiswaController::class,'Dashboard_Pendataan_Kompen']);

Route::get('/Profil-Kompen/{id}', [KompenMahasiswaController::class,'Profil_Kompen']);

Route::get('/Revisi-Presensi', [RevisiPresensiController::class, 'DashboardRevisiPresensi']);

Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::post('/register', [RegisteredUserController::class, 'store']);

Route::get('/Dashboard-Mahasiswa/{id}', [MahasiswaController::class, 'DashboardMahasiswa']);

Route::get('/Dashboard-Revisi-Presensi', [RevisiPresensiController::class, 'DashboardRevisiPresensi'])->name('revisi-presensi.dashboard');
Route::post('/upload-revisi-presensi', [RevisiPresensiController::class, 'uploadRevisiPresensi']);
Route::post('/update-status-kehadiran/{id_presensi}', [RevisiPresensiController::class, 'updateStatusKehadiran']);
Route::post('/add-deskripsi/{id_revisi}', [RevisiPresensiController::class, 'addDeskripsi']);

Route::get('/berita-acara-mahasiswa/{id}', [BeritaAcaraController::class, 'getMhsData']);