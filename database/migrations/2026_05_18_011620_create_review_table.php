<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review', function (Blueprint $table) {
            $table->bigIncrements('id_review');
            $table->unsignedBigInteger('id_lokasi');
            $table->unsignedBigInteger('id_user');
            $table->tinyInteger('rating')->unsigned(); // nilai 1 - 5
            $table->text('komentar')->nullable();
            $table->timestamps();

            // Satu user hanya bisa review satu lokasi sekali
            $table->unique(['id_lokasi', 'id_user']);

            $table->foreign('id_lokasi')
                  ->references('id_lokasi')
                  ->on('lokasi')
                  ->onDelete('cascade');

            $table->foreign('id_user')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review');
    }
};