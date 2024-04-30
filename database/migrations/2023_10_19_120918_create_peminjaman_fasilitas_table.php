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
        Schema::create('peminjaman_fasilitas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_peminjaman');
            $table->foreign('id_peminjaman')->references('id')->on('peminjamen');
            $table->unsignedInteger('id_fasilitas');
            $table->foreign('id_fasilitas')->references('id')->on('fasilitas');
            $table->integer('jumlah')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_fasilitas');
    }
};
