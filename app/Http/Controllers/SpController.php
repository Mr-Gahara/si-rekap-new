<?php

namespace App\Http\Controllers;
use App\Models\Sp;
use App\Models\Mahasiswa;

class SpController extends Controller
{
    public function Dashboardsp()
    {
        try {
            // Menghitung jumlah SP1, SP2, SP3, dan DO
            $SuratPeringatan1 = sp::where('jenis_sp', 1)->count();
            $SuratPeringatan2 = sp::where('jenis_sp', 2)->count();
            $SuratPeringatan3 = sp::where('jenis_sp', 3)->count();
            $Do = Mahasiswa::where('ket_status', 'DO')->count(); // contoh status DO

            
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
                'summary' => [
                    'SP1' => $SuratPeringatan1,
                    'SP2' => $SuratPeringatan2,
                    'SP3' => $SuratPeringatan3,
                    'DO' => $Do
                ],
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage(),
            ], 500);
        }
    }

}