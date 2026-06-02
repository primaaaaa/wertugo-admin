<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tangkap halaman saat ini dari query string URL (default: halaman 1)
        $page = $request->input('page', 1);
        $token = Session::get('api_token');

        // URL endpoint API Backend untuk mengambil list laporan
        $url = env('WERTUGO_API') . '/reports/getallreport'; 

        // 2. Kirim parameter page dan token Bearer ke API Backend
        $response = Http::withToken($token)->get($url, [
            'page' => $page
        ]);

        if ($response->successful()) {
            $apiData = $response->json();

            // Pisahkan data stats dan data pagination dari JSON Backend
            $stats = $apiData['stats'];
            $paginationData = $apiData['data_reports'];

            // 3. Bangun LengthAwarePaginator agar kompatibel dengan <x-data-table>
            $reportsPaginator = new LengthAwarePaginator(
                $paginationData['data'],           // Ambil array data laporan
                $paginationData['total'],          // Total seluruh data di DB
                $paginationData['per_page'],       // Jumlah data per halaman
                $paginationData['current_page'],   // Halaman aktif saat ini
                [
                    'path' => $request->url(), 
                    'query' => $request->query()
                ]
            );

            // 4. Lempar data ke view Blade
            return view('pages.report-notice', [
                'stats' => $stats,
                'reports' => $reportsPaginator,
                // Header disesuaikan persis dengan desain UI yang kamu kirim tadi
                'tableHeaders' => ['Isi Komentar / Laporan', 'UMKM', 'Dilaporkan Oleh', 'Jumlah Report', 'Tanggal', 'Aksi']
            ]);
        }

        // Handle jika sesi token admin habis
        if ($response->status() === 401) {
            Session::flush();
            return redirect('/login')->withErrors(['email' => 'Sesi telah habis. Silakan login ulang.']);
        }

        return abort($response->status(), 'Gagal mengambil data laporan dari server.');
    }

    // Method Eksekutor untuk menindak laporan (Suspend)
    public function tindakReport(Request $request, $id)
    {
        $token = Session::get('api_token');
        $url = env('WERTUGO_API') . '/reports/' . $id . '/tindak';

        // Tembak API menggunakan PUT dengan membawa form + comment_id
        $response = Http::withToken($token)->put($url, [
            'aksi_komentar'    => $request->input('aksi_komentar'),
            'status_akun'      => $request->input('status_akun'),
            'catatan_internal' => $request->input('catatan_internal'),
            'comment_id'       => $request->input('comment_id'), // DATA BARU
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Laporan berhasil ditindaklanjuti sesuai pilihan Anda!');
        }

        $errorMsg = $response->json()['message'] ?? 'Gagal menindak laporan pelanggaran.';
        return back()->withErrors(['msg' => $errorMsg]);
    }
}