<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KalenderController extends Controller
{
    //
    public function index()
    {
        // Menampilkan halaman kalender
        return view('admin.kalender');
    }
}
