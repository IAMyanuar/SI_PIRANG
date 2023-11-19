<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;

class webPeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //menampilkan semua data
        $apiUrl = env('API_URL');
        $apiToken = session('api_token');
        $client = new Client();

        try {
            $url1 = $apiUrl."/api/peminjaman/submitted";
            $response1 = $client->request(
                'GET',
                $url1,
                ['headers' => [
                    'Authorization' => 'Bearer ' . $apiToken
                ],]
            );
            $conten1 = $response1->getBody()->getContents();
            $contenarray1 = json_decode($conten1, true);
            $datapeminjamansubmitted = $contenarray1['data'];
        } catch (\Throwable $th) {
            // return redirect()->back()->withErrors('error', 'Access Denied');
        }


        try {
            $url2 = $apiUrl."/api/peminjaman/approve";
            $response2 = $client->request(
                'GET',
                $url2,
                ['headers' => [
                    'Authorization' => 'Bearer ' . $apiToken
                ],]
            );
            $conten2 = $response2->getBody()->getContents();
            $contenarray2 = json_decode($conten2, true);
            $datapmjapprove = $contenarray2['data'];
        } catch (\Throwable $th) {
            // return redirect()->back()->withErrors('error', 'Access Denied');
        }


        try {
            $url3 = $apiUrl."/api/peminjaman/inprogress";
            $response3 = $client->request(
                'GET',
                $url3,
                ['headers' => [
                    'Authorization' => 'Bearer ' . $apiToken
                ],]
            );
            $conten3 = $response3->getBody()->getContents();
            $contenarray3 = json_decode($conten3, true);
            $datapmjinprogress = $contenarray3['data'];
        } catch (\Throwable $th) {
            // return redirect()->back()->withErrors('error', 'Access Denied');
        }

        return view('admin.acc_peminjaman', ['datapeminjamansubmitted' => $datapeminjamansubmitted, 'datapmjapprove' => $datapmjapprove, 'datapmjinprogress' => $datapmjinprogress]);
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
        //menambah data pengajuan ruangan
        $apiUrl = env('API_URL');
        $apiToken = session('api_token');
        $validatedData = $request->validate([
            'bukti_pendukung' => 'required',
            'fasilitas' => 'required',
            'foto' => 'required|image', // Jika Anda ingin memastikan bahwa 'foto' adalah berkas gambar.
        ]);
        try {
            $foto = $request->file('foto');
            $options = [
                'multipart' => [
                    [
                        'name' => 'nama',
                        'contents' => $validatedData['nama']
                    ],
                    [
                        'name' => 'fasilitas',
                        'contents' => $validatedData['fasilitas']
                    ],
                    [
                        'name' => 'foto',
                        'contents' => fopen($foto, 'r'),
                        'filename' => $foto->getClientOriginalName(),
                        'headers'  => [
                            'Content-Type' => '<Content-type header>'
                        ]
                    ]
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiToken,
                ],

            ];

            $client = new Client();
            $url = $apiUrl . "/api/peminjaman";
            $response = $client->request('POST', $url, $options);
            $response->getBody()->getContents();
            return redirect()->to('/admin/DataRuangan')
                ->with('success', 'ruangan ' . $validatedData['nama'] . ' berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('RuanganIsExist', 'ruangan ' . $validatedData['nama'] . ' Sudah ada.');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //menampilkan detail peminjaman berdasarkan id
        $apiUrl = env('API_URL');
        $apiToken = session('api_token');
        $client = new Client();
        try {
            $url = $apiUrl.'/api/peminjaman/' . $id;
            $response = $client->request(
                'GET',
                $url,
                ['headers' => [
                    'Authorization' => 'Bearer ' . $apiToken
                ],]
            );
            $conten = $response->getBody()->getContents();
            $contenarray = json_decode($conten, true);
            $datapeminjam = $contenarray['data'];
            return view('admin.detail_peminjaman', ['datapeminjam' => $datapeminjam]);
        } catch (\Throwable $th) {
            // return redirect()->back()->withErrors('error', 'Access Denied');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $apiUrl = env('API_URL');
            $apiToken = session('api_token');
            $datadiubah = [
                'Authorization' => 'Bearer ' . $apiToken,
                'status' => $request->input('status'),
                'feedback' => $request->input('feedback')
            ];


            // Kirim permintaan PUT ke API dengan status yang sesuai
            $client = new Client();
            $url = $apiUrl."/api/peminjaman/{$id}";
            $response = $client->request('PUT', $url, ['json' =>  $datadiubah, 'headers' => [
                'Authorization' => 'Bearer ' . $apiToken,
            ],]);

            // Periksa respons dari API
            if ($response->getStatusCode() === 201) {
                return redirect()->back()->with('success', 'Status berhasil di ubah');
            } else {
                return redirect()->back()->with('error', 'Gagal merubah status');
            }
        } catch (\Throwable $th) {
            // return redirect()->back()->withErrors('error', 'Access Denied');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



    public function unduhFile($id)
    {
        $apiUrl = env('API_URL');
        $client = new Client();
        $url = $apiUrl.'/api/unduhFileDokumen/' . $id;
        try {
            $response = $client->get($url);

            if ($response->getStatusCode() === 200) {
                // Mendapatkan body respons (file) dari API
                $fileData = $response->getBody()->getContents();

                // Menentukan header respons sesuai dengan header dari API
                $headers = $response->getHeaders();

                // Meneruskan file dari API ke pengguna
                return response($fileData, 200)->withHeaders($headers);
            } else {
                return redirect()->back()
                    ->with('info', 'tidak ada bukti pendukung');

            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('info', 'tidak ada bukti pendukung');

        }
    }
}
