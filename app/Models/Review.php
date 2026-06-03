<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table      = 'review';
    protected $primaryKey = 'id_review';

    protected $fillable = [
        'id_lokasi',
        'id_user',
        'rating',
        'komentar',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------
    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}