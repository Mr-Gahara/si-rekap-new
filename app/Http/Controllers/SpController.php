<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Sp;
use App\Models\Kelas;
use App\Models\Presensi;

class SpController extends Controller
{
    public function Dashboardsp()
    {
        try {
            $data = Sp::join('mahasiswa', 'sp.id_mahasiswa', '=', 'mahasiswa.id_mahasiswa')
                ->join('kelas', 'mahasiswa.id_kelas', '=', 'kelas.id_kelas')
                ->join('presensi', 'mahasiswa.id_mahasiswa', '=', 'presensi.id_mahasiswa')
                ->select(
                    'sp.id_sp as no',
                    'mahasiswa.nama as nama_mahasiswa',
                    'mahasiswa.nim',
                    'kelas.smt',
                    'kelas.abjad_kls as kelas',
                    'presensi.ketidakhadiran',
                    'mahasiswa.ket_status as surat_peringatan'
                )
                ->get();

            return response()->json([
                'status' => 200,
                'data' => $data,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage(),
            ], 500);
        }
    }
}

