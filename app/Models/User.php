<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory,HasApiTokens,Notifiable;
    protected $fillable =['nim','nama','email','password','telp'];
    protected $table = 'users';


    public function getStatusAttribute()
    {
        return $this->role_user->value;
    }

    public function Peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
