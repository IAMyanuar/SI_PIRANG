<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class LandingPageController extends Controller
{
    public function index()
    {
        //
        //menampilkan semua data
        $apiUrl = env('API_URL');
        
        // try {
        $client = new Client();
        $url1 = $apiUrl . "/api/ruangan";
        $response1 = $client->request('GET', $url1);
        $dataruangan = [];
        $conten = $response1->getBody()->getContents();
        $contenarray = json_decode($conten, true);
        $dataruangan = $contenarray['data'];

        return view('layout.landing_page', ['dataruangan' => $dataruangan]);
    }
}
