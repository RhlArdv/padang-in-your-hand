<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lokasi', function (Blueprint $table) {
            $table->bigIncrements('id_lokasi');
            $table->string('nama_tempat', 255);
            $table->unsignedBigInteger('id_kategori');
            $table->text('alamat');
            $table->unsignedBigInteger('id_kecamatan');
            $table->unsignedBigInteger('id_kelurahan');
            $table->decimal('latitude', 10, 8);   // contoh: -0.94924
            $table->decimal('longitude', 11, 8);  // contoh: 100.35403
            $table->text('deskripsi')->nullable();
            $table->string('jam_operasional', 255)->nullable(); // contoh: "08:00 - 17:00"
            $table->string('kontak', 50)->nullable();
            $table->string('website', 255)->nullable();
            $table->enum('status_verifikasi', ['pending', 'disetujui', 'ditolak', 'revisi'])
                  ->default('pending');
            $table->unsignedBigInteger('created_by'); // FK ke users
            $table->timestamps();

            $table->foreign('id_kategori')
                  ->references('id_kategori')
                  ->on('kategori')
                  ->onDelete('restrict');

            $table->foreign('id_kecamatan')
                  ->references('id_kecamatan')
                  ->on('kecamatan')
                  ->onDelete('restrict');

            $table->foreign('id_kelurahan')
                  ->references('id_kelurahan')
                  ->on('kelurahan')
                  ->onDelete('restrict');

            $table->foreign('created_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lokasi');
    }
};