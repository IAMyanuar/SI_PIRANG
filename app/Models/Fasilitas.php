<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;
    protected $table = 'fasilitas';
    protected $fillable=['nama','foto','jumlah'];

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'id_fasilitas');
    }
}
