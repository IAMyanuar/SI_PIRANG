<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\authController;
use App\Http\Controllers\API\RuanganController;
use App\Http\Controllers\API\PeminjamanController;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/',function(){
    return response()->json([
        'status' => false,
        'massage' => 'akses tidak diperbolehkan'
    ],401);
})->name('login');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ->middleware('auth:sanctum','ablity:access-user');

Route::post('registerUser',[authController::class, 'RegisterUser']);//register
Route::post('login',[authController::class, 'Login']);//login
Route::post('logout',[authController::class, 'Logout'])->middleware('auth:sanctum');//logout

Route::get('ruangan',[RuanganController::class, 'index'])->middleware('auth:sanctum');//menampilkan data ruangan
Route::post('tambahruangan',[RuanganController::class, 'store'])->middleware('auth:sanctum','ablity:access-admin');//tambah ruangan
Route::get('ruangan/{id}',[RuanganController::class,'show'])->middleware('auth:sanctum','ablity:access-admin');//menampilkan data ruangan berdasarkan id
Route::post('ruangan/{id}',[RuanganController::class,'update'])->middleware('auth:sanctum','ablity:access-admin');//edit ruangan

//admin
Route::get('peminjaman/submitted',[PeminjamanController::class, 'index'])->middleware('auth:sanctum','ablity:access-admin');//menampilkan data peminjaman yang belum disetujui
Route::get('peminjaman/approve',[PeminjamanController::class, 'peminjamApprove'])->middleware('auth:sanctum','ablity:access-admin');//menampilkan data peminjaman approve
Route::get('peminjaman/inprogress',[PeminjamanController::class, 'peminjamInProgress'])->middleware('auth:sanctum','ablity:access-admin');//menampilkan data peminjaman in progress
Route::get('peminjaman/{id}',[PeminjamanController::class,'show'])->middleware('auth:sanctum');//menampilkan data berdasarkan id
Route::get('unduhFileDokumen/{id}',[PeminjamanController::class,'unduhFile']);//
Route::post('peminjaman',[PeminjamanController::class, 'store']);//tambah peminjaman
Route::put('peminjaman/{id}',[PeminjamanController::class,'updateStatus'])->middleware('auth:sanctum','ablity:access-admin');//edit Status
Route::delete('peminjaman/{id}',[PeminjamanController::class, 'destroy']);


//user
Route::get('peminjamanbyuser/{id}',[PeminjamanController::class,'peminjamanByUser'])->middleware('auth:sanctum','ablity:access-user');//menampilkan data berdasarkan id
Route::post('EditPeminjaman/{id}',[PeminjamanController::class,'update'])->middleware('auth:sanctum','ablity:access-user');//edit ruangan
Route::patch('peminjaman/{id}/feedback',[PeminjamanController::class,'feedback'])->middleware('auth:sanctum','ablity:access-user');

