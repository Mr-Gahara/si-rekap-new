<?php

namespace App\Http\Controllers;

use App\Models\Sp;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

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
            
            $formattedData = [];

            // Iterasi setiap item dalam koleksi data
            foreach ($data as $item) {
                $formattedData[] = [
                    'NO' => $item->no,
                    'Nama_Mahasiswa' => $item->nama_mahasiswa,
                    'NIM' => $item->nim,
                    'Kelas' => $item->kelas,
                    'Presensi' => $item->ketidakhadiran,
                    'Surat_Peringatan' => $item->surat_peringatan
                ];
            }

            return response()->json([
                'status' => 200,
                'data' => $formattedData, 
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

    public function DetailSp () 
    {
        try {
            // Contoh NIM, Anda bisa menggantinya dengan yang dinamis berdasarkan sesi atau input
            $nim = "2023001";

            // Mengambil data informasi mahasiswa
            $mahasiswa = Mahasiswa::select('nim', 'nama')
                ->where('nim', $nim)
                ->first();

            // Mengambil total ketidakhadiran mahasiswa
            $total_ketidakhadiran = Mahasiswa::join('presensi', 'mahasiswa.id_mahasiswa', '=', 'presensi.id_mahasiswa')
                ->where('mahasiswa.nim', $nim)
                ->sum('presensi.ketidakhadiran');

            // Mengambil data detail surat peringatan (tanpa tanggal pengajuan, surat pernyataan, dan status peringatan)
            $surat_peringatan = Sp::select('sp.jenis_sp as surat_peringatan', 'kls.smt', 'kls.abjad_kls as kelas')
                ->join('mahasiswa as mhs', 'sp.id_mahasiswa', '=', 'mhs.id_mahasiswa')
                ->join('kelas as kls', 'mhs.id_kelas', '=', 'kls.id_kelas')
                ->where('mhs.nim', $nim)
                ->get();

            // Memeriksa apakah data mahasiswa ditemukan
            if (!$mahasiswa) {
                return response()->json(['message' => 'Mahasiswa not found.'], 404);
            }

            // Memeriksa apakah ada catatan surat peringatan
            if ($surat_peringatan->isEmpty()) {
                return response()->json(['message' => 'No records found for surat peringatan.'], 404);
            }

            // Menyusun respons JSON
            return response()->json([
                'status' => 200,
                'info_mahasiswa' => [
                    'nim' => $mahasiswa->nim,
                    'nama' => $mahasiswa->nama,
                    'total_ketidakhadiran' => $total_ketidakhadiran
                ],
                'detail_sp' => $surat_peringatan,
            ], 200);

        } catch (\Exception $e) {
            // Menangani pengecualian dan mengembalikan respons error
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);

        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage(),
            ], 500);
        }
    }
}

// use App\Models\Sp;
// use App\Models\Mahasiswa;

// class SpController extends Controller
// {
//     public function Dashboardsp()
//     {
//         try {
//             // Menghitung jumlah SP1, SP2, SP3, dan DO
//             $SuratPeringatan1 = sp::where('jenis_sp', 1)->count();
//             $SuratPeringatan2 = sp::where('jenis_sp', 2)->count();
//             $SuratPeringatan3 = sp::where('jenis_sp', 3)->count();
//             $Do = Mahasiswa::where('ket_status', 'DO')->count(); // contoh status DO

            
//             $data = Sp::join('mahasiswa', 'sp.id_mahasiswa', '=', 'mahasiswa.id_mahasiswa')
//                 ->join('kelas', 'mahasiswa.id_kelas', '=', 'kelas.id_kelas')
//                 ->join('presensi', 'mahasiswa.id_mahasiswa', '=', 'presensi.id_mahasiswa')
//                 ->select(
//                     'sp.id_sp as no',
//                     'mahasiswa.nama as nama_mahasiswa',
//                     'mahasiswa.nim',
//                     'kelas.smt',
//                     'kelas.abjad_kls as kelas',
//                     'presensi.ketidakhadiran',
//                     'mahasiswa.ket_status as surat_peringatan'
//                 )
//                 ->get();

//             return response()->json([
//                 'status' => 200,
//                 'data' => $data,
//                 'summary' => [
//                     'SP1' => $SuratPeringatan1,
//                     'SP2' => $SuratPeringatan2,
//                     'SP3' => $SuratPeringatan3,
//                     'DO' => $Do
//                 ],
//             ], 200);

//         } catch (\Throwable $th) {
//             return response()->json([
//                 "error" => $th->getMessage(),
//             ], 500);
//         }
//     }

// }