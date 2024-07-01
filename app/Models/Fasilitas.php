<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;
    protected $table = 'fasilitas';
    protected $fillable=['nama','foto','jumlah'];

    public function peminjamanFasilitas()
    {
        return $this->hasMany(PeminjamanFasilitas::class, 'id_fasilitas');
    }
}
