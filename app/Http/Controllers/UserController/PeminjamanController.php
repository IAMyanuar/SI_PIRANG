<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Termwind\Components\Dd;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function peminjamanku()
    {
        //
        $apiUrl = env('API_URL');
        $apiToken = session('api_token');
        $id_user = session('id_user');
        $client = new Client();
        try {
            $url = $apiUrl.'/api/peminjamanbyuser/' . $id_user;
            $response = $client->request(
                'GET',
                $url,
                ['headers' => [
                    'Authorization' => 'Bearer ' . $apiToken
                ],]
            );
            $conten = $response->getBody()->getContents();
            $contenarray = json_decode($conten, true);
            $datapeminjaman = $contenarray['data'];
        } catch (\Throwable $th) {
            return view('user.pengajuan_peminjaman')
            ->with('datakosong');
        }
        return view('user.pengajuan_peminjaman', ['datapeminjaman' => $datapeminjaman]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //menampilkan semua data ruangan
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
            return view('user.ajukan_peminjaman', ['data' => $data]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //menambahkan data peminjaman
        $apiUrl = env('API_URL');
        $id_user = session('id_user');
        $apiToken = session('api_token');

        $validatedData = $request->validate([
            'nama_lembaga' => 'required',
            'kegiatan' => 'required',
            'tgl_mulai' => 'required',
            'tgl_selesai' => 'required',
            'id_ruangan' => 'required',
            'dokumen_pendukung' => 'nullable',


            // Jika Anda ingin memastikan bahwa 'dokumen_pendukung' adalah berkas gambar.
        ]);

        try {
            $dokumen_pendukung = $request->file('dokumen_pendukung');
            $options = [
                'multipart' => [
                    [
                        'name' => 'nama_lembaga',
                        'contents' => $validatedData['nama_lembaga']
                    ],
                    [
                        'name' => 'kegiatan',
                        'contents' => $validatedData['kegiatan']
                    ],
                    [
                        'name' => 'tgl_mulai',
                        'contents' => $validatedData['tgl_mulai']
                    ],
                    [
                        'name' => 'tgl_selesai',
                        'contents' => $validatedData['tgl_selesai']
                    ],
                    [
                        'name' => 'user_id',
                        'contents' => $id_user
                    ],
                    [
                        'name' => 'id_ruangan',
                        'contents' => $validatedData['id_ruangan']
                    ],


                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiToken,
                ],

            ];

            if (!empty($dokumen_pendukung)) {
                $dokumenIsexist = [
                    'name' => 'dokumen_pendukung',
                    'contents' => fopen($dokumen_pendukung, 'r'),
                    'filename' => $dokumen_pendukung->getClientOriginalName(),
                    'headers'  => [
                        'Content-Type' => '<Content-type header>'
                    ]
                    ];
                    $options['multipart'][] = $dokumenIsexist;
            }


            $client = new Client();
            $url = $apiUrl . "/api/peminjaman";
            $response = $client->request('POST', $url, $options);
            $response->getBody()->getContents();
            return redirect()->to('/PengajuanPeminjaman')
                ->with('success', 'Pengajuan ruangan Berhasil');
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $conten = $response->getBody()->getContents();
            $contenarray = json_decode($conten, true);
            return redirect()->back()
                ->with('error', $contenarray['message']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
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
            return view('user.detail_peminjaman', ['datapeminjam' => $datapeminjam]);
        } catch (\Throwable $th) {
            // return redirect()->back()->withErrors('error', 'Access Denied');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //menampilkan data peminjaman yang lama
        $apiUrl = env('API_URL');
        $apiToken = session('api_token');
        $client = new Client();
        try {
            $url1 = $apiUrl.'/api/peminjaman/' . $id;
            $response1 = $client->request(
                'GET',
                $url1,
                ['headers' => [
                    'Authorization' => 'Bearer ' . $apiToken
                ],]
            );

            $url2 = $apiUrl . "/api/ruangan";
            $response2 = $client->request('GET', $url2, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiToken
                ],
            ]);

            $conten1 = $response1->getBody()->getContents();
            $contenarray1 = json_decode($conten1, true);
            $datapeminjam = $contenarray1['data'];

            $conten2 = $response2->getBody()->getContents();
            $contenarray2 = json_decode($conten2, true);
            $data = $contenarray2['data'];

            return view('user.edit_peminjaman', ['datapeminjam' => $datapeminjam, 'data' => $data]);
        } catch (\Throwable $th) {
            // return redirect()->back()->withErrors('error', 'Access Denied');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $apiUrl = env('API_URL');
        $id_user = session('id_user');
        $apiToken = session('api_token');

        $validatedData = $request->validate([
            'nama_lembaga' => 'required',
            'kegiatan' => 'required',
            'tgl_mulai' => 'required',
            'tgl_selesai' => 'required',
            'id_ruangan' => 'required',
            'dokumen_pendukung' => 'nullable',


            // Jika Anda ingin memastikan bahwa 'dokumen_pendukung' adalah berkas gambar.
        ]);

        try {
            $dokumen_pendukung = $request->file('dokumen_pendukung');
            $options = [
                'multipart' => [
                    [
                        'name' => 'nama_lembaga',
                        'contents' => $validatedData['nama_lembaga']
                    ],
                    [
                        'name' => 'kegiatan',
                        'contents' => $validatedData['kegiatan']
                    ],
                    [
                        'name' => 'tgl_mulai',
                        'contents' => $validatedData['tgl_mulai']
                    ],
                    [
                        'name' => 'tgl_selesai',
                        'contents' => $validatedData['tgl_selesai']
                    ],
                    [
                        'name' => 'user_id',
                        'contents' => $id_user
                    ],
                    [
                        'name' => 'id_ruangan',
                        'contents' => $validatedData['id_ruangan']
                    ],


                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiToken,
                ],

            ];

            if (!empty($dokumen_pendukung)) {
                $dokumenIsexist = [
                    'name' => 'dokumen_pendukung',
                    'contents' => fopen($dokumen_pendukung, 'r'),
                    'filename' => $dokumen_pendukung->getClientOriginalName(),
                    'headers'  => [
                        'Content-Type' => '<Content-type header>'
                    ]
                    ];
                    $options['multipart'][] = $dokumenIsexist;
            }


            $client = new Client();
            $url = $apiUrl . "/api/EditPeminjaman/$id";
            $response = $client->request('POST', $url, $options);
            $response->getBody()->getContents();
            return redirect()->to('/PengajuanPeminjaman')
                ->with('success', 'pengajuan Peminjaman Ruangan Berhasil di Ubah');
            return dd($options);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $conten = $response->getBody()->getContents();
            $contenarray = json_decode($conten, true);
            return redirect()->back()
                ->with('error', $contenarray['message']);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $apiUrl = env('API_URL');
        $apiToken = session('api_token');

        try {
            $options = [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiToken,
                ],
            ];

            $client = new Client();
            $url = $apiUrl . "/api/peminjaman/" . $id;
            $response = $client->request('DELETE', $url, $options);
            $conten = $response->getBody()->getContents();
            $contenarray = json_decode($conten, true);

            // Beri respons berhasil
            return redirect()->back()->with('success', 'Peminjaman berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->to('/PengajuanPeminjaman')
                ->with('error', 'Terjadi kesalahan. ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $apiUrl = env('API_URL');
            $apiToken = session('api_token');
            $options = [
                'form_params' => [
                  'feedback' => $request->input('feedback'),
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiToken,
                ],
            ];



            // Kirim permintaan PUT ke API dengan status yang sesuai
            $client = new Client();
            $url = $apiUrl."/api/peminjaman/$id/feedback";
            $response = $client->request('PATCH', $url, $options);

            // Periksa respons dari API
            if ($response->getStatusCode() === 201) {
                return redirect()->back()->with('success', 'Berhasil mengirim ulasan');
            } else {
                return redirect()->back()->with('error', 'Gagal merubah memberi ulasan');
            }
        }  catch (\Exception $e) {
            // $response = $e->getResponse();
            // $conten = $response->getBody()->getContents();
            // $contenarray = json_decode($conten, true);
            // return redirect()->back()
            //     ->with('error',$contenarray['message']);
            return dd($options);
        }
    }

}
