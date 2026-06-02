<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator; 
use Session;// Jangan lupa import ini!

class UserController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tangkap halaman saat ini (default: 1)
        $page = $request->input('page', 1);

        $token = Session::get('api_token');

        $url = env('WERTUGO_API').'/user/getusers';
        
        // 2. Kirim parameter page ke API
        $response = Http::withToken($token)->get($url, ['page' => $page]);

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

    public function showDetail($id)
    {
        // 1. Ambil token admin dari session
        $token = Session::get('api_token');
        
        // 2. URL endpoint Backend API yang tadi kita buat
        $url = env('WERTUGO_API') . '/admin/users/' . $id;

        // 3. Tembak API
        $response = Http::withToken($token)->get($url);

        // 4. Jika berhasil, lempar data ke View
        if ($response->successful()) {
            $apiData = $response->json();
            
            // Kita ambil bagian 'data' saja karena isinya sudah rapi ('profil', 'keamanan', 'komentar')
            $detailData = $apiData['data']; 

            return view('pages.detail.user', [
                'detail' => $detailData
            ]);
        }

        // 5. Jika token expired
        if ($response->status() === 401) {
            Session::flush();
            return redirect('/login')->withErrors(['email' => 'Sesi telah habis. Silakan login ulang.']);
        }

        // 6. Jika error lain (misal user tidak ditemukan)
        return back()->withErrors(['msg' => 'Gagal mengambil detail user. Pastikan data masih tersedia.']);
    }
}