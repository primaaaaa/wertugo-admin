<?php

namespace App\Http\Controllers;

use Http;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Session;

class UmkmController extends Controller
{
    public function index(Request $request){
         // 1. Tangkap halaman saat ini (default: 1)
        $page = $request->input('page', 1);

        $token = Session::get('api_token');

        $url = env('WERTUGO_API').'/umkm/getumkm';
        
        // 2. Kirim parameter page ke API
        $response = Http::withToken($token)->get($url, ['page' => $page]);

        if ($response->successful()){
            $apiData = $response->json();
            
            $stats = $apiData['stats'];
            $paginationData = $apiData['data_umkm'];


            // 3. Bangun Paginator
            // Pastikan struktur response API kamu benar-benar dari fungsi ->paginate() 
            // sehingga memiliki key 'data', 'total', dll.
            $umkm = new LengthAwarePaginator(
                $paginationData['data'],           
                $paginationData['total'],          
                $paginationData['per_page'],       
                $paginationData['current_page'],   
                [
                    'path' => $request->url(), 
                    'query' => $request->query()
                ]
            );

            return view('pages.daftar-umkm', [
                'umkm' => $umkm,
                'tableHeaders' => ['Profil UMKM', 'Status Aktif', 'Status Verifikasi', 'Join Date', 'Aksi'],
                'stats' => $stats // Saya ganti 'Action' ke 'Aksi' agar pas dengan Blade-mu
            ]);
        }

        if($response->status() === 401){
            Session::flush();
            return redirect('/login')->withErrors(['email' => 'Sesi telah Habis. Silahkan login ulang']);
        }
        
        return abort($response->status(), 'Gagal mengambil data dari server.');
    }


}
