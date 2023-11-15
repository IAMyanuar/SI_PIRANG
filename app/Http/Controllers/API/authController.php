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
                ], 401);
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
            ], 200);

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                // Extract the column name causing the error
                $columnName = str_replace("Duplicate entry '", "", $e->errorInfo[2]);
                $columnName = substr($columnName, 0, strpos($columnName, "'"));

                return response()->json([
                    'status' => false,
                    'message' => 'Gagal memasukan data baru. ' . ucfirst($columnName) . ' sudah terdaftar.',
                ], 400);
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
            ], 401);
        }

        if (!Auth::attempt($request->only(['nim', 'password']))) {
            return response()->json([
                'status' => false,
                'message' => 'nim atau password salah',
            ], 401);
        }

        if ($datauser = User::where('nim', $request->nim)->where('role_user', 'admin')->first()) {
            $role = $datauser->role_user;
            $nama = $datauser->nama;
            return response()->json([
                'status' => true,
                'message' => 'login berhasil',
                'role' => $role,
                'nama' => $nama,
                'token' => $datauser->createToken('api-product', ['access-admin'])->plainTextToken
            ], 200);
        }

        if ($datauser = User::where('nim', $request->nim)->where('role_user', 'user')->first()) {
            $role = $datauser->role_user;
            $nama = $datauser->nama;
            return response()->json([
                'status' => true,
                'message' => 'login berhasil',
                'role' => $role,
                'nama' => $nama,
                'token' => $datauser->createToken('api-product', ['access-user'])->plainTextToken
            ], 200);
        }
    }

    public function Logout(Request $request)
    {
        $user = Auth::User();
        $user->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json([
            'status' => true,
            'message' => 'berhasil logout'
        ], 200);
    }
}
