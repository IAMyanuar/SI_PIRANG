<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanFasilitas extends Model
{
    use HasFactory;
    protected $fillable= ['jumlah'];

    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class, 'id_fasilitas');
    }

    public function peminjamans()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }
}
