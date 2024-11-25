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


            if ($contenarray['status'] == true && $contenarray['role'] == "peminjam") {
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
        // } catch (RequestException $e) {
        //     $response = $e->getResponse();
        //     $conten = $response->getBody()->getContents();
        //     $contenarray = json_decode($conten, true);

        //     return redirect()->back()
        //         ->withErrors(['error' => $contenarray['message'].json_encode($contenarray['data'])]);
        // }
    }


    public function viewRegister()
    {
        return view('layout.register');
    }
    public function Register(Request $request)
    {
        $apiUrl = env('API_URL');
        $messages = [
            'password.required' => 'Password harus diisi.',
            'password.regex' => 'Password harus mengandung setidaknya satu huruf kecil, satu huruf besar, dan satu angka.',
            'password.min' => 'Password harus memiliki minimal 8 karakter.',
            'password.max' => 'Password tidak boleh lebih dari 70 karakter.',
            'password.string' => 'Password harus berupa string.',
            'foto_bwp.required' => 'Foto harus diupload.',
            'foto_bwp.image' => 'File yang diupload harus berupa gambar.',
            'foto_bwp.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'telp.required' => 'Nomor telepon harus diisi.',
            'telp.numeric' => 'Nomor telepon harus berupa angka.',
            'telp.digits_between' => 'Nomor telepon harus terdiri dari :min hingga :max digit.',
            'nim.required' => 'NIM harus diisi.',
            'nim.numeric' => 'NIM harus berupa angka.',
            'nim.digits_between' => 'NIM harus terdiri dari :min hingga :max digit.',
            'nama.required' => 'Nama harus diisi.',
            'nama.string' => 'Nama harus berupa string.',
            'nama.max' => 'Nama tidak boleh lebih dari :max karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email tidak boleh lebih dari :max karakter.',
            'email.unique' => 'Email sudah terdaftar.',
        ];
        $validatedData = $request->validate([
            'nim' => 'required|numeric|digits_between:8,13',
            'nama' => ['required', 'string', 'max:30', 'min:3', 'regex:/^[a-zA-Z\s]*$/'],
            'email' => 'required|email|min:5|max:64',
            'password' => 'required|string|min:8|max:70|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
            'telp' => 'required|numeric|digits_between:10,15',
            'foto_bwp' => 'required|image|mimes:jpeg,png,jpg'
        ], $messages);



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
                    ],

                ]
            ];
            if ($request->hasFile('foto_bwp')) {
                $foto = $request->file('foto_bwp');
                // Tambahkan 'foto' ke $options jika ada
                $options['multipart'][] = [
                    'name' => 'foto_bwp',
                    'contents' => fopen($foto->getPathname(), 'r'),
                    'filename' => $foto->getClientOriginalName(),
                    'headers' => [
                        'Content-Type' => '<Content-type header>'
                    ]
                ];
            }

            $client = new Client();
            $url = $apiUrl . "/api/registerUser";
            $response = $client->request('POST', $url, $options);
            $conten = $response->getBody()->getContents();
            return redirect()->to('/')
                ->with('success', 'Pendaftaran Berhasil, Silahkan Tunggu Akun Anda Di Konfirmasi');
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $conten = $response->getBody()->getContents();
            $contenarray = json_decode($conten, true);
            return redirect()->back()
                ->with('error', $contenarray['message']);
        }
    }

    public function viewConfirmRegister()
    {
        $apiUrl = env('API_URL');
        try {
            $apiToken = session('api_token');
            $client = new Client();
            $url = $apiUrl . "/api/user";
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiToken
                ],
            ]);
            $conten = $response->getBody()->getContents();
            $contenarray = json_decode($conten, true);
            $data = $contenarray['data'];
            return view('admin.konfirmasi_user_baru', ['data' => $data]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function confirmRegister(Request $request, $id)
    {
        try {
            $apiUrl = env('API_URL');
            $apiToken = session('api_token');
            $datadiubah = [
                'Authorization' => 'Bearer ' . $apiToken,
                'status_user' => $request->input('status_user'),
            ];


            // Kirim permintaan PUT ke API dengan status yang sesuai
            $client = new Client();
            $url = $apiUrl . "/api/confirmregister/{$id}";
            $response = $client->request('PATCH', $url, ['json' =>  $datadiubah, 'headers' => [
                'Authorization' => 'Bearer ' . $apiToken,
            ],]);

            // Periksa respons dari API
            if ($response->getStatusCode() === 201) {
                return redirect()->back()->with('success', 'Status User berhasil di ubah');
            } else {
                return redirect()->back()->with('error', 'Gagal merubah status User');
            }
        } catch (\Throwable $th) {
            // return redirect()->back()->withErrors('error', 'Access Denied');
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
