<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\Kompen_mahasiswa;
use App\Models\Mahasiswa;
use App\Models\Presensi;


class KompenMahasiswaController extends Controller
{
    
    public function Dashboard_Kompen(Request $request)
    {
        try {

            $userId = $request->user()->id;
            
            // Mengambil data dari tabel kompen_mahasiswa
            $kompen = Kompen_mahasiswa::with('matkul')
            ->where('id_mahasiswa', $userId)
            ->get();
            
            
            // Menghitung total kompen
            $totalKompensasi = $kompen->sum('jumlah_kompen');

            $data = $kompen->map(function ($kompen) {
                return [
                    'mata_kuliah' => $kompen->matkul->nama_matkul,
                    'jam_kompen' => $kompen->jumlah_kompen,
                    'tanggal' => $kompen->tgl_alpha,
                ];
            });

            // Mengembalikan response dalam format JSON
            return response()->json([
                'status' => 200,
                'data' => $data,
                'Total Kompen' => $totalKompensasi . ' Jam'
            ], 200);
        } catch (\Throwable $th) {
            // Default kode status HTTP untuk kesalahan server
            $statusCode = is_int($th->getCode()) && $th->getCode() >= 100 && $th->getCode() <= 599 ? $th->getCode() : 500;

            return response()->json([
                "error" => $th->getMessage(),
            ], $statusCode);
        }
    } 

    public function Profil_Kompen(Request $request)
    {
        try {
            $user = $request->user();

            $id_mahasiswa = $user->id_mahasiswa;
    
            $mahasiswa = Mahasiswa::where('id_mahasiswa', $id_mahasiswa)->first();
    
            $jumlah_kompen = kompen_mahasiswa::where('id_mahasiswa', $id_mahasiswa)->sum('jumlah_kompen');
    
            $jumlah_absensi_izin = Presensi::where('id_mahasiswa', $id_mahasiswa)->where('status', 'I')->count();
            $jumlah_absensi_sakit = Presensi::where('id_mahasiswa', $id_mahasiswa)->where('status', 'S')->count();
            $jumlah_absensi_alpha = Presensi::where('id_mahasiswa', $id_mahasiswa)->where('status', 'A')->count();
    
            return response()->json([
                'status' => 200,
                'foto' => $mahasiswa -> foto,
                'nama_mahasiswa' => $mahasiswa -> nama,
                'status_mahasiswa' => $mahasiswa->status,
                'jumlah_kompensasi' => $jumlah_kompen,
                'jumlah_absensi_izin' => $jumlah_absensi_izin,
                'jumlah_absensi_sakit' => $jumlah_absensi_sakit,
                'jumlah_absensi_alpha' => $jumlah_absensi_alpha,
            ], 200);

        } catch (\Throwable $th) {
            // Default kode status HTTP untuk kesalahan server
            $statusCode = is_int($th->getCode()) && $th->getCode() >= 100 && $th->getCode() <= 599 ? $th->getCode() : 500;

            return response()->json([
                "error" => $th->getMessage(),
            ], $statusCode);
        }
    }
}
