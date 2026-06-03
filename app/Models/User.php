<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'no_hp',
        'foto',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role'              => 'string',
        ];
    }

    // -------------------------------------------------------
    // Helper: cek role (dipakai di middleware & blade)
    // -------------------------------------------------------
    public function isSuperAdmin(): bool { return $this->role === 'super_admin'; }
    public function isAdmin(): bool      { return $this->role === 'admin'; }
    public function isOperator(): bool   { return $this->role === 'operator'; }
    public function isKontributor(): bool{ return $this->role === 'kontributor'; }
    public function canApprove(): bool
    {
        return in_array($this->role, ['super_admin', 'admin', 'operator']);
    }

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    // Lokasi yang ditambahkan oleh user ini (sebagai kontributor)
    public function lokasi()
    {
        return $this->hasMany(Lokasi::class, 'created_by');
    }

    // Review yang ditulis user ini
    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_user');
    }

    // Pengaduan yang dikirim user ini
    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class, 'id_user');
    }

    // Lokasi favorit user ini
    public function favorits()
    {
        return $this->hasMany(Favorit::class, 'id_user');
    }

    // Shortcut: ambil lokasi yang difavoritkan (many-to-many style)
    public function lokasiDifavorit()
    {
        return $this->belongsToMany(Lokasi::class, 'favorit', 'id_user', 'id_lokasi')
                    ->withTimestamps();
    }

    // Riwayat kunjungan user ini
    public function riwayatKunjungans()
    {
        return $this->hasMany(RiwayatKunjungan::class, 'id_user');
    }

    // Log approval yang dilakukan user ini (sebagai admin/operator)
    public function approvalLogs()
    {
        return $this->hasMany(ApprovalLog::class, 'id_admin');
    }

    // Event yang dibuat user ini
    public function events()
    {
        return $this->hasMany(Event::class, 'created_by');
    }
}