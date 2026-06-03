<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $table      = 'pengaduan';
    protected $primaryKey = 'id_pengaduan';

    protected $fillable = [
        'id_user',
        'id_lokasi',
        'jenis_pengaduan',
        'isi_pengaduan',
        'foto_bukti',
        'status',
        'catatan_admin',
    ];

    protected $appends = [
        'foto_bukti_url',
    ];

    // URL foto bukti
    public function getFotoBuktiUrlAttribute(): ?string
    {
        return $this->foto_bukti
            ? asset('storage/' . $this->foto_bukti)
            : null;
    }

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
    }
}