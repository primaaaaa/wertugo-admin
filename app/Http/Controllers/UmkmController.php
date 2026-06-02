<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;

class UmkmController extends Controller
{
    /**
     * Menampilkan halaman Daftar/Tabel UMKM
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $token = Session::get('api_token');

        $url = env('WERTUGO_API') . '/umkm/getumkm'; 
        
        $response = Http::withToken($token)->get($url, ['page' => $page]);

        if ($response->successful()) {
            $apiData = $response->json();
            
            $stats = $apiData['stats'];
            $paginationData = $apiData['data_umkm'];

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
                'stats' => $stats
            ]);
        }

        if ($response->status() === 401) {
            Session::flush();
            return redirect('/login')->withErrors(['email' => 'Sesi telah habis. Silakan login ulang.']);
        }
        
        return abort($response->status(), 'Gagal mengambil data daftar UMKM dari server.');
    }

    /**
     * Menampilkan halaman Detail UMKM (Desain Premium)
     */
    public function showDetail($id)
    {
        $token = Session::get('api_token');
        
        // Pastikan endpoint ini sama persis dengan yang ada di Backend
        $url = env('WERTUGO_API') . '/umkm/' . $id;

        $response = Http::withToken($token)->get($url);

        if ($response->successful()) {
            $apiData = $response->json();
            
            // Ambil objek 'data' yang berisi profil_umkm, pemilik, keamanan, dan ulasan
            $detailData = $apiData['data']; 

            return view('pages.detail.umkm', [
                'detail' => $detailData
            ]);
        }

        if ($response->status() === 401) {
            Session::flush();
            return redirect('/login')->withErrors(['email' => 'Sesi telah habis. Silakan login ulang.']);
        }

        return back()->withErrors(['msg' => 'Gagal mengambil detail UMKM. Pastikan data tempat usaha masih tersedia di database.']);
    }

    public function verifyUmkm(Request $request, $id)
    {
        $token = Session::get('api_token');

        // Tembak API menggunakan metode PUT
        $response = Http::withToken($token)->put(env('WERTUGO_API').'/umkm/'.$id.'/verify');

        if ($response->successful()) {
            return back()->with('success', 'Status UMKM berhasil diubah menjadi Verified!');
        }

        return back()->withErrors(['msg' => 'Gagal memverifikasi UMKM. Pastikan server aktif.']);
    }
}



