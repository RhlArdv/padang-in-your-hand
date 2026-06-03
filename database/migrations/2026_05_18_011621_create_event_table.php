<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event', function (Blueprint $table) {
            $table->bigIncrements('id_event');
            $table->string('nama_event', 255);
            $table->unsignedBigInteger('id_lokasi')->nullable(); // lokasi bisa dari tabel lokasi
            $table->string('lokasi_event', 255)->nullable();     // atau input manual alamat
            $table->enum('jenis_event', ['festival', 'wisata', 'olahraga', 'budaya', 'lainnya'])
                  ->default('lainnya');
            $table->text('deskripsi')->nullable();
            $table->string('banner', 255)->nullable(); // path banner event
            $table->datetime('tanggal_mulai');
            $table->datetime('tanggal_selesai');
            $table->enum('status', ['aktif', 'selesai', 'dibatalkan'])->default('aktif');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('id_lokasi')
                  ->references('id_lokasi')
                  ->on('lokasi')
                  ->onDelete('set null');

            $table->foreign('created_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event');
    }
};