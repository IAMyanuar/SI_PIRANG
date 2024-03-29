<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePeminjamanRequest;
use Doctrine\Inflector\Rules\English\Rules;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // menampilkan data peminjaman status submitted
        $datapeminjaman = Peminjaman::join('users', 'peminjamen.user_id', '=', 'users.id')
            ->join('ruangans', 'peminjamen.id_ruangan', '=', 'ruangans.id')
            ->select(
                'peminjamen.id',
                'users.nama as nama_user',
                'peminjamen.nama_lembaga',
                'peminjamen.kegiatan',
                Peminjaman::raw('DATE(peminjamen.tgl_mulai) as tgl_mulai'), // Mengambil tanggal saja
                Peminjaman::raw('TIME(peminjamen.tgl_mulai) as jam_mulai'), // Mengambil waktu saja
                Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
                Peminjaman::raw('TIME(peminjamen.tgl_selesai) as jam_selesai'), // Mengambil waktu saja
                'peminjamen.status',
                'peminjamen.feedback',
                'peminjamen.dokumen_pendukung',
                'ruangans.nama as nama_ruangan',
                'users.nim',
                'users.email',
                'users.telp'
            )->where('peminjamen.status', '=', 'submitted')
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
        $rules = [
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
            ], 422);
        }


        $ruangan = $request->id_ruangan;
        $tgl_mulai = $request->tgl_mulai;
        $tgl_selesai = $request->tgl_selesai;

        //cek apakah sudah ada yang meminjam ruangan tsb
        $CekDB = Peminjaman::where('id_ruangan', $ruangan)
            ->whereIn('status', ['approved', 'in progress'])
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
            $datapeminjaman->nama_lembaga = $request->nama_lembaga;
            $datapeminjaman->kegiatan = $request->kegiatan;
            $datapeminjaman->tgl_mulai = $request->tgl_mulai;
            $datapeminjaman->tgl_selesai = $request->tgl_selesai;
            $datapeminjaman->feedback = $request->feedback;
            $datapeminjaman->user_id = $request->user_id;
            $datapeminjaman->id_ruangan = $ruangan;


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
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Ruangan sudah ada yang meminjam',
            ], 409);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //menampilkan data peminjaman by id(detail)
        $datapeminjaman = Peminjaman::join('users', 'peminjamen.user_id', '=', 'users.id')
            ->join('ruangans', 'peminjamen.id_ruangan', '=', 'ruangans.id')
            ->select(
                'peminjamen.id',
                'users.nama as nama_user',
                'peminjamen.nama_lembaga',
                'peminjamen.kegiatan',
                Peminjaman::raw('DATE(peminjamen.tgl_mulai) as tgl_mulai'), // Mengambil tanggal saja
                Peminjaman::raw('TIME(peminjamen.tgl_mulai) as jam_mulai'), // Mengambil waktu saja
                Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
                Peminjaman::raw('TIME(peminjamen.tgl_selesai) as jam_selesai'), // Mengambil waktu saja
                'peminjamen.status',
                'peminjamen.feedback',
                'peminjamen.dokumen_pendukung',
                'ruangans.id as id_ruangan',
                'ruangans.nama as nama_ruangan',
                'users.nim',
                'users.email',
                'users.telp'
            )->find($id);

        //memberi url foto
        if (!empty($datapeminjaman['dokumen_pendukung'])) {
            $datapeminjaman['dokumen_pendukung'] = url('assets/images/bukti_pendukung/' . $datapeminjaman['dokumen_pendukung']);
        }


        if (empty($datapeminjaman)) {
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
        //megambil id untuk mencari di db
        $datapeminjaman = Peminjaman::find($id);
        //jika data tidak ditemukan
        if (empty($datapeminjaman)) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }


        //edit peminjaman
        $rules = [
            'nama_lembaga' => 'required',
            'kegiatan' => 'required',
            'tgl_mulai' => 'required',
            'tgl_selesai' => 'required',
            'feedback' => 'nullable',
            'dokumen_pendukung' => 'nullable',
            'user_id' => 'required',
            'id_ruangan' => 'required',
        ];


        //validasi input
        $validator = Validator::make($request->all(), $rules);
        //mengembalikan respon ketika inputan salah
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Proses ubah data ruangan gagal',
                'data' => $validator->errors(),
                'request' => $request->all(),
            ], 400);
        }

        if ($request->hasFile('dokumen_pendukung')) {
            // Hapus file gambar lama jika ada
            if (!empty($datapeminjaman->dokumen_pendukung)) {
                $oldPhotoPath = public_path('assets/images/bukti_pendukung/' . $datapeminjaman->dokumen_pendukung);
                if (File::exists($oldPhotoPath)) {
                    File::delete($oldPhotoPath);
                }


                //simpan gambar baru
                $dokumen_pendukung = $request->file('dokumen_pendukung');
                $namadokumen = time() . '.' . $dokumen_pendukung->getClientOriginalExtension();
                $dokumen_pendukung->move(public_path('assets/images/bukti_pendukung'), $namadokumen);
                $datapeminjaman->dokumen_pendukung = $namadokumen;
            } else {
                $dokumen_pendukung = $request->file('dokumen_pendukung');
                $namadokumen = time() . '.' . $dokumen_pendukung->getClientOriginalExtension();
                $dokumen_pendukung->move(public_path('assets/images/bukti_pendukung'), $namadokumen);
                $datapeminjaman->dokumen_pendukung = $namadokumen;
            }
        }


        $datapeminjaman->nama_lembaga = $request->nama_lembaga;
        $datapeminjaman->kegiatan = $request->kegiatan;
        $datapeminjaman->tgl_mulai = $request->tgl_mulai;
        $datapeminjaman->tgl_selesai = $request->tgl_selesai;
        $datapeminjaman->feedback = $request->feedback;
        $datapeminjaman->user_id = $request->user_id;
        $datapeminjaman->id_ruangan = $request->id_ruangan;
        $datapeminjaman->update();

        return response()->json([
            'status' => true,
            'message' => 'Proses ubah pengajuan ruangan berhasil',
        ], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        //update status
        $datapeminjaman = Peminjaman::find($id);

        if (empty($datapeminjaman)) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $rules = [
            'status' => 'required',
        ];


        $validator = Validator::make($request->all(), $rules);
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
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //hapus peminjaman

        // Temukan peminjaman berdasarkan ID
        $peminjaman = Peminjaman::findOrFail($id);

        // Hapus peminjaman
        $peminjaman->delete();

        // mengambil data gambar dari db
        $dokumen_pendukung = $peminjaman->dokumen_pendukung;

        //cek apahah data ada. jika ada hapus dari folder
        if (!empty($dokumen_pendukung)) {
            // Hapus file gambar dari sistem file
            $pathToFile = public_path('assets/images/bukti_pendukung/' . $dokumen_pendukung);
            if (file_exists($pathToFile)) {
                unlink($pathToFile);
            }
        }

        // Beri respons berhasil
        return response()->json([
            'status' => true,
            'message' => 'Peminjaman berhasil dihapus',
        ], 200);
    }

    public function unduhFile($id)
    {
        $peminjaman = Peminjaman::find($id);
        // return $peminjaman;
        if ($peminjaman['dokumen_pendukung'] !== null) {
            $dokumen_pendukung = $peminjaman->dokumen_pendukung;
            $pathToFile = public_path('assets/images/bukti_pendukung/' . $dokumen_pendukung);

            if (file_exists($pathToFile)) {
                // Mendapatkan ekstensi file
                $fileExtension = pathinfo($pathToFile, PATHINFO_EXTENSION);

                // Menentukan Content-Type sesuai dengan ekstensi file
                $contentTypes = [
                    'jpg' => 'image/jpg',
                    'jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                    'pdf' => 'application/pdf',
                ];

                $contentType = $contentTypes[$fileExtension] ?? 'application/octet-stream';

                return response()->file($pathToFile, [
                    'Content-Type' => $contentType,
                    'Content-Disposition' => 'attachment; filename=' . $dokumen_pendukung,
                ], 200);
            } else {
                // File tidak ditemukan
                return response()->json([
                    'status' => false,
                    'message' => 'File tidak ditemukan',
                ], 404);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Data kosong',
        ], 400);
    }

    public function peminjamApprove()
    {
        // menampilkan data peminjaman status approve
        $datapeminjaman = Peminjaman::join('users', 'peminjamen.user_id', '=', 'users.id')
            ->join('ruangans', 'peminjamen.id_ruangan', '=', 'ruangans.id')
            ->select(
                'peminjamen.id',
                'users.nama as nama_user',
                'peminjamen.nama_lembaga',
                'peminjamen.kegiatan',
                Peminjaman::raw('DATE(peminjamen.tgl_mulai) as tgl_mulai'), // Mengambil tanggal saja
                Peminjaman::raw('TIME(peminjamen.tgl_mulai) as jam_mulai'), // Mengambil waktu saja
                Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
                Peminjaman::raw('TIME(peminjamen.tgl_selesai) as jam_selesai'), // Mengambil waktu saja
                'peminjamen.status',
                'peminjamen.feedback',
                'peminjamen.dokumen_pendukung',
                'ruangans.nama as nama_ruangan',
                'users.nim',
                'users.email',
                'users.telp'
            )->where('peminjamen.status', '=', 'approved')
            ->get();
        return response()->json([
            'status' => true,
            'message' => 'data ditemukan',
            'data' => $datapeminjaman,
        ], 200);
    }

    public function peminjamInProgress()
    {
        // menampilkan data peminjaman status inprogres
        $datapeminjaman = Peminjaman::join('users', 'peminjamen.user_id', '=', 'users.id')
            ->join('ruangans', 'peminjamen.id_ruangan', '=', 'ruangans.id')
            ->select(
                'peminjamen.id',
                'users.nama as nama_user',
                'peminjamen.nama_lembaga',
                'peminjamen.kegiatan',
                Peminjaman::raw('DATE(peminjamen.tgl_mulai) as tgl_mulai'), // Mengambil tanggal saja
                Peminjaman::raw('TIME(peminjamen.tgl_mulai) as jam_mulai'), // Mengambil waktu saja
                Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
                Peminjaman::raw('TIME(peminjamen.tgl_selesai) as jam_selesai'), // Mengambil waktu saja
                'peminjamen.status',
                'peminjamen.feedback',
                'peminjamen.dokumen_pendukung',
                'ruangans.nama as nama_ruangan',
                'users.nim',
                'users.email',
                'users.telp'
            )
            ->where('peminjamen.status', '=', 'in progress')
            ->get();
        return response()->json([
            'status' => true,
            'message' => 'data ditemukan',
            'data' => $datapeminjaman,
        ], 200);
    }

    public function peminjamanByUser($id)
    {
        //menampilkan data peminjaman berdasarkan user yang mengajukan
        $datapeminjaman = Peminjaman::join('users', 'peminjamen.user_id', '=', 'users.id')
            ->join('ruangans', 'peminjamen.id_ruangan', '=', 'ruangans.id')
            ->select(
                'peminjamen.id',
                'users.nama as nama_user',
                'peminjamen.nama_lembaga',
                'peminjamen.kegiatan',
                Peminjaman::raw('DATE(peminjamen.tgl_mulai) as tgl_mulai'), // Mengambil tanggal saja
                Peminjaman::raw('TIME(peminjamen.tgl_mulai) as jam_mulai'), // Mengambil waktu saja
                Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
                Peminjaman::raw('TIME(peminjamen.tgl_selesai) as jam_selesai'), // Mengambil waktu saja
                'peminjamen.status',
                'peminjamen.feedback',
                'peminjamen.dokumen_pendukung',
                'ruangans.nama as nama_ruangan',
                'users.nim',
                'users.email',
                'users.telp'
            )
            ->where('peminjamen.user_id', '=', $id)
            ->where(function ($query) {
                // Menampilkan data dengan status "submitted", "submited", dan "in progress"
                $query->whereIn('peminjamen.status', ['submitted', 'approved', 'in progress']);

                // Menampilkan data dengan status "completed" hanya jika kolom feedback kosong
                $query->orWhere(function ($subQuery) {
                    $subQuery->where('peminjamen.status', 'completed')
                        ->whereNull('peminjamen.feedback');
                });
            })
            ->orderBy('peminjamen.id', 'desc')
            ->get();

        if (empty($datapeminjaman)) {
            return response()->json([
                'status' => true,
                'message' => 'data belum ada',
                'data' => null,
            ], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'data ditemukan',
            'data' => $datapeminjaman,
        ], 200);
    }


    public function feedback(string $id, Request $request)
    {
        //memberi feedback
        $datapeminjaman = Peminjaman::find($id);

        if (empty($datapeminjaman)) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $rules = [
            'feedback' => 'required',
        ];


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Proses ubah status peminjaman gagal',
                'data' => $validator->errors(),
                'request' => $request->all(),
            ], 400);
        }

        $datapeminjaman->feedback = $request->feedback;
        $datapeminjaman->save();

        return response()->json([
            'status' => true,
            'message' => 'Proses ubah ststus peminjaman berhasil',
        ], 201);
    }


    public function riwatyatPeminjamanByUser(Request $request, $id)
    {
        //menampilkann data riwayat dan pencarian
        $keyword = $request->keyword;
        if (isset($keyword)) {
            $datapeminjaman = Peminjaman::join('users', 'peminjamen.user_id', '=', 'users.id')
                ->join('ruangans', 'peminjamen.id_ruangan', '=', 'ruangans.id')
                ->select(
                    'peminjamen.id',
                    'users.nama as nama_user',
                    'peminjamen.nama_lembaga',
                    'peminjamen.kegiatan',
                    Peminjaman::raw('DATE(peminjamen.tgl_mulai) as tgl_mulai'), // Mengambil tanggal saja
                    Peminjaman::raw('TIME(peminjamen.tgl_mulai) as jam_mulai'), // Mengambil waktu saja
                    Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
                    Peminjaman::raw('TIME(peminjamen.tgl_selesai) as jam_selesai'), // Mengambil waktu saja
                    'peminjamen.status',
                    'peminjamen.feedback',
                    'peminjamen.dokumen_pendukung',
                    'ruangans.nama as nama_ruangan',
                    'users.nim',
                    'users.email',
                    'users.telp'
                )
                ->where('peminjamen.user_id', '=', $id)
                ->where(function ($query) {
                    // Menampilkan data dengan status "submitted", "submited", dan "in progress"
                    $query->where('peminjamen.status', 'reject');

                    // Menampilkan data dengan status "completed" hanya jika kolom feedback kosong
                    $query->orWhere(function ($subQuery) {
                        $subQuery->where('peminjamen.status', 'completed')
                            ->whereNotNull('peminjamen.feedback');
                    });
                })
                ->where(function ($query) use ($keyword) {
                    $query->where('peminjamen.tgl_mulai', 'LIKE', "%" . $keyword . "%")
                        ->orWhere('peminjamen.nama_lembaga', 'LIKE', "%" . $keyword . "%")
                        ->orWhere('peminjamen.kegiatan', 'LIKE', "%" . $keyword . "%")
                        ->orWhere('ruangans.nama', 'LIKE', "%" . $keyword . "%");
                })
                ->orderBy('peminjamen.id', 'desc')
                ->get();
        } else {
            //menampilkann data riwayat peminjaman seluruh user
            $datapeminjaman = Peminjaman::join('users', 'peminjamen.user_id', '=', 'users.id')
                ->join('ruangans', 'peminjamen.id_ruangan', '=', 'ruangans.id')
                ->select(
                    'peminjamen.id',
                    'users.nama as nama_user',
                    'peminjamen.nama_lembaga',
                    'peminjamen.kegiatan',
                    Peminjaman::raw('DATE(peminjamen.tgl_mulai) as tgl_mulai'), // Mengambil tanggal saja
                    Peminjaman::raw('TIME(peminjamen.tgl_mulai) as jam_mulai'), // Mengambil waktu saja
                    Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
                    Peminjaman::raw('TIME(peminjamen.tgl_selesai) as jam_selesai'), // Mengambil waktu saja
                    'peminjamen.status',
                    'peminjamen.feedback',
                    'peminjamen.dokumen_pendukung',
                    'ruangans.nama as nama_ruangan',
                    'users.nim',
                    'users.email',
                    'users.telp'
                )
                ->where('peminjamen.user_id', '=', $id)
                ->where(function ($query) {
                    // Menampilkan data dengan status "submitted", "submited", dan "in progress"
                    $query->where('peminjamen.status', 'reject');

                    // Menampilkan data dengan status "completed" hanya jika kolom feedback kosong
                    $query->orWhere(function ($subQuery) {
                        $subQuery->where('peminjamen.status', 'completed')
                            ->whereNotNull('peminjamen.feedback');
                    });
                })
                ->orderBy('peminjamen.id', 'desc')
                ->get();
        }

        //jika data kosong
        if ($datapeminjaman->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => isset($keyword) ? 'Data yang anda cari tidak ditemukan' : 'Belum ada data riwayat peminjaman',
                'data' => null,
            ], 401); // Use 404 status code for "not found"
        }

        //jika data ada
        return response()->json([
            'status' => true,
            'message' => 'data ditemukan',
            'data' =>  $datapeminjaman,
        ], 200);
    }

    public function riwayat(Request $request)
    {
        $keyword = $request->keyword;
        if (isset($keyword)) {
            $datapeminjaman = Peminjaman::join('users', 'peminjamen.user_id', '=', 'users.id')
                ->join('ruangans', 'peminjamen.id_ruangan', '=', 'ruangans.id')
                ->select(
                    'peminjamen.id',
                    'users.nama as nama_user',
                    'peminjamen.nama_lembaga',
                    'peminjamen.kegiatan',
                    Peminjaman::raw('DATE(peminjamen.tgl_mulai) as tgl_mulai'), // Mengambil tanggal saja
                    Peminjaman::raw('TIME(peminjamen.tgl_mulai) as jam_mulai'), // Mengambil waktu saja
                    Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
                    Peminjaman::raw('TIME(peminjamen.tgl_selesai) as jam_selesai'), // Mengambil waktu saja
                    'peminjamen.status',
                    'peminjamen.feedback',
                    'peminjamen.dokumen_pendukung',
                    'ruangans.nama as nama_ruangan',
                    'users.nim',
                    'users.email',
                    'users.telp'
                )
                ->where(function ($query) {
                    // Menampilkan data dengan status "submitted", "submited", dan "in progress"
                    $query->where('peminjamen.status', 'reject');

                    // Menampilkan data dengan status "completed" hanya jika kolom feedback kosong
                    $query->orWhere(function ($subQuery) {
                        $subQuery->where('peminjamen.status', 'completed')
                            ->whereNotNull('peminjamen.feedback');
                    });
                })
                ->where(function ($query) use ($keyword) {
                    $query->where('users.nama', 'LIKE', "%" . $keyword . "%")
                        ->orWhere('peminjamen.nama_lembaga', 'LIKE', "%" . $keyword . "%")
                        ->orWhere('peminjamen.kegiatan', 'LIKE', "%" . $keyword . "%")
                        ->orWhere('ruangans.nama', 'LIKE', "%" . $keyword . "%");
                })
                ->orderBy('peminjamen.tgl_mulai', 'desc')
                ->get();
        } else {
            //menampilkann data riwayat peminjaman seluruh user
            $datapeminjaman = Peminjaman::join('users', 'peminjamen.user_id', '=', 'users.id')
                ->join('ruangans', 'peminjamen.id_ruangan', '=', 'ruangans.id')
                ->select(
                    'peminjamen.id',
                    'users.nama as nama_user',
                    'peminjamen.nama_lembaga',
                    'peminjamen.kegiatan',
                    Peminjaman::raw('DATE(peminjamen.tgl_mulai) as tgl_mulai'), // Mengambil tanggal saja
                    Peminjaman::raw('TIME(peminjamen.tgl_mulai) as jam_mulai'), // Mengambil waktu saja
                    Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
                    Peminjaman::raw('TIME(peminjamen.tgl_selesai) as jam_selesai'), // Mengambil waktu saja
                    'peminjamen.status',
                    'peminjamen.feedback',
                    'peminjamen.dokumen_pendukung',
                    'ruangans.nama as nama_ruangan',
                    'users.nim',
                    'users.email',
                    'users.telp'
                )
                ->where(function ($query) {
                    // Menampilkan data dengan status "submitted", "submited", dan "in progress"
                    $query->where('peminjamen.status', 'reject');

                    // Menampilkan data dengan status "completed" hanya jika kolom feedback kosong
                    $query->orWhere(function ($subQuery) {
                        $subQuery->where('peminjamen.status', 'completed')
                            ->whereNotNull('peminjamen.feedback');
                    });
                })
                ->orderBy('peminjamen.tgl_mulai', 'desc')
                ->get();
        }

        //jika data kosong
        if ($datapeminjaman->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => isset($keyword) ? 'Data yang anda cari tidak ditemukan' : 'Belum ada data riwayat peminjaman',
                'data' => [],
            ], 404); // Use 404 status code for "not found"
        }

        //jika data ada
        return response()->json([
            'status' => true,
            'message' => 'data ditemukan',
            'data' =>  $datapeminjaman,
        ], 200);
    }


    public function KalenderPeminjaman()
    {
        $datapeminjaman = Peminjaman::join('ruangans', 'peminjamen.id_ruangan', '=', 'ruangans.id')
            ->whereIn('peminjamen.status', ['approved', 'in progress'])
            ->orderBy('peminjamen.tgl_mulai', 'asc')
            ->get();
        $events = $datapeminjaman->map(function ($event) {

            return [
                'title' => $event->kegiatan . "(" . $event->nama_lembaga . ")",
                'start' => $event->tgl_mulai,
                'end' => $event->tgl_selesai,
                'ruangan' => $event->nama,
                'className' => 'bg-primary',
            ];
        });

        return response()->json($events);
    }

    public function peminjaman()
    {
        $datapeminjaman = Peminjaman::with(['ruangan','user'])
        // ->whereIn('peminjamen.status', ['in progress','completed'])
        ->orderBy('id','desc')
        ->get();

        return response()->json([
            'status' => true,
            'message' => 'data ditemukan',
            'data' =>  $datapeminjaman,
        ], 200);
    }
}
