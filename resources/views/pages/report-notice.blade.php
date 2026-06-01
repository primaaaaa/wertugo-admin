@extends('layouts.app')
@section('title', 'Report Notice')
@section('header', 'Report Notice')

@section('admin-content')

<div class="container-fluid p-4">
    <div class="mb-5">
        <h3 class="fw-bold text-dark mb-1">Report Notice</h3>
        <p class="text-muted small">Kelola dan tindak lanjuti laporan pelanggaran dari pengguna.</p>
    </div>
    
    <div class="row g-4 mb-5">
        {{-- Ubah menjadi col-md-4 agar sejajar bertiga --}}
        <div class="col-12 col-md-4">
            <x-stat-card icon="bi-shop" iconColor="warning" cardTitle="Total UMKM Dilaporkan" :data="$stats['total_umkm_report'] ?? 0"></x-stat-card>
        </div>

        <div class="col-12 col-md-4">
            <x-stat-card icon="bi-chat-right-text-fill" iconColor="danger" cardTitle="Total Komentar Dilaporkan" :data="$stats['total_comment_report'] ?? 0"></x-stat-card>
        </div>

        <div class="col-12 col-md-4">
            <x-stat-card icon="bi-check-circle-fill" iconColor="success" cardTitle="Total Laporan Diselesaikan" :data="$stats['total_report_completed'] ?? 0"></x-stat-card>
        </div>
    </div>

    <div class="mt-4">
        <x-data-table
            title="Daftar Laporan Pelanggaran"
            :data="$reports"
            :headers="$tableHeaders"
            :addButton="false"
            :exportButton="false"
            :filterOptions="['pending', 'finished']">

            @forelse ($reports as $report)
                <tr>
                    <td style="max-width: 280px; padding-right: 20px;">
                        <p class="mb-2 text-dark fw-bold text-wrap" style="font-size: 14px;">
                            "{{ $report['report_message'] ?? 'Tidak ada pesan' }}"
                        </p>
                        
                        {{-- Logika Warna Badge Berdasarkan Kategori --}}
                        @php
                            $category = $report['report_category'] ?? 'Lainnya';
                            $badgeClass = 'bg-secondary-subtle text-secondary';
                            
                            if($category === 'Ujaran Kebencian' || $category === 'Pelecehan') $badgeClass = 'bg-danger-subtle text-danger border-danger-subtle';
                            elseif($category === 'Penipuan') $badgeClass = 'bg-success-subtle text-success border-success-subtle';
                            elseif($category === 'Ketidaknyamanan') $badgeClass = 'bg-warning-subtle text-warning border-warning-subtle';
                        @endphp
                        
                        <span class="badge {{ $badgeClass }} rounded-pill px-3 py-1 border" style="font-size: 11px;">
                            {{ $category }}
                        </span>
                    </td>

                    <td>
                        <div class="d-flex align-items-center gap-2">
                            {{-- Buat inisial bulat ala gambar desainmu --}}
                            <div class="bg-success-subtle text-success fw-bold rounded-circle d-flex justify-content-center align-items-center" style="width: 35px; height: 35px; font-size: 13px;">
                                {{ strtoupper(substr($report['terlapor']['username'] ?? 'U', 0, 2)) }}
                            </div>
                            <span class="fw-semibold text-dark text-wrap" style="max-width: 150px; font-size: 14px;">
                                {{ $report['terlapor']['username'] ?? 'User Dihapus' }}
                            </span>
                        </div>
                    </td>

                    <td class="text-dark" style="font-size: 14px;">
                        {{ $report['pelapor']['username'] ?? 'User Dihapus' }}
                    </td>

                    <td class="text-center">
                        <span class="badge bg-white border border-danger text-danger rounded-circle d-inline-flex justify-content-center align-items-center" style="width: 30px; height: 30px; font-size: 13px;">
                            1 {{-- Angka ini statis dulu sampai kita buat fungsi grouping di API --}}
                        </span>
                    </td>

                    <td class="text-success" style="font-size: 13px;">
                        {{ isset($report['created_at']) ? \Carbon\Carbon::parse($report['created_at'])->format('d M Y') : '-' }}
                    </td>

                    <td class="text-center">
                        <div class="d-flex flex-column gap-1 align-items-center justify-content-center">
                            <a href="#" class="text-success text-decoration-none fw-bold" style="font-size: 12px;">Lihat</a>
                            
                            @if(isset($report['report_status']) && $report['report_status'] === 'finished')
                                <span class="badge bg-secondary rounded-pill px-3 py-2 mt-1">Selesai</span>
                            @else
                                <form action="{{ route('report.tindak', $report['id'] ?? $report['_id']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menindak (suspend) target ini?');">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 py-1 fw-bold shadow-sm mt-1" style="font-size: 12px; background-color: #b91c1c; border: none;">
                                        Tindak
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        Semua aman! Belum ada laporan pelanggaran yang masuk.
                    </td>
                </tr>
            @endforelse

        <x-slot name="footer">
                {{-- TAMBAHAN: flex-column (untuk HP) dan flex-md-row (untuk Laptop), serta gap-3 biar ada jarak saat numpuk --}}
                <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-center bg-light gap-3" 
                    style="width: calc(100% + 40px); margin: 0 -20px -20px -20px; padding: 15px 20px; border-top: 1px solid #dee2e6; border-radius: 0 0 12px 12px;">
                    
                    {{-- TAMBAHAN: text-center di HP, text-md-start di Laptop --}}
                    <p class="pagination-info mb-0 text-muted text-center text-md-start">
                        Menampilkan <strong>{{ $reports->firstItem() ?? 0 }} - {{ $reports->lastItem() ?? 0 }}</strong> dari <strong>{{ $reports->total() }}</strong> user
                    </p>
                    
                    <nav>
                        <ul class="custom-pagination mb-0 justify-content-center flex-wrap">
                            
                            {{-- Tombol Previous --}}
                            @if ($reports->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link"><i class="bi bi-chevron-left"></i></span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $reports->previousPageUrl() }}"><i class="bi bi-chevron-left"></i></a>
                                </li>
                            @endif

                            {{-- Deretan Angka Halaman --}}
                            @foreach ($reports->getUrlRange(1, $reports->lastPage()) as $page => $url)
                                <li class="page-item {{ $page == $reports->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach

                            {{-- Tombol Next --}}
                            @if ($reports->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $reports->nextPageUrl() }}"><i class="bi bi-chevron-right"></i></a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link"><i class="bi bi-chevron-right"></i></span>
                                </li>
                            @endif
                            
                        </ul>
                    </nav>
                    
                </div>
            </x-slot>
        </x-data-table>
    </div>
</div>

@endsection