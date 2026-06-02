<?php

namespace App\Http\Controllers;

use Http;
use Illuminate\Http\Request;
use Session;

class DashboardController extends Controller
{
    public function index(){
        $url = env('WERTUGO_API').'/admin/dashboard';
        $token = Session::get('api_token');
        $response = Http::withToken($token)->get($url);

        $stats = [
            'total_user' => 0,
            'total_umkm' => 0,
            'total_verifikasi_pending' => 0
            ];

        if($response->successful()){
            $stats = $response->json();
        }

        return view('pages.dashboard', ['stats' => $stats]);
    }
}
