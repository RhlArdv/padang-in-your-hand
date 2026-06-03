<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatKunjungan extends Model
{
    protected $table = 'riwayat_kunjungan';
    protected $primaryKey = 'id_riwayat';

    // Tidak pakai updated_at karena riwayat tidak diupdate, hanya dibuat
    public $timestamps = false;
    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $fillable = [
        'id_user',
        'id_lokasi',
        'status',
        'latitude_arrived',
        'longitude_arrived',
        'jarak_tempuh',
        'mulai_navigasi',
        'waktu_tiba',
        'dikunjungi_pada',
    ];

    protected $casts = [
        'latitude_arrived' => 'float',
        'longitude_arrived' => 'float',
        'jarak_tempuh' => 'float',
        'mulai_navigasi' => 'datetime',
        'waktu_tiba' => 'datetime',
        'dikunjungi_pada' => 'datetime',
    ];

    // -------------------------------------------------------
    // Helper: tandai user sudah tiba
    // dipanggil saat GPS user masuk radius 100m dari lokasi
    // -------------------------------------------------------
    public function tandaiTiba(float $latUser, float $lngUser): void
    {
        $this->update([
            'status' => 'arrived',
            'latitude_arrived' => $latUser,
            'longitude_arrived' => $lngUser,
            'waktu_tiba' => now(),
        ]);

        // Update jumlah kunjungan di tabel lokasi
        $this->lokasi()->increment('jumlah_kunjungan');
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