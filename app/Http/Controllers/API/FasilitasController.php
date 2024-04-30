<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class FasilitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //menampilkan semua data fasilitas
        $data = Fasilitas::get();
        for ($i = 0; $i < $data->count(); $i++) {
            $data[$i]['foto'] = url('assets/images/fasilitas/' . $data[$i]['foto']);
        }
        return response()->json([
            'status' => true,
            'message' => 'data ditemukan',
            'data' => $data,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $datafasilitas = new Fasilitas;
            $rules = [
                'nama' => 'required',
                'foto' => 'required|image|mimes:jpeg,png,jpg,gif',
                'jumlah' => 'required',
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
            $foto->move(public_path('assets/images/fasilitas'), $namafoto);

            $datafasilitas->nama = $request->nama;
            $datafasilitas->foto = $namafoto;
            $datafasilitas->jumlah = $request->jumlah;
            $datafasilitas->save();

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
    public function show(string $id)
    {
        //menampilkan data berdasarkan id
        $data = Fasilitas::find($id);
        $data['foto'] = url('assets/images/fasilitas/' . $data['foto']);

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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $datafasilitas = Fasilitas::find($id);

        if (empty($datafasilitas)) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        // Validasi input
        $rules = [
            'nama' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'jumlah' => 'required',
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
            if (!empty($datafasilitas->foto)) {
                $oldPhotoPath = public_path('assets/images/fasilitas/' . $datafasilitas->foto);
                if (File::exists($oldPhotoPath)) {
                    File::delete($oldPhotoPath);
                }
            }

            // Simpan gambar baru
            $foto = $request->file('foto');
            $namafoto = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('assets/images/fasilitas'), $namafoto);
            $datafasilitas->foto = $namafoto;
        }

        // Update data ruangan hanya jika validasi berhasil
        $datafasilitas->nama = $request->nama;
        $datafasilitas->jumlah = $request->jumlah;
        $datafasilitas->save();

        return response()->json([
            'status' => true,
            'message' => 'Proses ubah ruangan berhasil',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
