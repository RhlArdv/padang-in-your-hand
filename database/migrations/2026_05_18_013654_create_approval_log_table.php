<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_log', function (Blueprint $table) {
            $table->bigIncrements('id_log');

            // Lokasi yang sedang diproses
            $table->unsignedBigInteger('id_lokasi');

            // Admin/Operator yang melakukan aksi
            $table->unsignedBigInteger('id_admin');

            // Status yang diberikan admin pada saat itu
            $table->enum('status', ['pending', 'disetujui', 'ditolak', 'revisi']);

            // Catatan dari admin ke kontributor
            // contoh: "Foto kurang jelas, mohon upload ulang"
            $table->text('catatan')->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->foreign('id_lokasi')
                  ->references('id_lokasi')
                  ->on('lokasi')
                  ->onDelete('cascade');

            $table->foreign('id_admin')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_log');
    }
};