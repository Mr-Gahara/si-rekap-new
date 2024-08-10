<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// hubungkan model model yang diperlukan
use app\Models\Dosen;
use app\Models\Mahasiswa;
use app\Models\Admin;

class User extends Model
{
    use HasFactory;

    // menghubungkan nama tabel dengan model
    protected $table = 'users';

    // menentukan primary key pada kolom tabel
    protected $primaryKey = 'id';

    // menentukan apakah primary key auto increment pada tabel
    public $incrementing = true;

    // menentukan tipe data primary key
    protected $keyType = 'int';

    // menentukan kolom kolom lain pada tabel (yang bukan primary key)
    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'created_at',
    ];

    // menentukan relasi tabel
    public function dosen()
    {
        return $this->hasOne(Dosen::class, 'user_id', 'id');
    }

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'user_id', 'id');
    }

    public function admin()
    {
        return $this ->hasOne(Admin::class, 'user_id', 'id');
    }
}
