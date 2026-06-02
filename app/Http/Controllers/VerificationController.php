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
                'historyTable' => $historyPaginator, // Kirim untuk tabel
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

    public function pendingList(Request $request)
    {
        // 1. Tangkap halaman aktif dari query string URL
        $page = $request->input('page', 1);
        $token = Session::get('api_token');
        
        // 2. URL endpoint API Backend khusus data pending
        $url = env('WERTUGO_API') . '/verifikasi/pending'; 
        
        // 3. Ambil data dari Backend
        $response = Http::withToken($token)->get($url, [
            'page' => $page
        ]);

        if ($response->successful()) {
            $apiData = $response->json();
            $paginationData = $apiData['data'];
            $totalPending = $apiData['total_pending'] ?? 0;

            // 4. Bangun LengthAwarePaginator agar link halaman (1, 2, 3) di Blade aktif
            $pendingTable = new LengthAwarePaginator(
                $paginationData['data'],           // Array data item
                $paginationData['total'],          // Total seluruh data pending di DB
                $paginationData['per_page'],       // Jumlah data per halaman
                $paginationData['current_page'],   // Halaman aktif saat ini
                [
                    'path' => $request->url(), 
                    'query' => $request->query()
                ]
            );

            return view('pages.verifikasi-pending', [
                'pendingTable' => $pendingTable,
                'totalPending' => $totalPending
            ]);
        }

        // 5. Antisipasi jika sesi token kedaluwarsa
        if ($response->status() === 401) {
            Session::flush();
            return redirect('/login')->withErrors(['email' => 'Sesi telah habis. Silakan login ulang.']);
        }
        
        return abort($response->status(), 'Gagal mengambil data antrean verifikasi dari server.');
    }
    public function showDetail($id)
    {
        $token = Session::get('api_token');
        $url = env('WERTUGO_API') . '/admin/verifikasi/' . $id;

        $response = Http::withToken($token)->get($url);

        if ($response->successful()) {
            $apiData = $response->json();
            
            return view('pages.detail.verifikasi', [
                'detail' => $apiData['data']
            ]);
        }

        if ($response->status() === 401) {
            Session::flush();
            return redirect('/login')->withErrors(['email' => 'Sesi telah habis. Silakan login ulang.']);
        }

        return back()->withErrors(['msg' => 'Gagal mengambil detail verifikasi. Pastikan data masih tersedia.']);
    }

    
}