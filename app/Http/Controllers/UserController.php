<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator; // Jangan lupa import ini!

class UserController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tangkap halaman saat ini (default: 1)
        $page = $request->input('page', 1);

        $url = env('WERTUGO_API').'/user/getusers';
        
        // 2. Kirim parameter page ke API
        $response = Http::get($url, ['page' => $page]);

        if ($response->successful()){
            $apiData = $response->json();

            $stats = $apiData['stats'];
            $paginationData = $apiData['data_user'];

            // 3. Bangun Paginator
            // Pastikan struktur response API kamu benar-benar dari fungsi ->paginate() 
            // sehingga memiliki key 'data', 'total', dll.
            $users = new LengthAwarePaginator(
                $paginationData['data'],           
                $paginationData['total'],          
                $paginationData['per_page'],       
                $paginationData['current_page'],   
                [
                    'path' => $request->url(), 
                    'query' => $request->query()
                ]
            );

            return view('pages.daftar-user', [
                'stats' => $stats,
                'users' => $users,
                'tableHeaders' => ['User Profile', 'Role', 'Status', 'Join Date', 'Aksi'] // Saya ganti 'Action' ke 'Aksi' agar pas dengan Blade-mu
            ]);
        }
        
        return abort($response->status(), 'Gagal mengambil data dari server.');
    }
}