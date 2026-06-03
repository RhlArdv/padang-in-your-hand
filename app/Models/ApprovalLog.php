<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalLog extends Model
{
    protected $table      = 'approval_log';
    protected $primaryKey = 'id_log';

    // Hanya ada created_at, tidak ada updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'id_lokasi',
        'id_admin',
        'status',
        'catatan',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------
    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
    }

    // Admin/operator yang melakukan approval
    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin');
    }
}