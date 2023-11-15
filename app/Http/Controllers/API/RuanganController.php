<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class RuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //menampilkan semua data ruangan
        $data = Ruangan::get();
        return response()->json([
            'status' => true,
            'message' => 'data ditemukan',
            'data' => $data,
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
        //
        try {
        $dataruangan = new Ruangan;
        $rules = [
            'nama' => 'required',
            'fasilitas' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'prsoses tambah ruangan gagal',
                'data' => $validator->errors()
            ], 422);
        }

        $foto = $request->file('foto');
        $namafoto = time() . '.' . $foto->getClientOriginalExtension();
        $foto->move(public_path('assets/images/ruangan'), $namafoto);

        $dataruangan->nama = $request->nama;
        $dataruangan->fasilitas = $request->fasilitas;
        $dataruangan->foto = $namafoto;
        $dataruangan->save();

        return response()->json([
            'status' => true,
            'message' => 'prsoses tambah ruangan berhasil',
        ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Ruangan sudah ada',
            ], 409);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //menampilkan data berdasarkan id
        $data = Ruangan::find($id);
        if(empty($data)){
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Data dengan id: '.$id.' berhasil temukan',
            'data' => $data
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        // Temukan data ruangan berdasarkan ID
        $dataruangan = Ruangan::find($id);

        if (empty($dataruangan)) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        // Validasi input
        $rules = [
            'nama' => 'required',
            'fasilitas' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Proses ubah data ruangan gagal',
                'data' => $validator->errors(),
                'request' => $request->all(),
            ], 400);
        }

        if ($request->hasFile('foto')) {
            // Hapus file gambar lama jika ada
            if (!empty($dataruangan->foto)) {
                $oldPhotoPath = public_path('assets/images/ruangan/' . $dataruangan->foto);
                if (File::exists($oldPhotoPath)) {
                    File::delete($oldPhotoPath);
                }
            }

            // Simpan gambar baru
            $foto = $request->file('foto');
            $namafoto = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('assets/images/ruangan'), $namafoto);
            $dataruangan->foto = $namafoto;
        }

        // Update data ruangan hanya jika validasi berhasil
        $dataruangan->nama = $request->nama;
        $dataruangan->fasilitas = $request->fasilitas;
        $dataruangan->save();

        return response()->json([
            'status' => true,
            'message' => 'Proses ubah ruangan berhasil',
            'request' => $request
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
    }
}
