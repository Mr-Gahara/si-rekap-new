<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Revisi_presensi;
use Illuminate\Support\Facades\Storage;
use PDF;

class RevisiPresensiController extends Controller
{
    public function DashboardRevisiPresensi()
    {
        try {
            // Ambil data dari tabel revisi_presensi dengan data terkait
            $revisi = DB::table('revisi_presensi')
                ->join('mahasiswa', 'mahasiswa.nim', '=', 'mahasiswa.nim')
                ->join('matkul', 'matkul.id_matkul', '=', 'matkul.id_matkul')
                ->join('presensi', 'revisi_presensi.id_presensi', '=', 'presensi.id_presensi')
                ->select(
                    'mahasiswa.nim',
                    'mahasiswa.nama as Nama_mahasiswa',
                    'matkul.nama_matkul as Mata_kuliah',
                    'presensi.status as keterangan'
                )
                ->get();

            return response()->json([
                'status' => 200,
                'RevisiPresensi' => $revisi
            ], 200);

        } catch (\Throwable $th) {
            $statusCode = is_int($th->getCode()) && $th->getCode() >= 100 && $th->getCode() <= 599 ? $th->getCode() : 500;

            return response()->json([
                "error" => $th->getMessage()
            ], $statusCode);
        }
    }

    public function uploadRevisiPresensi(Request $request)
    {
        $request->validate([
            'file_path' => 'required|mimes:pdf|max:3096', // Validasi untuk PDF dan ukuran maksimal 3MB
            'id_presensi' => 'required', // Validasi untuk id_presensi
        ]);

        try {
            // Simpan file yang diunggah
            $path = $request->file('file_path');
            $pathnm;
            if (!$path) {
                $path = "Tidak Ada File yang Diunggah";   
            } else {
                $pathnm = $path->getClientOriginalName();
                $path->storeAs($pathnm);
            };
            // Simpan path file dan data lain yang diperlukan ke dalam database
            Revisi_presensi::create([
                'id_revisi_presensi' => $request->input('id_revisi_presensi'),
                'id_presensi' => $request->input('id_presensi'), // Menyimpan id_presensi
                'tanggal_revisi' => $request->input('tanggal_revisi'),
                'status' => $request->input('status'),
                'bukti_revisi' => $request->input('bukti_revisi'),
                'revisi' => $request->input('revisi'),
                'file_path' => $pathnm, //$path
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'File berhasil diunggah',
                'file_path' => $pathnm,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], 500);
        }
    }

    public function generatePdf()
    {
        try {
            // Ambil data dari tabel revisi_presensi beserta data terkait
            $data = Revisi_presensi::with('presensi')->get();

            // Generate PDF dari view yang sudah dibuat
            $pdf = PDF::loadView('pdf_view', compact('data'));

            // Tentukan nama file PDF yang akan disimpan
            $fileName = 'revisi_presensi_' . time() . '.pdf';

            // Simpan file PDF ke storage sementara dan ambil kontennya
            $tempFilePath = storage_path('app/temp/' . $fileName);
            Storage::put('temp/' . $fileName, $pdf->output());

            // Baca isi file PDF dan simpan ke database sebagai binary atau base64
            $pdfContent = file_get_contents($tempFilePath);
            $pdfBase64 = base64_encode($pdfContent);

            // Simpan konten PDF yang di-encode ke dalam kolom file_path di database
            $revisiPresensi = new Revisi_presensi();
            $revisiPresensi->file_path = $pdfBase64;
            $revisiPresensi->save();

            // Hapus file sementara setelah dibaca
            unlink($tempFilePath);

            return response()->json([
                'status' => 200,
                'message' => 'PDF berhasil dibuat dan disimpan ke database',
                'pdf_data' => $pdfBase64,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], 500);
        }
    }

    // Metode untuk mengubah status kehadiran
    public function updateStatusKehadiran(Request $request, $id_presensi)
    {
        $request->validate([
            'status' => 'required|string|max:255', // Validasi status kehadiran
        ]);

        try {
            // Update status kehadiran di tabel presensi
            DB::table('presensi')
                ->where('id_presensi', $id_presensi)
                ->update(['status' => $request->input('status')]);

            return response()->json([
                'status' => 200,
                'message' => 'Status kehadiran berhasil diperbarui',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], 500);
        }
    }

    // Metode untuk menambahkan deskripsi pada revisi presensi
    public function addDeskripsi(Request $request, $id_revisi)
    {
        $request->validate([
            'deskripsi' => 'required|string|max:1000', // Validasi deskripsi
        ]);

        try {
            // Update deskripsi di tabel revisi_presensi
            DB::table('revisi_presensi')
                ->where('revisi', $id_revisi)
                ->update(['revisi' => $request->input('deskripsi')]);

            return response()->json([
                'status' => 200,
                'message' => 'Deskripsi berhasil ditambahkan',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], 500);
        }
    }
}
