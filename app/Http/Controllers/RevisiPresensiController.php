<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Revisi_presensi;

class RevisiPresensiController extends Controller
{
    public function DashboardRevisiPresensi()
    {
        try {
            // Fetch data from the Revisi_Presensi table with related data
            $revisi = DB::table('revisi_presensi')
                ->join('mahasiswa', 'mahasiswa.nim', '=', 'mahasiswa.nim') // Correct the join condition
                ->join('matkul', 'matkul.id_matkul', '=', 'matkul.id_matkul') // Check if id_matkul exists
                ->join('presensi', 'revisi_presensi.id_presensi', '=', 'presensi.id_presensi')
                ->select(
                    'mahasiswa.nim',
                    'mahasiswa.nama as Nama_mahasiswa', // Ensure this column exists in mahasiswas table
                    'matkul.nama_matkul as Mata_kuliah',
                    'presensi.status as keterangan'
                )
                ->get();

            // Return response as JSON
            return response()->json([
                'status' => 200,
                'RevisiPresensi' => $revisi
            ], 200);
        } catch (\Throwable $th) {
            // Handle exceptions and return error message
            $statusCode = is_int($th->getCode()) && $th->getCode() >= 100 && $th->getCode() <= 599 ? $th->getCode() : 500;

            return response()->json([
                "error" => $th->getMessage()
            ], $statusCode);
        }
    }
}
