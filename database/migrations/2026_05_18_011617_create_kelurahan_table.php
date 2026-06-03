<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelurahan', function (Blueprint $table) {
            $table->bigIncrements('id_kelurahan');
            $table->unsignedBigInteger('id_kecamatan');
            $table->string('nama_kelurahan', 100);
            $table->timestamps();

            $table->foreign('id_kecamatan')
                  ->references('id_kecamatan')
                  ->on('kecamatan')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelurahan');
    }
};