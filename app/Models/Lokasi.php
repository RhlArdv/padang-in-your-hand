<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $table      = 'lokasi';
    protected $primaryKey = 'id_lokasi';

    protected $fillable = [
        'nama_tempat',
        'id_kategori',
        'alamat',
        'id_kecamatan',
        'id_kelurahan',
        'latitude',
        'longitude',
        'deskripsi',
        'jam_operasional',
        'kontak',
        'website',
        'status_verifikasi',
        'rating_avg',
        'jumlah_review',
        'jumlah_kunjungan',
        'created_by',
    ];

    protected $casts = [
        'latitude'         => 'float',
        'longitude'        => 'float',
        'rating_avg'       => 'float',
        'jumlah_review'    => 'integer',
        'jumlah_kunjungan' => 'integer',
    ];

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('map_lokasi_disetujui');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('map_lokasi_disetujui');
        });
    }

    // -------------------------------------------------------
    // Scope: filter hanya lokasi yang sudah disetujui
    // Dipakai di API publik
    // -------------------------------------------------------
    public function scopeDisetujui($query)
    {
        return $query->where('status_verifikasi', 'disetujui');
    }

    // Scope: filter berdasarkan kategori
    public function scopeKategori($query, $idKategori)
    {
        return $query->where('id_kategori', $idKategori);
    }

    // -------------------------------------------------------
    // Helper: hitung jarak dari koordinat user (dalam meter)
    // Pakai formula Haversine
    // -------------------------------------------------------
    public function hitungJarak(float $latUser, float $lngUser): float
    {
        $earthRadius = 6371000; // meter

        $latDiff = deg2rad($this->latitude - $latUser);
        $lngDiff = deg2rad($this->longitude - $lngUser);

        $a = sin($latDiff / 2) ** 2
           + cos(deg2rad($latUser))
           * cos(deg2rad($this->latitude))
           * sin($lngDiff / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2); // hasil dalam meter
    }

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    // Kategori lokasi ini
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    // Kecamatan lokasi ini
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    // Kelurahan lokasi ini
    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'id_kelurahan', 'id_kelurahan');
    }

    // User yang menambahkan lokasi ini (kontributor)
    public function kontributor()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Foto-foto lokasi ini
    public function fotos()
    {
        return $this->hasMany(FotoLokasi::class, 'id_lokasi', 'id_lokasi');
    }

    // Foto utama (foto pertama yang diupload)
    public function fotoUtama()
    {
        return $this->hasOne(FotoLokasi::class, 'id_lokasi', 'id_lokasi')->oldestOfMany('id_foto');
    }

    // Semua review lokasi ini
    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_lokasi', 'id_lokasi');
    }

    // User yang memfavoritkan lokasi ini
    public function difavoritOleh()
    {
        return $this->belongsToMany(User::class, 'favorit', 'id_lokasi', 'id_user')
                    ->withTimestamps();
    }

    // Riwayat kunjungan ke lokasi ini
    public function riwayatKunjungans()
    {
        return $this->hasMany(RiwayatKunjungan::class, 'id_lokasi', 'id_lokasi');
    }

    // Log approval lokasi ini
    public function approvalLogs()
    {
        return $this->hasMany(ApprovalLog::class, 'id_lokasi', 'id_lokasi');
    }

    // Pengaduan terkait lokasi ini
    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class, 'id_lokasi', 'id_lokasi');
    }

    // Event yang berlokasi di sini
    public function events()
    {
        return $this->hasMany(Event::class, 'id_lokasi', 'id_lokasi');
    }
}