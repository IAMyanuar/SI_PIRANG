<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanFasilitas extends Model
{
    use HasFactory;
    protected $fillable= ['jumlah'];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
