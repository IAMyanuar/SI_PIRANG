<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class FasilitasController extends Controller
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
            $url = $apiUrl . "/api/fasilitas";
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiToken
                ],
            ]);
            $conten = $response->getBody()->getContents();
            $contenarray = json_decode($conten, true);
            $data = $contenarray['data'];
            return view('admin.data_fasilitas', ['data' => $data]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tambah_fasilitas');
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
            'jumlah' => 'required',
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
                        'name' => 'foto',
                        'contents' => fopen($foto, 'r'),
                        'filename' => $foto->getClientOriginalName(),
                        'headers'  => [
                            'Content-Type' => '<Content-type header>'
                        ]
                    ],
                    [
                        'name' => 'jumlah',
                        'contents' => $validatedData['jumlah']
                    ],
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiToken,
                ],

            ];

            $client = new Client();
            $url = $apiUrl . "/api/tambahfasilitas";
            $response = $client->request('POST', $url, $options);
            $response->getBody()->getContents();
            return redirect()->to('/admin/DataFasilitas')
                ->with('success', 'ruangan ' . $validatedData['nama'] . ' berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('FasilitasIsExist', 'ruangan ' . $validatedData['nama'] . ' Sudah ada.');
        }
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
            $url = $apiUrl . "/api/fasilitas/$id";
            $response = $client->request('GET', $url,[ 'headers' => [
                'Authorization' => 'Bearer ' . $apiToken,
            ],]);
            $conten = $response->getBody()->getContents();
            $contenarray = json_decode($conten, true);
            $data = $contenarray['data'];
            return view('admin.ubah_fasilitas', ['data' => $data]);
        } catch (\Throwable $th) {
            return redirect()->to('/admin/DataFasilitas');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,string $id)
    {
        $apiUrl = env('API_URL');
        $apiToken = session('api_token');
        $validatedData = $request->validate([
            'nama' => 'required',
            'foto' => 'nullable|image', // Jika Anda ingin memastikan bahwa 'foto' adalah berkas gambar.
            'jumlah' => 'required',
        ]);
        try {
            $options = [
                'multipart' => [
                    [
                        'name' => 'nama',
                        'contents' => $validatedData['nama']
                    ],
                    [
                        'name' => 'jumlah',
                        'contents' => $validatedData['jumlah']
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
            $url = $apiUrl . "/api/fasilitas/$id";
            $response = $client->request('POST', $url, $options);
            $response->getBody()->getContents();
            // return $options;
            return redirect()->to('/admin/DataFasilitas')
                ->with('success', 'data ruangan ' . $validatedData['nama'] . ' berhasil di ubah');
        } catch (\Exception $e) {
            return redirect()->back()->with('RuanganIsExist', 'ruangan ' . $validatedData['nama'] . ' Sudah ada.');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fasilitas $fasilitas)
    {
        //
    }
}
