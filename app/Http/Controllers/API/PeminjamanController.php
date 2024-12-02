<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePeminjamanRequest;
use App\Models\Fasilitas;
use App\Models\PeminjamanFasilitas;
use Doctrine\Inflector\Rules\English\Rules;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // menampilkan data peminjaman status terkirim
        $datapeminjaman = Peminjaman::join('users', 'peminjamen.user_id', '=', 'users.id')
            ->join('ruangans', 'peminjamen.id_ruangan', '=', 'ruangans.id')
            ->select(
                'peminjamen.id',
                'users.nama as nama_user',
                'peminjamen.nama_lembaga',
                'peminjamen.kegiatan',
                Peminjaman::raw('DATE(peminjamen.tgl_mulai) as tgl_mulai'), // Mengambil tanggal saja
                Peminjaman::raw('TO_CHAR(peminjamen.tgl_mulai, \'HH24:MI:SS\') as jam_mulai'), // Format waktu untuk PostgreSQL
                Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
                Peminjaman::raw('TO_CHAR(peminjamen.tgl_selesai, \'HH24:MI:SS\') as jam_selesai'), // Format waktu untuk PostgreSQL
                'peminjamen.status',
                'peminjamen.feedback',
                'peminjamen.dokumen_pendukung',
                'ruangans.nama as nama_ruangan',
                'users.nim',
                'users.email',
                'users.telp'
            )->where('peminjamen.status', '=', 'terkirim')
            ->get();

        // Mengambil ID peminjaman
        $peminjamanIDs = $datapeminjaman->pluck('id');

        //query peminjaman fasilitas dan fasilitas
        $datafasilitas = PeminjamanFasilitas::whereIn('id_peminjaman', $peminjamanIDs)
            ->leftJoin('fasilitas', 'peminjaman_fasilitas.id_fasilitas', '=', 'fasilitas.id')
            ->select('peminjaman_fasilitas.id', 'peminjaman_fasilitas.id_peminjaman', 'peminjaman_fasilitas.id_fasilitas', 'fasilitas.nama', 'peminjaman_fasilitas.jumlah')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'data ditemukan',
            'data' => [$datapeminjaman, $datafasilitas],
        ], 200);
    }



    public function storepeminjaman(Request $request)
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
            'id_fasilitas' => 'nullable',
            'id_fasilitas.*' => 'nullable',
            'jumlah' => 'nullable',
            'jumlah.*' => 'nullable',
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

        //penambahan code bug
        if ($tgl_mulai > $tgl_selesai) {
            return response()->json([
                'status' => false,
                'message' => 'prsoses ajukan peminjaman gagal karena waktu yang tidak falid',
            ], 400);
        }

        //cek apakah sudah ada yang meminjam ruangan tsb
        $CekPeminjaman = Peminjaman::where('id_ruangan', $ruangan)
            ->whereIn('status', ['disetujui', 'di prosess'])
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

        if (!$CekPeminjaman) {
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

            // Simpan data fasilitas yang diasosiasikan dengan peminjaman
            $id_fasilitas_array = explode('"', $request->id_fasilitas);
            $jumlah_array = explode('"', $request->jumlah);


            $errorMessages = [];
            $jmlFasilitasKurang = false;

            foreach ($id_fasilitas_array ?? [] as $key => $fasilitasId) {
                if (!is_null($fasilitasId)) { // Pastikan nilai tidak null
                    // Periksa apakah jumlah fasilitas yang diminta lebih besar dari yang tersedia
                    $jumlahDiminta = $jumlah_array[$key] ?? 0;
                    $fasilitas = Fasilitas::find($fasilitasId);
                    $jumlahTersedia = $fasilitas->jumlah;

                    // Jika jumlah fasilitas yang diminta lebih besar dari yang tersedia, tambahkan pesan kesalahan
                    if ($jumlahDiminta > $jumlahTersedia) {
                        $errorMessages[] = "Jumlah fasilitas '{$fasilitas->nama}' yang tersedia tidak mencukupi untuk dipinjam.";
                        $jmlFasilitasKurang = true;
                    }
                }
            }

            if ($jmlFasilitasKurang == false) {
                foreach ($id_fasilitas_array ?? [] as $key => $fasilitasId) {
                    if (!is_null($fasilitasId)) { // Pastikan nilai tidak null
                        // Periksa apakah jumlah fasilitas yang diminta lebih besar dari yang tersedia
                        $jumlahDiminta = $jumlah_array[$key] ?? 0;
                        $fasilitas = Fasilitas::find($fasilitasId);
                        $jumlahTersedia = $fasilitas->jumlah;

                        $detailPeminjaman = new PeminjamanFasilitas;
                        $detailPeminjaman->id_peminjaman = $datapeminjaman->id;
                        $detailPeminjaman->id_fasilitas = $fasilitasId;
                        $detailPeminjaman->jumlah = $jumlahDiminta;
                        $detailPeminjaman->save();
                    }
                }
            }

            // Jika ada pesan kesalahan, kembalikan respons dengan pesan kesalahan
            // ganti arayy menjadi string
            $errorString = implode("\n", $errorMessages);
            if (!empty($errorMessages)) {
                $peminjaman = Peminjaman::findOrFail($datapeminjaman->id);
                // Hapus peminjaman
                $peminjaman->delete();
                return response()->json([
                    'status' => false,
                    'message' => $errorString,
                    'errors' => $errorMessages
                ], 422);
            }

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
                Peminjaman::raw('TO_CHAR(peminjamen.tgl_mulai, \'HH24:MI:SS\') as jam_mulai'), // Format waktu untuk PostgreSQL
                Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
                Peminjaman::raw('TO_CHAR(peminjamen.tgl_selesai, \'HH24:MI:SS\') as jam_selesai'), // Format waktu untuk PostgreSQL
                'peminjamen.status',
                'peminjamen.feedback',
                'peminjamen.dokumen_pendukung',
                'ruangans.id as id_ruangan',
                'ruangans.nama as nama_ruangan',
                'users.nim',
                'users.email',
                'users.telp'
            )->find($id);

        $datafasilitas = PeminjamanFasilitas::where('id_peminjaman', $id)
            ->leftjoin('fasilitas', 'peminjaman_fasilitas.id_fasilitas', '=', 'fasilitas.id')
            ->select('peminjaman_fasilitas.id', 'peminjaman_fasilitas.id_peminjaman', 'peminjaman_fasilitas.id_fasilitas', 'fasilitas.nama', 'peminjaman_fasilitas.jumlah')
            ->get();

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
            'data' => [$datapeminjaman, $datafasilitas]
        ], 200);
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

        //perbaikan bug
        if ($request->input('status') == 'ditolak') {
            $rules['status'] = 'required';
            $rules['feedback'] = 'required';
        } else {
            $rules = [
                'status' => 'required',
            ];
        }




        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Proses ubah status peminjaman gagal',
                'data' => $validator->errors(),
                'request' => $request->all(),
            ], 400);
        }

        //status untuk menangani bug
        $currentDate = Carbon::now()->format('Y-m-d H:i:s');
        $tanggalMulai = Carbon::parse($datapeminjaman->tgl_mulai)->format('Y-m-d H:i:s');
        // $tanggalMulai = Carbon::parse($request->tgl_mulai);
        if ($request->status == 'disetujui' && Carbon::parse($currentDate)->greaterThanOrEqualTo($tanggalMulai)) {
            return response()->json([
                'message' => 'Status peminjaman tidak dapat diubah menjadi disetujui karena sudah melewati tanggal mulai acara.',
                $currentDate,
                $tanggalMulai
            ], 400);
        }

        //ketika status di prosess maka
        if ($request->status == 'di prosess') {
            $pmjfasilitas = PeminjamanFasilitas::where('id_peminjaman', $id)->get();
            foreach ($pmjfasilitas as $peminjaman_fasilitas) {
                $id_fasilitas = $peminjaman_fasilitas->id_fasilitas;
                $jumlah_peminjaman = $peminjaman_fasilitas->jumlah;

                $fasilitas = Fasilitas::find($id_fasilitas);
                $fasilitas->jumlah -= $jumlah_peminjaman;
                $fasilitas->save();
            }
        }

        //ketika status selesai
        if ($request->status == 'selesai') {
            $pmjfasilitas = PeminjamanFasilitas::where('id_peminjaman', $id)->get();
            foreach ($pmjfasilitas as $peminjaman_fasilitas) {
                $id_fasilitas = $peminjaman_fasilitas->id_fasilitas;
                $jumlah_peminjaman = $peminjaman_fasilitas->jumlah;

                $fasilitas = Fasilitas::find($id_fasilitas);
                $fasilitas->jumlah += $jumlah_peminjaman;
                $fasilitas->save();
            }
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
        $peminmjamanfasilitas = PeminjamanFasilitas::where('id_peminjaman', $id);
        $peminmjamanfasilitas->delete();
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
                Peminjaman::raw('TO_CHAR(peminjamen.tgl_mulai, \'HH24:MI:SS\') as jam_mulai'), // Format waktu untuk PostgreSQL
                Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
                Peminjaman::raw('TO_CHAR(peminjamen.tgl_selesai, \'HH24:MI:SS\') as jam_selesai'), // Format waktu untuk PostgreSQL
                'peminjamen.status',
                'peminjamen.feedback',
                'peminjamen.dokumen_pendukung',
                'ruangans.nama as nama_ruangan',
                'users.nim',
                'users.email',
                'users.telp',
            )->where('peminjamen.status', '=', 'disetujui')
            ->get();

        // Mengambil ID peminjaman
        $peminjamanIDs = $datapeminjaman->pluck('id');

        //query peminjaman fasilitas dan fasilitas
        $datafasilitas = PeminjamanFasilitas::whereIn('id_peminjaman', $peminjamanIDs)
            ->leftJoin('fasilitas', 'peminjaman_fasilitas.id_fasilitas', '=', 'fasilitas.id')
            ->select('peminjaman_fasilitas.id', 'peminjaman_fasilitas.id_peminjaman', 'peminjaman_fasilitas.id_fasilitas', 'fasilitas.nama', 'peminjaman_fasilitas.jumlah')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'data ditemukan',
            'data' => [$datapeminjaman, $datafasilitas],
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
                Peminjaman::raw('TO_CHAR(peminjamen.tgl_mulai, \'HH24:MI:SS\') as jam_mulai'), // Format waktu untuk PostgreSQL
                Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
                Peminjaman::raw('TO_CHAR(peminjamen.tgl_selesai, \'HH24:MI:SS\') as jam_selesai'), // Format waktu untuk PostgreSQL
                'peminjamen.status',
                'peminjamen.feedback',
                'peminjamen.dokumen_pendukung',
                'ruangans.nama as nama_ruangan',
                'users.nim',
                'users.email',
                'users.telp'
            )
            ->where('peminjamen.status', '=', 'di prosess')
            ->get();

        // Mengambil ID peminjaman
        $peminjamanIDs = $datapeminjaman->pluck('id');

        //query peminjaman fasilitas dan fasilitas
        $datafasilitas = PeminjamanFasilitas::whereIn('id_peminjaman', $peminjamanIDs)
            ->leftJoin('fasilitas', 'peminjaman_fasilitas.id_fasilitas', '=', 'fasilitas.id')
            ->select('peminjaman_fasilitas.id', 'peminjaman_fasilitas.id_peminjaman', 'peminjaman_fasilitas.id_fasilitas', 'fasilitas.nama', 'peminjaman_fasilitas.jumlah')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'data ditemukan',
            'data' => [$datapeminjaman, $datafasilitas],
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
                Peminjaman::raw('TO_CHAR(peminjamen.tgl_mulai, \'HH24:MI:SS\') as jam_mulai'), // Format waktu untuk PostgreSQL
                Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
                Peminjaman::raw('TO_CHAR(peminjamen.tgl_selesai, \'HH24:MI:SS\') as jam_selesai'), // Format waktu untuk PostgreSQL
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
                // Menampilkan data dengan status "terkirim", "submited", dan "di prosess"
                $query->whereIn('peminjamen.status', ['terkirim', 'disetujui', 'di prosess']);

                // Menampilkan data dengan status "selesai" hanya jika kolom feedback kosong
                $query->orWhere(function ($subQuery) {
                    $subQuery->where('peminjamen.status', 'selesai')
                        ->whereNull('peminjamen.feedback');
                });
            })
            ->orderBy('peminjamen.id', 'desc')
            ->get();

        // Mengambil ID peminjaman
        $peminjamanIDs = $datapeminjaman->pluck('id');

        //query peminjaman fasilitas dan fasilitas
        $datafasilitas = PeminjamanFasilitas::whereIn('id_peminjaman', $peminjamanIDs)
            ->leftJoin('fasilitas', 'peminjaman_fasilitas.id_fasilitas', '=', 'fasilitas.id')
            ->select('peminjaman_fasilitas.id', 'peminjaman_fasilitas.id_peminjaman', 'peminjaman_fasilitas.id_fasilitas', 'fasilitas.nama', 'peminjaman_fasilitas.jumlah')
            ->get();


        if (empty($datapeminjaman)) {
            return response()->json([
                'status' => false,
                'message' => 'data belum ada',
                'data' => null,
            ], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'data ditemukan',
            'data' => [$datapeminjaman, $datafasilitas],
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
            'feedback' => 'nullable',
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
                    Peminjaman::raw('TO_CHAR(peminjamen.tgl_mulai, \'HH24:MI:SS\') as jam_mulai'), // Format waktu untuk PostgreSQL
                    Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
                    Peminjaman::raw('TO_CHAR(peminjamen.tgl_selesai, \'HH24:MI:SS\') as jam_selesai'), // Format waktu untuk PostgreSQL
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
                    // Menampilkan data dengan status "terkirim", "submited", dan "di prosess"
                    $query->where('peminjamen.status', 'ditolak');

                    // Menampilkan data dengan status "selesai" hanya jika kolom feedback kosong
                    $query->orWhere(function ($subQuery) {
                        $subQuery->where('peminjamen.status', 'selesai')
                            ->whereNotNull('peminjamen.feedback');
                    });
                })
                ->where(function ($query) use ($keyword) {
                    $query->where('users.nama', 'ILIKE', "%" . $keyword . "%")
                        ->orWhere('peminjamen.nama_lembaga', 'ILIKE', "%" . $keyword . "%")
                        ->orWhere('peminjamen.kegiatan', 'ILIKE', "%" . $keyword . "%")
                        ->orWhere('ruangans.nama', 'ILIKE', "%" . $keyword . "%")
                        //perbaikan bug
                        ->orWhereRaw("EXTRACT(MONTH FROM peminjamen.tgl_mulai) = ?", [$keyword]);
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
                    Peminjaman::raw('TO_CHAR(peminjamen.tgl_mulai, \'HH24:MI:SS\') as jam_mulai'), // Format waktu untuk PostgreSQL
                    Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
                    Peminjaman::raw('TO_CHAR(peminjamen.tgl_selesai, \'HH24:MI:SS\') as jam_selesai'), // Format waktu untuk PostgreSQL
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
                    // Menampilkan data dengan status "terkirim", "submited", dan "di prosess"
                    $query->where('peminjamen.status', 'ditolak');

                    // Menampilkan data dengan status "selesai" hanya jika kolom feedback kosong
                    $query->orWhere(function ($subQuery) {
                        $subQuery->where('peminjamen.status', 'selesai')
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
                    Peminjaman::raw('TO_CHAR(peminjamen.tgl_mulai, \'HH24:MI:SS\') as jam_mulai'), // Format waktu untuk PostgreSQL
                    Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
                    Peminjaman::raw('TO_CHAR(peminjamen.tgl_selesai, \'HH24:MI:SS\') as jam_selesai'), // Format waktu untuk PostgreSQL
                    'peminjamen.status',
                    'peminjamen.feedback',
                    'peminjamen.dokumen_pendukung',
                    'ruangans.nama as nama_ruangan',
                    'users.nim',
                    'users.email',
                    'users.telp'
                )
                ->where(function ($query) {
                    // Menampilkan data dengan status "terkirim", "submited", dan "di prosess"
                    $query->where('peminjamen.status', 'ditolak');

                    // Menampilkan data dengan status "selesai" hanya jika kolom feedback kosong
                    $query->orWhere(function ($subQuery) {
                        $subQuery->where('peminjamen.status', 'selesai');
                        // ->whereNotNull('peminjamen.feedback');
                    });
                })
                ->where(function ($query) use ($keyword) {
                    $query->where('users.nama', 'ILIKE', "%" . $keyword . "%")
                        ->orWhere('peminjamen.nama_lembaga', 'ILIKE', "%" . $keyword . "%")
                        ->orWhere('peminjamen.kegiatan', 'ILIKE', "%" . $keyword . "%")
                        ->orWhere('ruangans.nama', 'ILIKE', "%" . $keyword . "%")
                        //perbaikan bug
                        ->orWhereRaw("EXTRACT(MONTH FROM peminjamen.tgl_mulai) = ?", [$keyword]);
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
                    Peminjaman::raw('TO_CHAR(peminjamen.tgl_mulai, \'HH24:MI:SS\') as jam_mulai'), // Format waktu untuk PostgreSQL
                    Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
                    Peminjaman::raw('TO_CHAR(peminjamen.tgl_selesai, \'HH24:MI:SS\') as jam_selesai'), // Format waktu untuk PostgreSQL
                    'peminjamen.status',
                    'peminjamen.feedback',
                    'peminjamen.dokumen_pendukung',
                    'ruangans.nama as nama_ruangan',
                    'users.nim',
                    'users.email',
                    'users.telp'
                )
                ->where(function ($query) {
                    // Menampilkan data dengan status "terkirim", "submited", dan "di prosess"
                    $query->where('peminjamen.status', 'ditolak');

                    // Menampilkan data dengan status "selesai" hanya jika kolom feedback kosong
                    $query->orWhere(function ($subQuery) {
                        $subQuery->where('peminjamen.status', 'selesai');
                        // ->whereNotNull('peminjamen.feedback');
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
            ->whereIn('peminjamen.status', ['disetujui', 'di prosess'])
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
        $datapeminjaman = Peminjaman::with(['ruangan', 'user'])
            // ->whereIn('peminjamen.status', ['di prosess','selesai'])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'data ditemukan',
            'data' =>  $datapeminjaman,
        ], 200);
    }

    public function downloadPeminjamanByMonth(Request $request)
    {
        // Validasi input bulan (format: YYYY-MM)
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        // Ambil bulan dari request
        $tanggalMulai = $request->input('start_date');
        $tanggalSelesai = $request->input('end_date');
        //penambahan code bug
        if ($tanggalMulai > $tanggalSelesai) {
            return response()->json([
                'status' => false,
                'message' => 'waktu yang tidak falid',
            ], 400);
        }


        // Query data berdasarkan bulan
        $datapeminjaman = Peminjaman::join('users', 'peminjamen.user_id', '=', 'users.id')
            ->join('ruangans', 'peminjamen.id_ruangan', '=', 'ruangans.id')
            ->select(
                'peminjamen.id',
                'users.nama as nama_user',
                'peminjamen.nama_lembaga',
                'peminjamen.kegiatan',
                Peminjaman::raw('DATE(peminjamen.tgl_mulai) as tgl_mulai'), // Mengambil tanggal saja
                Peminjaman::raw('TO_CHAR(peminjamen.tgl_mulai, \'HH24:MI:SS\') as jam_mulai'), // Format waktu untuk PostgreSQL
                Peminjaman::raw('DATE(peminjamen.tgl_selesai) as tgl_selesai'), // Mengambil tanggal saja
                Peminjaman::raw('TO_CHAR(peminjamen.tgl_selesai, \'HH24:MI:SS\') as jam_selesai'), // Format waktu untuk PostgreSQL
                'peminjamen.status',
                'peminjamen.feedback',
                'peminjamen.dokumen_pendukung',
                'ruangans.nama as nama_ruangan',
                'users.nim',
                'users.email',
                'users.telp'
            )
            ->where(function ($query) {
                // Menampilkan data dengan status "ditolak" dan "selesai" (jika feedback kosong)
                $query->where('peminjamen.status', 'ditolak')
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('peminjamen.status', 'selesai');
                        // ->whereNotNull('peminjamen.feedback');
                    });
            })

            // ->whereMonth('peminjamen.tgl_mulai', '=', date('m', strtotime($bulan)))
            // ->whereYear('peminjamen.tgl_mulai', '=', date('Y', strtotime($bulan)))
            ->whereBetween('peminjamen.tgl_mulai', [$tanggalMulai, $tanggalSelesai])
            ->orderBy('peminjamen.tgl_mulai', 'desc')
            ->get();

        // Load data ke view untuk PDF
        $pdf = PDF::loadView('layout.riwayat_pdf', compact('datapeminjaman'))->setPaper('a3');

        // Unduh PDF dengan nama file yang sesuai bulan
        return $pdf->download('peminjaman_' . $tanggalMulai . '-' . $tanggalSelesai . '.pdf');
    }
}
