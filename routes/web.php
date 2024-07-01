<?php

use App\Http\Controllers\AdminController\DashboardController as AdminDashboardController;
use App\Http\Controllers\AdminController\KalenderController;
use App\Http\Controllers\AdminController\PeminjamanController as AdminPeminjamanController;
use App\Http\Controllers\AdminController\RiwayatController as AdminRiwayatController;
use App\Http\Controllers\AdminController\RuanganController as AdminRuanganController;
use App\Http\Controllers\AdminController\FasilitasController as AdminFasilitasController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\UserController\DashboardController;
use App\Http\Controllers\UserController\PeminjamanController;
use App\Http\Controllers\UserController\RiwayatController;
use App\Http\Controllers\webAuthController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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


Route::get('/', [LandingPageController::class, 'index']);


//register
Route::get('/daftar', [webAuthController::class, 'viewRegister']);
Route::post('/daftar', [webAuthController::class, 'Register'])->name('register');

//login
Route::get('/login', [webAuthController::class, 'viewLogin']);
Route::post('/login', [webAuthController::class, 'Login']);

Route::middleware('checkToken')->group(function () {
    //admin
    Route::get('/admin/dashboard', [AdminDashboardController::class,'index']);
    Route::get('/admin/DataRuangan', [AdminRuanganController::class, 'index']); //data ruangan
    Route::get('/DataRuangan/TambahRuangan', [AdminRuanganController::class, 'create']);
    Route::post('/DataRuangan/TambahRuangan', [AdminRuanganController::class, 'store'])->name('tambah_ruangan');
    Route::get('/DataRuangan/UbahRuangan/{id}', [AdminRuanganController::class, 'edit']);
    Route::put('/DataRuangan/UbahRuangan/{id}', [AdminRuanganController::class, 'update']);
    Route::get('/admin/DataFasilitas', [AdminFasilitasController::class, 'index']);//data fasilitas
    Route::get('/DataFasilitas/TambahFasilitas', [AdminFasilitasController::class, 'create']);
    Route::post('/DataFasilitas/TambahFasilitas', [AdminFasilitasController::class, 'store'])->name('tambah_fasilitas');
    Route::get('/DataFasilitas/UbahFasilitas/{id}', [AdminFasilitasController::class, 'edit']);
    Route::put('/DataFasilitas/UbahFasilitas/{id}', [AdminFasilitasController::class, 'update']);

    Route::get('/admin/accpeminjaman', [AdminPeminjamanController::class, 'index']); //halaman acc peminjaman
    Route::get('/admin/accpeminjaman/detail/{id}', [AdminPeminjamanController::class, 'show']); //detail pemijaman
    Route::put('/update-status/{id}', [AdminPeminjamanController::class, 'updateStatus'])->name('update-status'); //update status
    Route::get('/unduh-file/{id}', [AdminPeminjamanController::class, 'unduhfile']); //untuk download bukti peminjaman
    Route::get('/admin/riwayat', [AdminRiwayatController::class, 'riwayat'])->name('riwayat_search');
    Route::get('/admin/kalender', [KalenderController::class, 'index']);

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
    Route::get('/riwayat', [RiwayatController::class, 'riwayatPeminjaman'])->name('riwayatku_search');
    // Route::get('/kalender', [DashboardController::class, 'KalenderPeminjaman']);



    Route::post('/logout', [webAuthController::class, 'Logout'])->name('logout');
});
