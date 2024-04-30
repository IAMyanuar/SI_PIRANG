<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;

class RiwayatController extends Controller
{
    public function riwayatPeminjaman(Request $request)
        {
            //riwayat dan pencarian
            $apiUrl = env('API_URL');
            $apiToken = session('api_token');
            $id_user = session('id_user');
            $client = new Client();
            $search = [
                'keyword' => $request->input('search')
            ];
            try {
                $url = $apiUrl.'/api/peminjaman/riwayat/' . $id_user;
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
            return view('user.riwayat',['empty' => $contenarray['message']]);
            // return $contenarray['message'];
        }

        return view('user.riwayat', ['datariwayat' => $datariwayat]);
        // return $datariwayat;

    }
}
