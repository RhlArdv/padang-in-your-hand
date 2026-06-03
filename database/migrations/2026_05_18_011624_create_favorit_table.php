<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorit', function (Blueprint $table) {
            $table->bigIncrements('id_favorit');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_lokasi');
            $table->timestamps();

            // Satu user tidak bisa favorit lokasi yang sama dua kali
            $table->unique(['id_user', 'id_lokasi']);

            $table->foreign('id_user')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('id_lokasi')
                  ->references('id_lokasi')
                  ->on('lokasi')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorit');
    }
};