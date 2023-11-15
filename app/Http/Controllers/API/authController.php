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
                'nim' => 'required',
                'nama' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'telp' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'prsoses validasi gagal',
                    'data' => $validator->errors()
                ], 422); // Menggunakan kode status 422 untuk Unprocessable Entity
            }
            $datauser->nim = $request->nim;
            $datauser->nama = $request->nama;
            $datauser->email = $request->email;
            $datauser->password = Hash::make($request->password);
            $datauser->telp =  $request->telp;
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

        if ($datauser = User::where('nim', $request->nim)->where('role_user', 'user')->first()) {
            $role = $datauser->role_user;
            $nama = $datauser->nama;
            $id_user = $datauser->id;
            return response()->json([
                'status' => true,
                'message' => 'login berhasil',
                'role' => $role,
                'id_user' => $id_user,
                'nama' => $nama,
                'token' => $datauser->createToken('api-product', ['access-user'])->plainTextToken
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
