<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class PeminjamanController extends Controller
{
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



    public function unduhFile($id)
    {
        $apiUrl = env('API_URL');
        $apiToken = session('api_token');
        $client = new Client();
        $url = $apiUrl . '/api/unduhFileDokumen/' . $id;
        // return $apiToken;
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
}
