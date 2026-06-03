<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riwayat_kunjungan', function (Blueprint $table) {
            // Status perjalanan user
            $table->enum('status', ['navigating', 'arrived'])
                  ->default('navigating')
                  ->after('id_lokasi');

            // Koordinat GPS saat user dianggap "sampai"
            // Logic: jika posisi user dalam radius 100m dari lokasi → arrived
            $table->decimal('latitude_arrived', 10, 8)
                  ->nullable()
                  ->after('status');

            $table->decimal('longitude_arrived', 11, 8)
                  ->nullable()
                  ->after('latitude_arrived');

            // Jarak tempuh dalam meter
            $table->decimal('jarak_tempuh', 10, 2)
                  ->nullable()
                  ->after('longitude_arrived')
                  ->comment('Jarak tempuh dalam meter');

            // Waktu mulai navigasi & waktu sampai
            $table->timestamp('mulai_navigasi')->nullable()->after('jarak_tempuh');
            $table->timestamp('waktu_tiba')->nullable()->after('mulai_navigasi');
        });
    }

    public function down(): void
    {
        Schema::table('riwayat_kunjungan', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'latitude_arrived',
                'longitude_arrived',
                'jarak_tempuh',
                'mulai_navigasi',
                'waktu_tiba',
            ]);
        });
    }
};