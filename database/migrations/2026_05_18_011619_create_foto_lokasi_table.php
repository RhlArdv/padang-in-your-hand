<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foto_lokasi', function (Blueprint $table) {
            $table->bigIncrements('id_foto');
            $table->unsignedBigInteger('id_lokasi');
            $table->text('file_foto'); // path file atau URL
            $table->string('caption', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_lokasi')
                  ->references('id_lokasi')
                  ->on('lokasi')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foto_lokasi');
    }
};