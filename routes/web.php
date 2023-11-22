<?php

use App\Http\Controllers\UserController\PeminjamanController;
use App\Http\Controllers\webAuthController;
use App\Http\Controllers\webRuanganController;
use App\Http\Controllers\webPeminjamanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     return view('layout.login');
// });

// Route::get('/daftar', function () {
//     return view('layout.register');
// });

//admin
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/admin/kalender', function () {
    return view('admin.kalender');
});

// Route::get('/admin/accpeminjaman', function () {
//     return view('admin.acc_peminjaman');
// });

// Route::get('/admin/laporan', function () {
//     return view('admin.laporan');
// });

// Route::get('/laporan/detaillaporan', function () {
//     return view('admin.detail_laporan');
// });

// Route::get('/admin/DataRuangan', function () {
//     return view('admin.data_ruangan');
// });


// Route::get('/DataRuangan/UbahRuangan', function () {
//     return view('admin.ubah_ruangan');
// });

// user
Route::get('/dashboard', function () {
    return view('user.dashboard');
});

Route::get('/kalender', function () {
    return view('user.kalender');
});

// Route::get('/AjukanPeminjaman', function () {
//     return view('user.ajukan_peminjaman');
// });

// Route::get('/PengajuanPeminjaman', function () {
//     return view('user.pengajuan_peminjaman');
// });

// Route::get('/EditPeminjaman', function () {
//     return view('user.edit_peminjaman');
// });

Route::get('/BuktiPeminjaman', function () {
    return view('user.bukti_peminjaman');
});

// Route::get('/riwayat', function () {
//     return view('user.riwayat');
// });

// Route::get('/detaillaporan', function () {
//     return view('user.detail_laporan');
// });


//fix admin
Route::get('/admin/DataRuangan',[webRuanganController::class,'index']); //data ruangan
Route::get('/DataRuangan/TambahRuangan', [webRuanganController::class,'create']);
Route::post('/DataRuangan/TambahRuangan', [webRuanganController::class,'store'])->name('tambah_ruangan');
Route::get('/DataRuangan/UbahRuangan/{id}', [webRuanganController::class,'edit']);
Route::put('/DataRuangan/UbahRuangan/{id}', [webRuanganController::class,'update']);

Route::get('/admin/accpeminjaman',[webPeminjamanController::class,'index']);//halaman acc peminjaman
Route::get('/admin/accpeminjaman/detail/{id}',[webPeminjamanController::class,'show']);//detail pemijaman
Route::put('/update-status/{id}', [webPeminjamanController::class,'updateStatus'])->name('update-status');//update status
Route::get('/unduh-file/{id}',[webPeminjamanController::class,'unduhfile']);//untuk download bukti peminjaman

//register
Route::get('/daftar',[webAuthController::class,'viewRegister']);
Route::post('/daftar',[webAuthController::class,'Register'])->name('register');

//login
Route::get('/',[webAuthController::class,'viewLogin']);
Route::post('/',[webAuthController::class,'Login']);
Route::post('/logout',[webAuthController::class,'Logout'])->name('logout');


//user
Route::get('/PengajuanPeminjaman', [PeminjamanController::class, 'peminjamanku']);
Route::get('/AjukanPeminjaman', [PeminjamanController::class,'create']);
Route::post('/AjukanPeminjaman',[PeminjamanController::class,'store'])->name('form_ajukan_peminjaman');
Route::delete('/PengajuanPeminjaman/{id}',[PeminjamanController::class,'destroy'])->name('hapus_pengajuan');
Route::get('/EditPeminjaman/{id}', [PeminjamanController::class,'edit']);
Route::get('/peminjaman/detail/{id}',[PeminjamanController::class,'show']);//detail pemijaman user
Route::post('/EditPeminjaman/{id}', [PeminjamanController::class,'update'])->name('ubah_pengajuan');
Route::patch('/PengajuanPeminjaman/{id}', [PeminjamanController::class,'updateStatus'])->name('ulasan');
