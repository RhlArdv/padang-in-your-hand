<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom role setelah email
            $table->enum('role', ['super_admin', 'admin', 'operator', 'kontributor'])
                  ->default('kontributor')
                  ->after('email');

            // Tambah no_hp dan foto setelah role
            $table->string('no_hp', 20)->nullable()->after('role');
            $table->text('foto')->nullable()->after('no_hp');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'no_hp', 'foto']);
        });
    }
};