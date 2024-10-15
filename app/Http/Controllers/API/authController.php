<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class authController extends Controller
{
    public function RegisterUser(Request $request)
    {
        try {
            $datauser = new User();
            $rules = [
                'nim' => 'required|string|max:13',
                'nama' => 'required|string|max:40',
                'email' => 'required|email|max:64|unique:users,email',
                'password' => 'required|string|min:8|max:70|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
                'telp' => 'required|numeric|digits_between:10,15',
                'foto_bwp' => 'required|image|mimes:jpeg,png,jpg'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'prsoses validasi gagal',
                    'cekdata' => $request->all(),
                    'data' => $validator->errors()
                ], 422); // Menggunakan kode status 422 untuk Unprocessable Entity
            }

            // Cek apakah sudah ada user dengan nim atau email yang sama dan status_user selain "belum_dikonfirmasi"
            $existingUser = User::where(function ($query) use ($request) {
                $query->where('nim', $request->nim)
                    ->orWhere('telp', $request->telp) // Perbaikan orWhare menjadi orWhere
                    ->orWhere('email', $request->email);
            })->where('status_user', '!=', 'tidak_dikonfirmasi')
                ->first();

            if ($existingUser) {
                // Cek kolom mana yang menyebabkan konflik
                if ($existingUser->nim == $request->nim) {
                    return response()->json([
                        'status' => false,
                        'message' => 'NIM ' . $existingUser->nim . ' sudah terdaftar dan statusnya: ' . $existingUser->status_user,
                    ], 409); // Conflict
                }

                if ($existingUser->email == $request->email) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Email ' . $existingUser->email . ' sudah terdaftar dan statusnya: ' . $existingUser->status_user,
                    ], 409); // Conflict
                }

                if ($existingUser->telp == $request->telp) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Nomor telepon ' . $existingUser->telp . ' sudah terdaftar dan statusnya: ' . $existingUser->status_user,
                    ], 409); // Conflict
                }
            }

            $foto = $request->file('foto_bwp');
            $namabwp = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('assets/images/foto_bwp/'), $namabwp);

            $datauser->nim = $request->nim;
            $datauser->nama = $request->nama;
            $datauser->email = $request->email;
            $datauser->password = Hash::make($request->password);
            $datauser->telp = $request->telp;
            $datauser->foto_bwp = $namabwp;
            $datauser->save();

            return response()->json([
                'status' => true,
                'message' => 'berhasil memasukan data baru',
            ], 201); // Menggunakan kode status 201 Created

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                // Extract the column name causing the error
                $columnName = str_replace("Duplicate entry '", "", $e->errorInfo[2]);
                $columnName = substr($columnName, 0, strpos($columnName, "'"));

                return response()->json([
                    'status' => false,
                    'message' => 'Gagal memasukan data baru. ' . ucfirst($columnName) . ' sudah terdaftar.',
                ], 409); // Menggunakan kode status 409 Conflict
            }
        }
    }

    public function listDataUser()
    {
        //menampilkan semua data fasilitas
        $data = User::where('status_user', '=', 'belum_dikonfirmasi')->get();
        for ($i = 0; $i < $data->count(); $i++) {
            $data[$i]['foto_bwp'] = url('assets/images/foto_bwp/' . $data[$i]['foto_bwp']);
        }
        return response()->json([
            'status' => true,
            'message' => 'data ditemukan',
            'data' => $data,
        ], 200);
    }

    public function confirmRegister(Request $request, $id)
    {
        $rules = [
            'status_user' => 'required',
        ];
        $datauser = User::find($id);

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'konfirmasi user gagal',
                'data' => $validator->errors()
            ], 400);
        }

        $datauser->status_user = $request->status_user;
        $datauser->save();
        return response()->json([
            'status' => true,
            'message' => 'berhasil mengonfirmasi user baru',
        ], 201);
    }

    public function showUser($id)
    {
        //menampilkan data berdasarkan id
        $data_user = User::find($id);
        $data_user['foto'] = url('assets/foto_bwp' . $data_user['foto_bwp']);

        if (empty($datadata_user)) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Data dengan id: ' . $id . ' berhasil temukan',
            'data' => $datadata_user
        ], 200);
    }

    public function Login(Request $request)
    {
        $rules = [
            'nim' => 'required',
            'password' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'login gagal',
                'data' => $validator->errors()
            ], 400); // Menggunakan status 400 Bad Request untuk kesalahan validasi
        }

        if (!Auth::attempt($request->only(['nim', 'password']))) {
            return response()->json([
                'status' => false,
                'message' => 'nim atau password salah',
            ],  401); // Menggunakan status 401 Unauthorized untuk kegagalan autentikasi
        }

        if ($datauser = User::where('nim', $request->nim)->where('role_user', 'admin')->first()) {
            $role = $datauser->role_user;
            $nama = $datauser->nama;
            $id_user = $datauser->id;
            return response()->json([
                'status' => true,
                'message' => 'login berhasil',
                'role' => $role,
                'id_user' => $id_user,
                'nama' => $nama,
                'token' => $datauser->createToken('api-product', ['access-admin'])->plainTextToken
            ], 200);
        }

        if ($datauser = User::where('nim', $request->nim)->where('role_user', 'peminjam')->first()) {
            $role = $datauser->role_user;
            $nama = $datauser->nama;
            $id_user = $datauser->id;
            return response()->json([
                'status' => true,
                'message' => 'login berhasil',
                'role' => $role,
                'id_user' => $id_user,
                'nama' => $nama,
                'token' => $datauser->createToken('api-product', ['access-peminjam'])->plainTextToken
            ], 200);
        }
    }

    public function Logout(Request $request)
    {
        $user = Auth::User();
        if ($user) {
            $user->tokens->each(function ($token, $key) {
                $token->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Berhasil logout.',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal logout. Pengguna tidak ditemukan.',
            ], 404); // Gunakan kode status 404 Not Found jika pengguna tidak ditemukan.
        }
    }
}
