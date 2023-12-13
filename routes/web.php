<?php

use App\Http\Controllers\UserController\DashboardController;
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


// Route::get('/BuktiPeminjaman', function () {
//     return view('user.bukti_peminjaman');
// });


//register
Route::get('/daftar', [webAuthController::class, 'viewRegister']);
Route::post('/daftar', [webAuthController::class, 'Register'])->name('register');
Route::get('/admin/report', [webPeminjamanController::class, 'report']);

//login
Route::get('/', [webAuthController::class, 'viewLogin']);
Route::post('/', [webAuthController::class, 'Login']);

Route::middleware('checkToken')->group(function () {
    //admin
    Route::get('/admin/dashboard', [webPeminjamanController::class,'report']);
    Route::get('/admin/DataRuangan', [webRuanganController::class, 'index']); //data ruangan
    Route::get('/DataRuangan/TambahRuangan', [webRuanganController::class, 'create']);
    Route::post('/DataRuangan/TambahRuangan', [webRuanganController::class, 'store'])->name('tambah_ruangan');
    Route::get('/DataRuangan/UbahRuangan/{id}', [webRuanganController::class, 'edit']);
    Route::put('/DataRuangan/UbahRuangan/{id}', [webRuanganController::class, 'update']);

    Route::get('/admin/accpeminjaman', [webPeminjamanController::class, 'index']); //halaman acc peminjaman
    Route::get('/admin/accpeminjaman/detail/{id}', [webPeminjamanController::class, 'show']); //detail pemijaman
    Route::put('/update-status/{id}', [webPeminjamanController::class, 'updateStatus'])->name('update-status'); //update status
    Route::get('/unduh-file/{id}', [webPeminjamanController::class, 'unduhfile']); //untuk download bukti peminjaman
    Route::get('/admin/riwayat', [webPeminjamanController::class, 'riwayat'])->name('riwayat_search');
    Route::get('/admin/kalender', [webPeminjamanController::class, 'KalenderPeminjaman']);

    //user
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/PengajuanPeminjaman', [PeminjamanController::class, 'peminjamanku']);
    Route::get('/AjukanPeminjaman', [PeminjamanController::class, 'create']);
    Route::post('/AjukanPeminjaman', [PeminjamanController::class, 'store'])->name('form_ajukan_peminjaman');
    Route::delete('/PengajuanPeminjaman/{id}', [PeminjamanController::class, 'destroy'])->name('hapus_pengajuan');
    Route::get('/EditPeminjaman/{id}', [PeminjamanController::class, 'edit']);
    Route::get('/peminjaman/detail/{id}', [PeminjamanController::class, 'show']); //detail pemijaman user
    Route::post('/EditPeminjaman/{id}', [PeminjamanController::class, 'update'])->name('ubah_pengajuan');
    Route::patch('/PengajuanPeminjaman/{id}', [PeminjamanController::class, 'updateStatus'])->name('ulasan');
    Route::get('/riwayat', [PeminjamanController::class, 'riwayatPeminjaman'])->name('riwayatku_search');
    Route::get('/kalender', [PeminjamanController::class, 'KalenderPeminjaman']);

    Route::post('/logout', [webAuthController::class, 'Logout'])->name('logout');
});
