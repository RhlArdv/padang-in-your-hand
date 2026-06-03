<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorit extends Model
{
    protected $table      = 'favorit';
    protected $primaryKey = 'id_favorit';

    protected $fillable = [
        'id_user',
        'id_lokasi',
    ];

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