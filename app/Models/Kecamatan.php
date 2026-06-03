<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table      = 'kecamatan';
    protected $primaryKey = 'id_kecamatan';

    protected $fillable = [
        'nama_kecamatan',
    ];

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    // Kelurahan yang ada di kecamatan ini
    public function kelurahans()
    {
        return $this->hasMany(Kelurahan::class, 'id_kecamatan', 'id_kecamatan');
    }

    // Semua lokasi di kecamatan ini
    public function lokasis()
    {
        return $this->hasMany(Lokasi::class, 'id_kecamatan', 'id_kecamatan');
    }
}