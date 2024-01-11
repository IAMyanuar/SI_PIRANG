<?php

namespace App\Http\Controllers\AdminController;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{

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
}
