<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //menampilkan semua data
        $apiUrl = env('API_URL');
        $apiToken = session('api_token');
        $id_user = session('id_user');
        // try {
        $client = new Client();
        $url1 = $apiUrl . "/api/ruangan";
        $response1 = $client->request('GET', $url1, [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiToken
            ],
        ]);
        $dataruangan = [];
        $conten = $response1->getBody()->getContents();
        $contenarray = json_decode($conten, true);
        $dataruangan = $contenarray['data'];

        // data peminjaman user
        $url2 = $apiUrl . '/api/peminjamanbyuser/' . $id_user;
        $response2 = $client->request(
            'GET',
            $url2,
            ['headers' => [
                'Authorization' => 'Bearer ' . $apiToken
            ],]
        );
        // $datapeminjaman = [];
        $conten1 = $response2->getBody()->getContents();
        $contenarray1 = json_decode($conten1, true);
        $datapeminjaman = $contenarray1['data'];

        //riwayat
        $url3 = $apiUrl . '/api/peminjaman/riwayat/' . $id_user;
        $response3 = null;

        try {
            $response3 = $client->request('GET', $url3, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiToken,
                ],
            ]);
        } catch (RequestException $e) {
            // Handle error if needed, or just continue without setting $response3
        }
        $datariwayat = [];
        if ($response3 && $response3->getStatusCode() === 200) {
            $conten = $response3->getBody()->getContents();
            $contenarray = json_decode($conten, true);
            $datariwayat = $contenarray['data'];
        }

        $peminjamandisetujui = 0;
        $peminjamanditolak = 0;
        // return $datapeminjaman;

            foreach ($datapeminjaman[0] as $value) {
                if ($value['status'] == 'disetujui' || $value['status'] == 'diprosess') {
                    $peminjamandisetujui++;
                }
            }


        $total= count($datapeminjaman[0]);


        foreach ($datariwayat as $value) {
            if ($value['status'] == 'ditolak') {
                $peminjamanditolak++;
            }
        }


        return view('user.dashboard', ['dataruangan' => $dataruangan, 'datapeminjaman'=>$total ,'peminjamandisetujui' => $peminjamandisetujui, 'peminjamanditolak' => $peminjamanditolak]);
    }

    // public function KalenderPeminjaman()
    // {
    //     // Mengirimkan data ke halaman Blade
    //     return view('user.kalender');

    // }
}
