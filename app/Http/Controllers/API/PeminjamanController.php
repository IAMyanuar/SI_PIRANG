<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePeminjamanRequest;
use Doctrine\Inflector\Rules\English\Rules;
use Illuminate\Support\Facades\Validator;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // menampilkan data peminjaman statsu submitted
        $datapeminjaman = Peminjaman::join('users', 'peminjamen.user_id', '=', 'users.id')
            ->join('ruangans', 'peminjamen.id_ruangan', '=', 'ruangans.id')
            ->select('peminjamen.id', 'users.nama as nama_user', 'peminjamen.nama_lembaga','peminjamen.kegiatan',
            Peminjaman::raw('DATE(peminjamen.tgl_mulai) as tgl_mulai'), // Mengambil tanggal saja
            Peminjaman::raw('TIME(peminjamen.tgl_mulai) as jam_mulai'), // Mengambil waktu saja
            Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
            Peminjaman::raw('TIME(peminjamen.tgl_selesai) as jam_selesai'), // Mengambil waktu saja
                    'peminjamen.status','peminjamen.feedback','peminjamen.dokumen_pendukung',
                    'ruangans.nama as nama_ruangan','users.nim','users.email','users.telp')->where('peminjamen.status','=','submitted')
            ->get();
        return response()->json([
            'status' => true,
            'message' => 'data ditemukan',
            'data' => $datapeminjaman,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //tambah data peminjaman
        $rules= [
            'nama_lembaga' => 'required',
            'kegiatan' => 'required',
            'tgl_mulai' => 'required',
            'tgl_selesai' => 'required',
            'feedback' => 'nullable',
            'dokumen_pendukung' => 'nullable',
            'user_id' => 'required',
            'id_ruangan' => 'required',
        ];

        //validasi data yang di insert
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'prsoses ajukan peminjaman gagal',
                'data' => $validator->errors()
            ], 401);
        }


        $ruangan = $request -> id_ruangan;
        $tgl_mulai = $request -> tgl_mulai;
        $tgl_selesai = $request -> tgl_selesai;

        //cek apakah sudah ada yang meminjam ruangan tsb
        $CekDB = Peminjaman::where('id_ruangan', $ruangan)
        ->where('status', 'approved')
        ->where(function ($query) use ($tgl_mulai, $tgl_selesai) {
            $query->where(function ($query) use ($tgl_mulai, $tgl_selesai) {
                $query->whereBetween('tgl_mulai', [$tgl_mulai, $tgl_selesai])
                      ->orWhereBetween('tgl_selesai', [$tgl_mulai, $tgl_selesai]);
                    })
                    ->orWhere(function ($query) use ($tgl_mulai, $tgl_selesai) {
                        $query->where('tgl_mulai', '>=', $tgl_mulai)
                              ->where('tgl_selesai', '<=', $tgl_selesai);
                    });
                })
        ->exists();

        if (!$CekDB) {
            $datapeminjaman = new Peminjaman;
            $datapeminjaman -> nama_lembaga = $request->nama_lembaga;
            $datapeminjaman -> kegiatan = $request->kegiatan;
            $datapeminjaman -> tgl_mulai = $request -> tgl_mulai;
            $datapeminjaman -> tgl_selesai = $request -> tgl_selesai;
            $datapeminjaman -> feedback = $request->feedback;
            $datapeminjaman -> user_id = $request->user_id;
            $datapeminjaman -> id_ruangan = $request -> id_ruangan;


            if ($request->hasFile('dokumen_pendukung')) {
                $dokumen_pendukung = $request->file('dokumen_pendukung');
                $namadokumen = time() . '.' . $dokumen_pendukung->getClientOriginalExtension();
                $dokumen_pendukung->move(public_path('assets/images/bukti_pendukung'), $namadokumen);
                $datapeminjaman->dokumen_pendukung = $namadokumen;
            }

            //masukkan ke DB
            $datapeminjaman->save();

            return response()->json([
                'status' => true,
                'message' => 'prsoses ajukan peminjaman ruangan berhasil',
            ], 200);
            
        }else {
            return response()->json([
                'status' => false,
                'message' => 'Ruangan sudah ada yang meminjam',
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $datapeminjaman = Peminjaman::join('users', 'peminjamen.user_id', '=', 'users.id')
            ->join('ruangans', 'peminjamen.id_ruangan', '=', 'ruangans.id')
            ->select('peminjamen.id', 'users.nama as nama_user', 'peminjamen.nama_lembaga','peminjamen.kegiatan',
            Peminjaman::raw('DATE(peminjamen.tgl_mulai) as tgl_mulai'), // Mengambil tanggal saja
            Peminjaman::raw('TIME(peminjamen.tgl_mulai) as jam_mulai'), // Mengambil waktu saja
            Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
            Peminjaman::raw('TIME(peminjamen.tgl_selesai) as jam_selesai'), // Mengambil waktu saja
                    'peminjamen.status','peminjamen.feedback','peminjamen.dokumen_pendukung',
                    'ruangans.nama as nama_ruangan','users.nim','users.email','users.telp')->find($id);

        if(empty($datapeminjaman)){
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'data ditemukan',
            'data' => $datapeminjaman,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function updateStatus(Request $request, $id)
    {
        //update status
        $datapeminjaman = Peminjaman::find($id);

        if (empty($datapeminjaman)) {
            return response()->json([
                'status' => 'false',
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $rules= [
            'status'=> 'required',
        ];


        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Proses ubah status peminjaman gagal',
                'data' => $validator->errors(),
                'request' => $request->all(),
            ], 400);
        }
        $datapeminjaman->status = $request->status;
        $datapeminjaman->feedback = $request->feedback;
        $datapeminjaman->save();

        return response()->json([
            'status' => 'true',
            'message' => 'Proses ubah ststus peminjaman berhasil',
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peminjaman $peminjaman)
    {
        //
    }

    public function unduhFile($id){
        $peminjaman = Peminjaman::find($id);
        if ($peminjaman) {
            $dokumen_pendukung = $peminjaman->dokumen_pendukung;
            $pathToFile = public_path('assets/images/bukti_pendukung/' . $dokumen_pendukung);

            if (file_exists($pathToFile)) {
                // Mendapatkan ekstensi file
                $fileExtension = pathinfo($pathToFile, PATHINFO_EXTENSION);

                // Menentukan Content-Type sesuai dengan ekstensi file
                $contentTypes = [
                    'jpg' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                    'pdf' => 'application/pdf',
                ];

                $contentType = $contentTypes[$fileExtension] ?? 'application/octet-stream';

                return response()->file($pathToFile, [
                    'Content-Type' => $contentType,
                    'Content-Disposition' => 'attachment; filename=' . $dokumen_pendukung,
                ]);
            }
        }

        return response()->json([
            'status' => 'false',
            'message' => 'Data tidak ditemukan',
        ], 404);

    }

    public function peminjamApprove()
    {
        // menampilkan data peminjaman status approve
        $datapeminjaman = Peminjaman::join('users', 'peminjamen.user_id', '=', 'users.id')
            ->join('ruangans', 'peminjamen.id_ruangan', '=', 'ruangans.id')
            ->select('peminjamen.id', 'users.nama as nama_user', 'peminjamen.nama_lembaga','peminjamen.kegiatan',
            Peminjaman::raw('DATE(peminjamen.tgl_mulai) as tgl_mulai'), // Mengambil tanggal saja
            Peminjaman::raw('TIME(peminjamen.tgl_mulai) as jam_mulai'), // Mengambil waktu saja
            Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
            Peminjaman::raw('TIME(peminjamen.tgl_selesai) as jam_selesai'), // Mengambil waktu saja
                    'peminjamen.status','peminjamen.feedback','peminjamen.dokumen_pendukung',
                    'ruangans.nama as nama_ruangan','users.nim','users.email','users.telp')->where('peminjamen.status','=','approved')
            ->get();
        return response()->json([
            'status' => true,
            'message' => 'data ditemukan',
            'data' => $datapeminjaman,
        ], 200);
    }

    public function peminjamInProgress()
    {
        // menampilkan data peminjaman status approve
        $datapeminjaman = Peminjaman::join('users', 'peminjamen.user_id', '=', 'users.id')
            ->join('ruangans', 'peminjamen.id_ruangan', '=', 'ruangans.id')
            ->select('peminjamen.id', 'users.nama as nama_user', 'peminjamen.nama_lembaga','peminjamen.kegiatan',
            Peminjaman::raw('DATE(peminjamen.tgl_mulai) as tgl_mulai'), // Mengambil tanggal saja
            Peminjaman::raw('TIME(peminjamen.tgl_mulai) as jam_mulai'), // Mengambil waktu saja
            Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
            Peminjaman::raw('TIME(peminjamen.tgl_selesai) as jam_selesai'), // Mengambil waktu saja
                    'peminjamen.status','peminjamen.feedback','peminjamen.dokumen_pendukung',
                    'ruangans.nama as nama_ruangan','users.nim','users.email','users.telp')->where('peminjamen.status','=','in progress')
            ->get();
        return response()->json([
            'status' => true,
            'message' => 'data ditemukan',
            'data' => $datapeminjaman,
        ], 200);
    }


}
