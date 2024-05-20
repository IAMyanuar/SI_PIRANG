<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use Illuminate\Pagination\LengthAwarePaginator;

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
                $data = $contenarray['data'];
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $conten = $response->getBody()->getContents();
            $contenarray = json_decode($conten, true);
            return view('user.riwayat',['empty' => $contenarray['message']]);
            // return $contenarray['message'];
        }
        // Paginasi data
        $currentPage = $request->query('page', 1);
        $perPage = 10; // Jumlah data per halaman
        $offset = ($currentPage - 1) * $perPage;
        $total = count($data);

        $items = array_slice($data, $offset, $perPage);
        $paginator = new LengthAwarePaginator($items, $total, $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        return view('user.riwayat', ['data' => $paginator]);
    }
}
