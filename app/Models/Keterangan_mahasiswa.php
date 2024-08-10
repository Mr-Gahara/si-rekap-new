<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Presensi;

class Keterangan_mahasiswa extends Model
{
    use HasFactory;
     
    // nonaktifkan timestamp karena di tabel tidak memakai kolom 'create_at' & 'update_at'
    public $timestamps = false;

    // menghubungkan nama tabel dengan model
    protected $table = 'ket_mahasiswa';
    
    // menentukan primary key pada kolom tabel
    protected $primaryKey = 'id_presensi';

    // menentukan apakah primary key auto increment pada tabel
    public $incrementing = false;

    // menentukan apakah primary key
    protected $keyType = 'int';

    // menentukan kolom kolom lain pada tabel (yang bukan primary key)
    protected $fillable = [
        'status_confirm',
        'surat_bukti',
        'deskripsi',
    ];

    public function presensi()
    {
        return $this->belongsTo(Presensi::class,'id_presensi','id_presensi');
    }
}
