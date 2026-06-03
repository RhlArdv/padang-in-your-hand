<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    protected $table      = 'kelurahan';
    protected $primaryKey = 'id_kelurahan';

    protected $fillable = [
        'id_kecamatan',
        'nama_kelurahan',
    ];

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    // Kecamatan induk kelurahan ini
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    // Semua lokasi di kelurahan ini
    public function lokasis()
    {
        return $this->hasMany(Lokasi::class, 'id_kelurahan', 'id_kelurahan');
    }
}