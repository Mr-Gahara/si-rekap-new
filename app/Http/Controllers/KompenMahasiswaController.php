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

            $data = DB::table("kompen_mahasiswa")
            ->join("matkul", "kompen_mahasiswa.id_matkul", "=", "matkul.id_matkul")
            ->select("matkul.nama_matkul as Mata kuliah","kompen_mahasiswa.tgl_alpha", "kompen_mahasiswa.jumlah_kompen")
            ->get();

            $totalKompensasi = Kompen_mahasiswa::sum("jumlah_kompen");

            // Mengembalikan response dalam format JSON
            return response()->json([
                'status' => 200,
                'data' => $data,
                'Total Kompen' => $totalKompensasi
            ], 200);
        } catch (\Throwable $th) {
            // Default kode status HTTP untuk kesalahan server
            $statusCode = is_int($th->getCode()) && $th->getCode() >= 100 && $th->getCode() <= 599 ? $th->getCode() : 500;

            return response()->json([
                "error" => $th->getMessage(),
            ], $statusCode);
        }
    } 

    public function Dashboard_Pendataan_Kompen()
    {
        try {

            $data = DB::table("kompen_mahasiswa")
            ->join("matkul", "kompen_mahasiswa.id_matkul", "=", "matkul.id_matkul")
            ->join("mahasiswa", "kompen_mahasiswa.id_mahasiswa", "=", "mahasiswa.id_mahasiswa")
            ->select(
                "kompen_mahasiswa.id_kompen as ID_kompen",
                "mahasiswa.nama as Nama_Mahasiswa",
                "matkul.nama_matkul as Mata_kuliah",
                "kompen_mahasiswa.tgl_alpha", 
                "kompen_mahasiswa.jumlah_kompen"
            )
            ->get();
        
            // Mengembalikan response dalam format JSON
            return response()->json([
                'status' => 200,
                'data' => $data,
            ], 200);
        } catch (\Throwable $th) {
            // Default kode status HTTP untuk kesalahan server
            $statusCode = is_int($th->getCode()) && $th->getCode() >= 100 && $th->getCode() <= 599 ? $th->getCode() : 500;

            return response()->json([
                "error" => $th->getMessage(),
            ], $statusCode);
        }
    } 

    public function Profil_Kompen(String $id, Request $request)
    {
        try {

        // Retrieve the id_mahasiswa from the request parameters
        $id_mahasiswa = $id;

        // Check if id_mahasiswa is provided
        if (!$id_mahasiswa) {
            return response()->json([
                "error" => "id_mahasiswa parameter is required",
            ], 400); // Bad Request
        }

        // Join the tables to get the required data
        $data = DB::table('mahasiswa')
            ->join('kompen_mahasiswa', 'mahasiswa.id_mahasiswa', '=', 'kompen_mahasiswa.id_mahasiswa')
            ->leftJoin('presensi as presensi_izin', function ($join) use ($id_mahasiswa) {
                $join->on('mahasiswa.id_mahasiswa', '=', 'presensi_izin.id_mahasiswa')
                     ->where('presensi_izin.status', '=', 'I');
            })
            ->leftJoin('presensi as presensi_sakit', function ($join) use ($id_mahasiswa) {
                $join->on('mahasiswa.id_mahasiswa', '=', 'presensi_sakit.id_mahasiswa')
                     ->where('presensi_sakit.status', '=', 'S');
            })
            ->leftJoin('presensi as presensi_alpha', function ($join) use ($id_mahasiswa) {
                $join->on('mahasiswa.id_mahasiswa', '=', 'presensi_alpha.id_mahasiswa')
                     ->where('presensi_alpha.status', '=', 'A');
            })
            ->select(
                'mahasiswa.nama',
                'mahasiswa.foto',
                'mahasiswa.ket_status as status_mahasiswa',
                DB::raw('SUM(kompen_mahasiswa.jumlah_kompen) as jumlah_kompensasi'),
                DB::raw('COUNT(DISTINCT presensi_izin.id_presensi) as jumlah_absensi_izin'),
                DB::raw('COUNT(DISTINCT presensi_sakit.id_presensi) as jumlah_absensi_sakit'),
                DB::raw('COUNT(DISTINCT presensi_alpha.id_presensi) as jumlah_absensi_alpha')
            )
            ->where('mahasiswa.id_mahasiswa', $id_mahasiswa)
            ->groupBy(
                'mahasiswa.nama',
                'mahasiswa.foto',
                'mahasiswa.ket_status'
            )
            ->first();

        // If no data is found, return an error response
        if (!$data) {
            return response()->json([
                "error" => "No data found for the provided id_mahasiswa",
            ], 404); // Not Found
        }

        // Return the response as JSON
        return response()->json([
            'status' => 200,

            'profil_mahasiswa' => [
                'nama_mahasiswa' =>$data->nama,
                'foto' => $data->foto,
            ],
            'data' => [
                'status_mahasiswa' => $data->status_mahasiswa,
                'jumlah_kompensasi' => $data->jumlah_kompensasi,
                'jumlah_absensi_izin' => $data->jumlah_absensi_izin,
                'jumlah_absensi_sakit' => $data->jumlah_absensi_sakit,
                'jumlah_absensi_alpha' => $data->jumlah_absensi_alpha,
            ],
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
