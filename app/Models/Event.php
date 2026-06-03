<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table      = 'event';
    protected $primaryKey = 'id_event';

    protected $fillable = [
        'nama_event',
        'id_lokasi',
        'lokasi_event',
        'jenis_event',
        'deskripsi',
        'banner',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'created_by',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    protected $appends = [
        'banner_url',
    ];

    // URL banner event
    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner
            ? asset('storage/' . $this->banner)
            : null;
    }

    // Scope: hanya event yang aktif dan belum selesai
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif')
                     ->where('tanggal_selesai', '>=', now());
    }

    // Scope: event mendatang
    public function scopeMendatang($query)
    {
        return $query->where('status', 'aktif')
                     ->where('tanggal_mulai', '>', now());
    }

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    // Lokasi tempat event berlangsung (opsional)
    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
    }

    // Admin/user yang membuat event
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}