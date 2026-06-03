<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table      = 'kategori';
    protected $primaryKey = 'id_kategori';

    protected $fillable = [
        'nama_kategori',
        'icon',
    ];

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    // Semua lokasi yang masuk kategori ini
    public function lokasis()
    {
        return $this->hasMany(Lokasi::class, 'id_kategori', 'id_kategori');
    }
}