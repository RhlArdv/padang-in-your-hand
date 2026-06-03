<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoLokasi extends Model
{
    protected $table      = 'foto_lokasi';
    protected $primaryKey = 'id_foto';

    protected $fillable = [
        'id_lokasi',
        'file_foto',
        'caption',
    ];

    protected $appends = [
        'url_foto',
    ];

    // URL lengkap foto (dari storage Laravel)
    public function getUrlFotoAttribute(): string
    {
        return asset('storage/' . $this->file_foto);
    }

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------
    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
    }
}