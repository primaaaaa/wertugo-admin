@extends('layouts.app')
@section('title', 'Kelola Verifikasi')
@section('header', 'Kelola Verifikasi')

@section('admin-content')

<style>
    /* Custom Styling untuk menyempurnakan Bootstrap */
    .wertugo-text-green { color: #0c7b1b; }
    .wertugo-bg-green { background-color: #0c7b1b; }
    .wertugo-btn-green { background-color: #0c7b1b; color: white; border: none; }
    .wertugo-btn-green:hover { background-color: #096115; color: white; }
    
    .card-pending {
        border: 1px solid #e2e8f0;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card-pending:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        border-color: #0c7b1b;
    }
    .icon-circle {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: #e6f4ea;
        color: #0c7b1b;
        font-size: 24px;
    }
</style>

<div class="container-fluid py-4 px-4">
    
    <div class="mb-5">
        <h3 class="fw-bold text-dark mb-2">Kelola Verifikasi</h3>
        <p class="text-muted" style="font-size: 15px;">
            Tinjau permohonan pendaftaran usaha baru dan riwayat verifikasi untuk menjaga kualitas komunitas Wertugo.
        </p>
    </div>

    <div class="mb-5">
        {{-- CEK APAKAH ADA DATA ANTREAN, JIKA ADA BARU TAMPILKAN HEADERNYA --}}
        @if(isset($stats['total_verification_pending']) && $stats['total_verification_pending'] > 0)
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="fw-bold mb-0">Menunggu Verifikasi</h5>
                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3">
                        {{ $stats['total_verification_pending'] }} Tertunda
                    </span>
                </div>
                <a href="#" class="btn btn-outline-success rounded-pill px-4" style="color: #0c7b1b; border-color: #0c7b1b;">
                    Lihat Semua Antrean
                </a>
            </div>
        @endif

        <div class="row g-4">
            {{-- LOOPING CARD PENDING --}}
            @forelse($pendingCards as $pending)
                <div class="col-md-4">
                    <div class="card card-pending rounded-4 h-100 p-3 position-relative">
                        
                        <div class="d-flex align-items-center gap-3 mb-4 mt-2">
                            <div class="icon-circle">
                                <i class="bi bi-shop"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">
                                    {{ $pending['umkm']['username'] ?? 'Nama Tidak Tersedia' }}
                                </h6>
                                <small class="text-muted">UMKM Baru</small>
                            </div>
                        </div>

                        <div class="mb-4" style="font-size: 13px;">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Email</span>
                                <span class="fw-semibold text-dark">
                                    {{ $pending['umkm']['email'] ?? '-' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Dikirim</span>
                                <span class="fw-semibold text-dark">
                                    {{ isset($pending['created_at']) ? \Carbon\Carbon::parse($pending['created_at'])->format('d M Y, H:i') : 'Tanggal tidak tersedia' }}
                                </span>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-auto">
                            <button class="btn btn-light rounded-pill flex-grow-1 text-muted fw-semibold border">Detail</button>
                            <form action="{{ route('umkm.verify', $pending['id'] ?? $pending['_id']) }}" method="POST" class="flex-grow-1 d-flex">
                                @csrf
                                <button type="submit" class="btn wertugo-btn-green rounded-pill w-100 fw-semibold">Verifikasi</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 bg-light rounded-4 border">
                    <i class="bi bi-check-circle-fill text-success fs-1 mb-2 d-block"></i>
                    <h6 class="fw-bold text-dark">Semua Beres!</h6>
                    <p class="text-muted mb-0">Tidak ada antrean verifikasi UMKM saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-4">
        <x-data-table
            title="Riwayat Verifikasi"
            :data="$historyData"
            :headers="['Nama UMKM', 'Email Pemilik', 'Tanggal Dibuat', 'Status Verifikasi', 'Aksi']"
            :addButton="false"
            :exportButton="false"
            :filterOptions="['verified', 'pending', 'rejected']">

            @forelse ($historyData as $history)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light text-secondary rounded-circle d-flex justify-content-center align-items-center border" style="width: 35px; height: 35px;">
                                <i class="bi bi-shop"></i>
                            </div>
                            <span class="fw-bold text-dark">
                                {{ $history['umkm']['username'] ?? 'User Dihapus' }}
                            </span>
                        </div>
                    </td>
                    
                    <td class="text-dark">
                        {{ $history['umkm']['email'] ?? '-' }}
                    </td>
                    
                    <td class="text-muted">
                        {{ isset($history['created_at']) ? \Carbon\Carbon::parse($history['created_at'])->format('d M Y') : '-' }}
                    </td>
                    
                    <td>
                        @if(isset($history['verification_status']) && $history['verification_status'] === 'verified')
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-semibold">
                                Terverifikasi
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-2 fw-semibold">
                                {{ ucfirst($history['verification_status'] ?? 'Pending') }}
                            </span>
                        @endif
                    </td>
                    
                    <td>
                        {{-- Tombol Aksi ngikutin gaya daftar user kamu --}}
                        <a href="#" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="#" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">Belum ada riwayat verifikasi yang tercatat.</td>
                </tr>
            @endforelse

            {{-- PAGINATION INJECT KE SLOT FOOTER --}}
            <x-slot name="footer">
                <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-center bg-light gap-3" 
                     style="width: calc(100% + 40px); margin: 0 -20px -20px -20px; padding: 15px 20px; border-top: 1px solid #dee2e6; border-radius: 0 0 12px 12px;">
                    
                    <p class="pagination-info mb-0 text-muted text-center text-md-start">
                        Menampilkan <strong>{{ $historyData->firstItem() ?? 0 }} - {{ $historyData->lastItem() ?? 0 }}</strong> dari <strong>{{ $historyData->total() }}</strong> entri
                    </p>
                    
                    <nav>
                        <ul class="custom-pagination mb-0 justify-content-center flex-wrap">
                            
                            {{-- Tombol Previous --}}
                            @if ($historyData->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link"><i class="bi bi-chevron-left"></i></span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $historyData->previousPageUrl() }}"><i class="bi bi-chevron-left"></i></a>
                                </li>
                            @endif

                            {{-- Deretan Angka Halaman --}}
                            @foreach ($historyData->getUrlRange(1, $historyData->lastPage()) as $page => $url)
                                <li class="page-item {{ $page == $historyData->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach

                            {{-- Tombol Next --}}
                            @if ($historyData->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $historyData->nextPageUrl() }}"><i class="bi bi-chevron-right"></i></a>
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