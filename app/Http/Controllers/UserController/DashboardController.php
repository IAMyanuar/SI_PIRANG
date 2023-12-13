<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        try {
            $client = new Client();
            $url = $apiUrl . "/api/ruangan";
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiToken
                ],
            ]);
            $conten = $response->getBody()->getContents();
            $contenarray = json_decode($conten, true);
            $dataruangan = $contenarray['data'];

            //data peminjaman user
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

            //riwayat
            $url = $apiUrl.'/api/peminjaman/riwayat/' . $id_user;
            $response = $client->request('GET', $url, ['headers' => [
                'Authorization' => 'Bearer ' . $apiToken,
            ],]);
            $conten = $response->getBody()->getContents();
            $contenarray = json_decode($conten, true);
            $datariwayat = $contenarray['data'];

            $peminjamandisetujui = 0;
            foreach ($datapeminjaman as $value) {
                if ($value['status'] !== 'submitted') {
                    $peminjamandisetujui++;
                }
            }

            foreach ($datariwayat as $value) {
                if ($value['status'] !== 'reject') {
                    $peminjamandisetujui++;
                }
            }

            $peminjamanditolak = 0 ;
            foreach ($datariwayat as $value) {
                if ($value['status'] == 'reject') {
                    $peminjamanditolak++;
                }
            }

            return view('user.dashboard', ['dataruangan' => $dataruangan, 'peminjamandisetujui' => $peminjamandisetujui, 'peminjamanditolak' => $peminjamanditolak]);
        } catch (\Throwable $th) {
            // throw $th;
        }
    }
}
