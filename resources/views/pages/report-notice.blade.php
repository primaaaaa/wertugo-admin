@extends('layouts.app')
@section('title', 'Report Notice')
@section('header', 'Report Notice')

@section('admin-content')

<div class="container-fluid p-4">
    {{-- TAMPILKAN PESAN SUKSES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- TAMPILKAN PESAN ERROR --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
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

                    <!-- 6. AKSI (TINDAK) -->
                    <td class="text-center">
                        <div class="d-flex flex-column gap-1 align-items-center justify-content-center">
                            <!-- <a href="#" class="text-success text-decoration-none fw-bold" style="font-size: 12px;">Lihat</a> -->
                            
                            @if(isset($report['report_status']) && $report['report_status'] === 'finished')
                                <span class="badge bg-secondary rounded-pill px-3 py-2 mt-1">Selesai</span>
                            @else
                                <!-- TOMBOL PEMICU MODAL (TETAP DI SINI) -->
                                <button type="button" class="btn btn-danger btn-sm rounded-pill px-3 py-1 fw-bold shadow-sm mt-1" 
                                        style="font-size: 12px; background-color: #b91c1c; border: none;" 
                                        data-bs-toggle="modal" data-bs-target="#tindakModal{{ $loop->iteration }}">
                                    Tindak
                                </button>
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


@foreach($reports as $report)
    @if(isset($report['report_status']) && $report['report_status'] !== 'finished')
        <!-- Perhatikan ID-nya pakai $loop->iteration -->
        <div class="modal fade" id="tindakModal{{ $loop->iteration }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 shadow-lg">
                    
                    <div class="modal-header border-bottom-0 pb-0 mt-2 px-4">
                        <h5 class="modal-title fw-bold text-dark" style="font-size: 1.1rem;">Tindak Lanjut Laporan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <form action="{{ route('report.tindak', $report['id'] ?? $report['_id']) }}" method="POST">
                        @csrf
                        
                        {{-- TITIPAN ID KOMENTAR (TIDAK TERLIHAT DI UI) --}}
                        <input type="hidden" name="comment_id" value="{{ $report['comment_id'] ?? '' }}">
                        
                        <div class="modal-body px-4 py-3 text-start">
                            <!-- Kotak Komentar yang Dilaporkan -->
                            <div class="bg-light rounded-3 p-3 mb-4 border" style="background-color: #f8fafc !important;">
                                <small class="text-success fw-bold d-block mb-2" style="font-size: 10px; letter-spacing: 0.5px; color: #2d6a4f !important;">
                                    KOMENTAR YANG DILAPORKAN
                                </small>
                                <p class="mb-0 text-dark fst-italic" style="font-size: 13px;">
                                    "{{ $report['report_message'] ?? 'Komentar tidak tersedia.' }}"
                                </p>
                            </div>

                            <!-- Dropdown Aksi Komentar -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark" style="font-size: 13px;">Aksi Komentar</label>
                                <select name="aksi_komentar" class="form-select form-select-sm bg-light border-0 py-2" style="font-size: 13px;">
                                    <option value="hapus">Hapus Komentar</option>
                                    <option value="biarkan">Biarkan Komentar (Abaikan)</option>
                                </select>
                            </div>

                            <!-- Dropdown Status Akun -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark" style="font-size: 13px;">Status Akun Komentator</label>
                                <select name="status_akun" class="form-select form-select-sm bg-light border-0 py-2" style="font-size: 13px;">
                                    <option value="aktif">Tetap Aktif</option>
                                    <option value="suspend">Suspend Akun</option>
                                </select>
                            </div>

                            <!-- Catatan Internal -->
                            <div class="mb-2">
                                <label class="form-label fw-bold text-dark" style="font-size: 13px;">
                                    Catatan Internal <span class="text-muted fw-normal">(Opsional)</span>
                                </label>
                                <textarea name="catatan_internal" class="form-control bg-light border-0" rows="3" 
                                          placeholder="Masukkan alasan atau catatan tambahan..." style="font-size: 13px;"></textarea>
                            </div>
                            
                        </div>
                        
                        <div class="modal-footer border-top-0 pt-0 px-4 pb-4 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light fw-bold text-success border-0 px-3" data-bs-dismiss="modal" style="color: #2d6a4f !important;">Batal</button>
                            <button type="submit" class="btn text-white rounded-pill px-4 fw-bold shadow-sm" style="background-color: #2d6a4f; font-size: 14px;">
                                Terapkan Tindakan
                            </button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection

