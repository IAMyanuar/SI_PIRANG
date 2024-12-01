<?php

namespace App\Http\Controllers\AdminController;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

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
            $data = $contenarray['data'];
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $conten = $response->getBody()->getContents();
            $contenarray = json_decode($conten, true);
            return view('/admin/riwayat', ['empty' => $contenarray['message']]);
            // return view('admin.riwayat', ['datariwayat' =>$contenarray['message']]);
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

        //menampilkan pilihan bulan
        $now = Carbon::now();

        // Menyiapkan array untuk menyimpan data
        $months = [];

        // Mengisi 12 bulan terakhir
        for ($i = 0; $i < 10; $i++) {
            $month = $now->copy()->subMonths($i);
            $months[] = [
                'tahun' => $month->year,
                'nama_bulan' => $month->translatedFormat('F'), // Nama bulan dalam bahasa lokal
                'bulan' => str_pad($month->month, 2, '0', STR_PAD_LEFT) // Bulan dalam angka
            ];
        }

        // return dd($months);

        return view('admin.riwayat', ['data' => $paginator, 'months' => $months]);
    }

    public function unduhRiwayat(Request $request)
    {
        $apiUrl = env('API_URL');
        $apiToken = session('api_token');
        $client = new Client();
        $search = [
            // 'bulan' => $request->input('tahun_bulan')
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date')
        ];
        try {
            $url = $apiUrl . '/api/riwayat/download';
            $response = $client->request('POST', $url, [
                'json' =>  $search,
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiToken,
                ],
            ]);
            // Mengambil isi respons dari API
            $conten = $response->getBody()->getContents();

            // Kirim kembali respons API ke browser tanpa memodifikasinya
            return response($conten, $response->getStatusCode())
                ->header('Content-Type', $response->getHeader('Content-Type'));
        } catch (\Throwable $th) {
            // Jika terjadi kesalahan, kembali ke halaman sebelumnya dengan pesan error
            return back()->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }

    }
}
