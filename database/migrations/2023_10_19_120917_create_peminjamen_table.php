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
        Schema::create('peminjamen', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_lembaga',65)->require();
            $table->text('kegiatan')->require();
            $table->dateTime('tgl_mulai')->require();
            $table->dateTime('tgl_selesai')->require();
            $table->enum('status',['terkirim','disetujui','ditolak','di prosess','selesai'])->default('terkirim');
            $table->text('feedback')->nullable();
            $table->string('dokumen_pendukung',50)->nullable();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('id_ruangan');
            $table->foreign('id_ruangan')->references('id')->on('ruangans');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamen');
    }
};
