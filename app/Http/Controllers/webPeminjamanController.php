<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use DateTime;
use Faker\Core\Color;
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
            $url1 = $apiUrl . "/api/peminjaman/submitted";
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
            $url2 = $apiUrl . "/api/peminjaman/approve";
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
            $url3 = $apiUrl . "/api/peminjaman/inprogress";
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
            $url = $apiUrl . '/api/peminjaman/' . $id;
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
            $url = $apiUrl . "/api/peminjaman/{$id}";
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
        $apiToken = session('api_token');
        $client = new Client();
        $url = $apiUrl . '/api/unduhFileDokumen/' . $id;
        try {
            $response = $client->get($url, ['headers' => [
                'Authorization' => 'Bearer ' . $apiToken
            ],]);

            if ($response->getStatusCode() === 200) {
                // Mendapatkan body respons (file) dari API
                $fileData = $response->getBody()->getContents();

                // Menentukan header respons sesuai dengan header dari API
                $headers = $response->getHeaders();

                // Meneruskan file dari API ke pengguna
                return response($fileData, 200)->withHeaders($headers);
            } elseif ($response->getStatusCode() === 404) {
                return redirect()->back()
                    ->with('info', 'tidak ada bukti pendukung');
            }
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $conten = $response->getBody()->getContents();
            $contenarray = json_decode($conten, true);
            return redirect()->back()
                ->with('error', $contenarray['message']);
        }
    }

    public function riwayat(Request $request)
    {
        //riwayat dan pencarian
        $apiUrl = env('API_URL');
        $apiToken = session('api_token');
        $client = new Client();
        $search = [
            'keyword' => $request->input('search')
        ];
        try {
            $url = $apiUrl . '/api/peminjaman/riwayat';
            $response = $client->request('GET', $url, ['json' =>  $search, 'headers' => [
                'Authorization' => 'Bearer ' . $apiToken,
            ],]);
            $conten = $response->getBody()->getContents();
            $contenarray = json_decode($conten, true);
            $datariwayat = $contenarray['data'];
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $conten = $response->getBody()->getContents();
            $contenarray = json_decode($conten, true);
            return view('/admin/riwayat', ['empty' => $contenarray['message']]);
            // return view('admin.riwayat', ['datariwayat' =>$contenarray['message']]);
        }
        return view('admin.riwayat', ['datariwayat' => $datariwayat]);
    }

    public function KalenderPeminjaman()
    {
        // Menampilkan halaman kalender
        return view('admin.kalender');
    }

    public function report()
    {
        $apiUrl = env('API_URL');
        $apiToken = session('api_token');
        $client = new Client();

        try {
            //mengambil data ruangan
            $url1 = $apiUrl . '/api/ruangan';
            $response2 = $client->get($url1, ['headers' => [
                'Authorization' => 'Bearer ' . $apiToken
            ],]);
            $conten1 = $response2->getBody()->getContents();
            $contenarray1 = json_decode($conten1, true);
            $dataruangan = $contenarray1['data'];
            //data nama ruangan
            $ruangans = [];
            foreach ($dataruangan as $data) {
                $namaruangan = $data['nama'];
                if (!in_array($namaruangan, $ruangans)) {
                    $ruangans[] = $namaruangan;
                }
            }

            //mengambil data peminjaman
            $url2 = $apiUrl . '/api/peminjaman';
            $response2 = $client->get($url2, ['headers' => [
                'Authorization' => 'Bearer ' . $apiToken
            ],]);
            $conten2 = $response2->getBody()->getContents();
            $contenarray2 = json_decode($conten2, true);
            $datapeminjaman = $contenarray2['data'];
            //
            $bulanIni = date('Y-m');
            $BulanSebelumnya = date('Y-m', strtotime('-1 month'));
            $dataBulanIni = array_fill_keys($ruangans, 0);
            $dataBulanSebelumnya = array_fill_keys($ruangans, 0);

            //mengambil data peminjaman status[complited, in progress]
            foreach ($datapeminjaman as $data) {
                if ($data['status'] == 'completed' || $data['status'] == 'in progress') {
                    $peminjamanFinal[] = $data;
                }
            }

            //mengambil data peminjaman status [reject]
            $peminjamanReject = 0;
            foreach ($datapeminjaman as $data) {
                if ($data['status'] == 'reject') {
                    $peminjamanReject++;
                }
            }

            //mengambil data peminjaman status [submitted]
            $peminjamanSubmited = 0;
            foreach ($datapeminjaman as $data) {
                if ($data['status'] == 'submitted') {
                    $peminjamanSubmited++;
                }
            }

            //mengambil data peminjaman status di[approved]
            $peminjamanApprove = 0;
            foreach ($datapeminjaman as $data) {
                if ($data['status'] == 'approved' || $data['status'] == 'in progress' || $data['status'] == 'completed') {
                    $peminjamanApprove++;
                }
            }

            //mengambil data peminjaman terkonfirmasi
            $peminjamanTKF = 0;
            foreach ($datapeminjaman as $data) {
                if ($data['status'] !== 'submitted') {
                    $peminjamanTKF++;
                }
            }

            //perulangan menghitung data ruangan yang di pakai
            foreach ($peminjamanFinal as $peminjam) {
                $tgl_mulai = (new DateTime($peminjam['tgl_mulai']))->format('Y-m');
                $namaruangan = $peminjam['ruangan']['nama'];
                if ($tgl_mulai == $bulanIni) {
                    if (in_array($namaruangan, $ruangans)) {
                        $dataBulanIni[$namaruangan]++;
                    }
                } else if ($tgl_mulai == $BulanSebelumnya) {
                    if (in_array($namaruangan, $ruangans)) {
                        $dataBulanSebelumnya[$namaruangan]++;
                    }
                }
            }
            //data nilai perruangan
            $dataBulanSebelumnya = array_values($dataBulanSebelumnya);
            $dataBulanIni = array_values($dataBulanIni);

            //membuat warna untuk grafik
            $color1 = '#ffac00';
            $color2 = '#000dff';
            for ($i = 0; $i < count($ruangans); $i++) {
                $colors1[] = $color1;
                $colors2[] = $color2;
            }




            // Menghitung jumlah peminjaman setiap ruangan
            $jumlahPeminjaman = [];
            foreach ($dataruangan as $ruangan) {
                $ruanganId = $ruangan['id'];
                $jumlahPeminjaman[$ruanganId] = 0;

                foreach ($peminjamanFinal as $peminjaman) {
                    if ($peminjaman['id_ruangan'] == $ruanganId && $peminjaman['status'] == 'completed') {
                        $jumlahPeminjaman[$ruanganId]++;
                    }
                }
            }

            // chart line
            $hasilData = [];
            foreach ($dataruangan as $ruangan) {
                $ruanganId = $ruangan['id'];
                $label = $ruangan['nama'];
                $data = [];

                // Menggunakan bulan sebagai index
                for ($bulan = 1; $bulan <= 12; $bulan++) {
                    // Inisialisasi jumlah peminjaman pada bulan ini
                    $jumlahPeminjamanBulanIni = 0;

                    // Iterasi pada data peminjaman
                    foreach ($peminjamanFinal as $peminjaman) {
                        $bulanPeminjaman = date('n', strtotime($peminjaman['tgl_mulai']));

                        // Periksa apakah peminjaman sesuai dengan bulan dan ruangan
                        if ($peminjaman['id_ruangan'] == $ruanganId && $bulanPeminjaman == $bulan) {
                            $jumlahPeminjamanBulanIni++;
                        }
                    }
                    $data[] = $jumlahPeminjamanBulanIni;
                }


                // Menghasilkan format data yang diinginkan
                $hasilData[] = [
                    'label' => $label,
                    'data' => $data,
                    'borderColor' => '#' . substr(md5($ruanganId), 0, 6),
                    'backgroundColor' =>  '#' . substr(md5($ruanganId), 0, 6),
                    'fill' => false,
                ];
            }

            return view('admin.dashboard', [
                'ruangan' => $ruangans,
                // 'dataBulanSebelumnya' => $dataBulanSebelumnya,
                // 'dataBulanIni' => $dataBulanIni,
                // 'colors1' => $colors1, 'colors2' => $colors2,
                'peminjamanSubmitted' => $peminjamanSubmited,
                'peminjamanApprove' => $peminjamanApprove,
                'peminjamanReject' => $peminjamanReject,
                'peminjamanFinal' => $peminjamanFinal,
                'peminjamanTKF' => $peminjamanTKF,
                'grafikline' => $hasilData
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
