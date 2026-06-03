<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lokasi', function (Blueprint $table) {
            // Cache rating supaya tidak perlu hitung AVG setiap saat
            $table->decimal('rating_avg', 3, 2)->default(0)->after('status_verifikasi');
            $table->unsignedInteger('jumlah_review')->default(0)->after('rating_avg');
            $table->unsignedInteger('jumlah_kunjungan')->default(0)->after('jumlah_review');
        });
    }

    public function down(): void
    {
        Schema::table('lokasi', function (Blueprint $table) {
            $table->dropColumn(['rating_avg', 'jumlah_review', 'jumlah_kunjungan']);
        });
    }
};