<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->char('nim',15);
            $table->string('nama',40);
            $table->string('email',64);
            $table->string('password',70);
            $table->char('telp',15);
            $table->enum('role_user',['admin','peminjam'])->default('peminjam');
            $table->string('foto_bwp',100);
            $table->enum('status_user',['belum_dikonfirmasi','terkonfirmasi','tidak_dikonfirmasi'])->default('belum_dikonfirmasi');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
