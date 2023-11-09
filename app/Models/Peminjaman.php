<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;
    protected $fillable =['kegiatan','tgl_mulai','tgl_selesai','status','dokumen_pendukung','feedback'];
    protected $dates = ['created_at', 'updated_at'];
    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }
}
