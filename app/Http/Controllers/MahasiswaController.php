<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\Mahasiswa;
use App\Models\Presensi;


class MahasiswaController extends Controller
{
    public function DashboardMahasiswa(String $id, Request $request) {
        try {

            // Retrieve the id_mahasiswa from the request parameters
            $id_mahasiswa = $id;

            // Check if id_mahasiswa is provided
            if (!$id_mahasiswa) {
                return response()->json([
                    "error" => "id_mahasiswa parameter is required",
                ], 400); // Bad Request
            }

            // Perform the database queries using $id_mahasiswa
            $jumlahKehadiran = DB::table('presensi')
                ->where('id_mahasiswa', $id_mahasiswa)
                ->sum('kehadiran');
        
            $sakit = DB::table('presensi')
                ->where('id_mahasiswa', $id_mahasiswa)
                ->where('status', 'S')
                ->count();
        
            $izin = DB::table('presensi')
                ->where('id_mahasiswa', $id_mahasiswa)
                ->where('status', 'I')
                ->count();
        
            $alpha = DB::table('presensi')
                ->where('id_mahasiswa', $id_mahasiswa)
                ->where('status', 'A')
                ->count();
        
            $jumlahKompensasi = DB::table('kompen_mahasiswa')
                ->where('id_mahasiswa', $id_mahasiswa)
                ->sum('jumlah_kompen');
        
            return response()->json([
                'status' => 200,
                'jumlah_kehadiran' => $jumlahKehadiran,
                'sakit' => $sakit,
                'izin' => $izin,
                'alpha' => $alpha,
                'jumlah_kompensasi' => $jumlahKompensasi,
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
