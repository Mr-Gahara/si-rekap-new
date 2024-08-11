<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Extend this class
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable // Extend Authenticatable, not Model
{
    use HasApiTokens, Notifiable, HasFactory;

    // Specify the table name if different from 'users'
    protected $table = 'users';

    // Define the primary key column
    protected $primaryKey = 'id';

    // Set primary key to auto-increment
    public $incrementing = true;

    // Define the key type
    protected $keyType = 'int';

    // Fillable attributes
    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'created_at',
    ];

    // Hidden attributes
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casts attributes to native types
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Define relationships
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
        return $this->hasOne(Admin::class, 'user_id', 'id');
    }
}
