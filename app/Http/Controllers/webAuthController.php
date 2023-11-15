<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Auth\Events\Logout;
use GuzzleHttp\Exception\RequestException;

class webAuthController extends Controller
{
    public function viewLogin()
    {
        return view('layout.login');
    }
    public function Login(Request $request)
    {
        try {
            $apiUrl = env('API_URL');
            $getdata = [
                'nim' => $request->input('nim'),
                'password' => $request->input('password')
            ];

            $client = new Client();
            $url = $apiUrl . "/api/login";
            $response = $client->request('POST', $url, ['json' =>  $getdata,]);
            $conten = $response->getBody()->getContents();
            $contenarray = json_decode($conten, true);


            if ($contenarray['status'] == true && $contenarray['role'] == "user") {
                $token = $contenarray['token'];
                $id_user = $contenarray['id_user'];
                $nama = $contenarray['nama'];
                session(['nama' => $nama]);
                session(['id_user' => $id_user]);
                session(['api_token' => $token]);
                return redirect('/dashboard');
            }

            if ($contenarray['status'] == true && $contenarray['role'] == "admin") {
                $token = $contenarray['token'];
                $id_user = $contenarray['id_user'];
                $nama = $contenarray['nama'];
                session(['nama' => $nama]);
                session(['id_user' => $id_user]);
                session(['api_token' => $token]);
                return redirect('/admin/dashboard');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Username Or Password Incorrect']);
        }
    }


    public function viewRegister()
    {
        return view('layout.register');
    }
    public function Register(Request $request)
    {
        $apiUrl = env('API_URL');
        $validatedData = $request->validate([
            'nim' => 'required',
            'nama' => 'required',
            'email' => 'required',
            'password' => 'required',
            'telp' => 'required',
        ]);

        try {
            $options = [
                'multipart' => [
                    [
                        'name' => 'nim',
                        'contents' => $validatedData['nim']
                    ],
                    [
                        'name' => 'nama',
                        'contents' => $validatedData['nama']
                    ],
                    [
                        'name' => 'email',
                        'contents' => $validatedData['email']
                    ],
                    [
                        'name' => 'password',
                        'contents' => $validatedData['password']
                    ],
                    [
                        'name' => 'telp',
                        'contents' => $validatedData['telp']
                    ]
                ]
            ];

            $client = new Client();
            $url = $apiUrl . "/api/registerUser";
            $response = $client->request('POST', $url, $options);
            $conten = $response->getBody()->getContents();
            return redirect()->to('/')
                ->with('success', 'Pendaftaran Berhasil');

        } catch (RequestException $e) {
            $response = $e->getResponse();
            $conten = $response->getBody()->getContents();
            $contenarray = json_decode($conten, true);
            return redirect()->back()
                ->with('error', $contenarray['message']);
        }
    }

    public function Logout()
    {
        // Mendapatkan token dari sesi
        $apiUrl = env('API_URL');
        $apiToken = session('api_token');

        // Mengirim permintaan POST ke API logout
        $client = new Client();
        $url = $apiUrl . "/api/logout"; // Ganti dengan URL logout API Anda
        $response = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiToken,
            ],
        ]);

        if ($response->getStatusCode() === 200) {
            // Logout berhasil, hapus token dari sesi
            session()->forget('api_token');
            return redirect('/')->with('success', 'Anda telah berhasil logout.');
        } else {
            // Handle kesalahan logout jika diperlukan
            return redirect()->back()->with('error', 'Gagal logout.');
        }
    }
}
