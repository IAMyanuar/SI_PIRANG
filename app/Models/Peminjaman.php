<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;
    protected $fillable = ['kegiatan', 'tgl_mulai', 'tgl_selesai', 'status', 'dokumen_pendukung', 'feedback'];
    protected $dates = ['created_at', 'updated_at'];


    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function peminjamanFasilitas()
    {
        return $this->hasMany(PeminjamanFasilitas::class, 'id_peminjaman');
    }


}
