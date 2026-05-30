<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $token = Session::get('api_token');
        
        $url = env('WERTUGO_API').'/umkm/getverifylist'; // Pastikan URL API kamu benar
        
        $response = Http::withToken($token)->get($url, ['page' => $page]);

        if ($response->successful()){
            $apiData = $response->json();
            
            // Tangkap 3 bagian data sesuai yang dikirim API
            $stats = $apiData['stats'];
            $pendingCards = $apiData['pending_cards']; // Ini bentuknya array biasa (karena pakai ->get())
            $paginationData = $apiData['history_table']; // Ini bentuknya paginator

            // Bangun Paginator untuk Tabel Riwayat
            $historyPaginator = new LengthAwarePaginator(
                $paginationData['data'],           
                $paginationData['total'],          
                $paginationData['per_page'],       
                $paginationData['current_page'],   
                [
                    'path' => $request->url(), 
                    'query' => $request->query()
                ]
            );

            return view('pages.verifikasi-umkm', [
                'stats' => $stats,
                'pendingCards' => $pendingCards, // Kirim untuk 3 card teratas
                'historyData' => $historyPaginator, // Kirim untuk tabel
                'tableHeaders' => ['Nama UMKM', 'Kategori', 'Pemilik', 'Status Verifikasi', 'Status Akun', 'Aksi']
            ]);
        }

        if($response->status() === 401){
            Session::flush();
            return redirect('/login')->withErrors(['email' => 'Sesi telah Habis. Silahkan login ulang']);
        }
        
        return abort($response->status(), 'Gagal mengambil data dari server.');
    }

    public function verifyUmkm(Request $request, $id)
    {
        $token = Session::get('api_token');

        // Tembak API menggunakan metode PUT
        $response = Http::withToken($token)->put(env('WERTUGO_API').'/umkm/'.$id.'/verify');

        if ($response->successful()) {
            return back()->with('success', 'Status Verifikasi UMKM berhasil diubah menjadi Verified!');
        }

        $errorMsg = $response->json()['message'] ?? 'Gagal memverifikasi UMKM.';
        return back()->withErrors(['msg' => $errorMsg]);
    }
}