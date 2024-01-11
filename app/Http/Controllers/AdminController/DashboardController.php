<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class DashboardController extends Controller
{
    public function index()
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

            //mengambil data peminjaman status[complited, in progress]
            $peminjamanFinal=[];
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

            // chart line
            $hasilData = [];
            foreach ($dataruangan as $ruangan) {
                $ruanganId = $ruangan['id'];
                $label = $ruangan['nama'];
                $dataTahunSebelumnya = [];
                $dataTahunIni = [];

                // Menggunakan bulan sebagai index
                for ($bulan = 1; $bulan <= 12; $bulan++) {
                    // Inisialisasi jumlah peminjaman pada bulan ini
                    $jumlahPeminjamanBulanIni = 0;
                    $jumlahPeminjamanBulanTahunSebelumnya = 0;

                    // Iterasi pada data peminjaman di tahun ini
                    foreach ($peminjamanFinal as $peminjaman) {
                        $bulanPeminjaman = date('n', strtotime($peminjaman['tgl_mulai']));
                        $tahunPeminjamanNow = date('Y', strtotime($peminjaman['tgl_mulai']));

                        // Periksa apakah peminjaman sesuai dengan bulan dan ruangan
                        if ($peminjaman['id_ruangan'] == $ruanganId && $bulanPeminjaman == $bulan && $tahunPeminjamanNow == date('Y')) {
                            $jumlahPeminjamanBulanIni++;
                        }
                    }
                    $dataTahunIni[] = $jumlahPeminjamanBulanIni;

                    // Iterasi pada data peminjaman di tahun sebelumnya
                    foreach ($peminjamanFinal as $peminjaman) {
                        $bulanPeminjaman = date('n', strtotime($peminjaman['tgl_mulai']));
                        $tahunPeminjamanNow = date('Y', strtotime($peminjaman['tgl_mulai']));
                        $TahunSebelumnya = $tahunPeminjamanNow - 1;

                        // Periksa apakah peminjaman sesuai dengan bulan dan ruangan
                        if ($peminjaman['id_ruangan'] == $ruanganId && $bulanPeminjaman == $bulan && $TahunSebelumnya == date('Y')) {
                            $jumlahPeminjamanBulanTahunSebelumnya++;
                        }
                    }
                    $dataTahunSebelumnya[] = $jumlahPeminjamanBulanTahunSebelumnya;
                }


                // Menghasilkan format data yang diinginkan
                $hasilData[] = [
                    'label' => $label,
                    'data' => $dataTahunIni,
                    'borderColor' => '#' . substr(md5($ruanganId), 0, 6),
                    'backgroundColor' =>  '#' . substr(md5($ruanganId), 0, 6),
                    'fill' => false,
                ];
            }

            // return date('y');

            return view('admin.dashboard', [
                'ruangan' => $ruangans,
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
