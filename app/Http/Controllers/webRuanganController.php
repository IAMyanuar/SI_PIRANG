<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;


class webRuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        //menampilkan semua data
        $apiUrl = env('API_URL');
        try {
            $apiToken = session('api_token');
            $client = new Client();
            $url = $apiUrl . "/api/ruangan";
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiToken
                ],
            ]);
            $conten = $response->getBody()->getContents();
            $contenarray = json_decode($conten, true);
            $data = $contenarray['data'];
            return view('admin.data_ruangan', ['data' => $data]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.tambah_ruangan');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //tambah ruangan
        $apiUrl = env('API_URL');
        $apiToken = session('api_token');
        $validatedData = $request->validate([
            'nama' => 'required',
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
            $url = $apiUrl . "/api/tambahruangan";
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //menampilkan data ruangan berdasarkan id
        try {
            $apiUrl = env('API_URL');
            $apiToken = session('api_token');
            $client = new Client();
            $url = $apiUrl . "/api/ruangan/$id";
            $response = $client->request('GET', $url,[ 'headers' => [
                'Authorization' => 'Bearer ' . $apiToken,
            ],]);
            $conten = $response->getBody()->getContents();
            $contenarray = json_decode($conten, true);
            $data = $contenarray['data'];
            return view('admin.ubah_ruangan', ['data' => $data]);
        } catch (\Throwable $th) {
            return redirect()->to('/admin/DataRuangan');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //edit ruangan
        $apiUrl = env('API_URL');
        $apiToken = session('api_token');
        $validatedData = $request->validate([
            'nama' => 'required',
            'fasilitas' => 'required',
            'foto' => 'nullable|image', // Jika Anda ingin memastikan bahwa 'foto' adalah berkas gambar.
        ]);
        try {
            $options = [
                'multipart' => [
                    [
                        'name' => 'nama',
                        'contents' => $validatedData['nama']
                    ],
                    [
                        'name' => 'fasilitas',
                        'contents' => $validatedData['fasilitas']
                    ]
                    ],'headers' => [
                        'Authorization' => 'Bearer ' . $apiToken,
                    ],

            ];
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                // Tambahkan 'foto' ke $options jika ada
                $options['multipart'][] = [
                    'name' => 'foto',
                    'contents' => fopen($foto->getPathname(), 'r'),
                    'filename' => $foto->getClientOriginalName(),
                    'headers' => [
                        'Content-Type' => '<Content-type header>'
                    ]
                ];
            }

            $client = new Client();
            $url = $apiUrl . "/api/ruangan/$id";
            $response = $client->request('POST', $url, $options);
            $response->getBody()->getContents();
            // return $options;
            return redirect()->to('/admin/DataRuangan')
                ->with('success', 'data ruangan ' . $validatedData['nama'] . ' berhasil di ubah');
        } catch (\Exception $e) {
            return redirect()->back()->with('RuanganIsExist', 'ruangan ' . $validatedData['nama'] . ' Sudah ada.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
