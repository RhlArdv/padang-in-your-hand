<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->bigIncrements('id_pengaduan');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_lokasi')->nullable();
            $table->enum('jenis_pengaduan', [
                'lokasi_salah',
                'foto_tidak_pantas',
                'informasi_salah',
                'tempat_ramai',
                'fasilitas_rusak',
                'lainnya'
            ])->default('lainnya');
            $table->text('isi_pengaduan');
            $table->string('foto_bukti', 255)->nullable(); // foto pendukung pengaduan
            $table->enum('status', ['pending', 'diproses', 'selesai', 'ditolak'])
                  ->default('pending');
            $table->text('catatan_admin')->nullable(); // tindak lanjut dari admin
            $table->timestamps();

            $table->foreign('id_user')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('id_lokasi')
                  ->references('id_lokasi')
                  ->on('lokasi')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};