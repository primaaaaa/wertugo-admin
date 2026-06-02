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

    {{-- PESAN NOTIFIKASI SUKSES/ERROR --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
                <a href="/admin/verifikasi/pending" class="btn btn-outline-success rounded-pill px-4" style="color: #0c7b1b; border-color: #0c7b1b;">
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
                                <h6 class="fw-bold mb-1 text-truncate" style="max-width: 200px;">
                                    {{ $pending['umkm']['nama_usaha'] ?? 'Nama Usaha Kosong' }}
                                </h6>
                                <small class="text-muted">Pemilik: {{ $pending['umkm']['user']['username'] ?? '-' }}</small>
                            </div>
                        </div>

                        <div class="mb-4" style="font-size: 13px;">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Email</span>
                                <span class="fw-semibold text-dark text-truncate" style="max-width: 150px;">
                                    {{ $pending['umkm']['user']['email'] ?? '-' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Dikirim</span>
                                <span class="fw-semibold text-dark">
                                    {{ isset($pending['created_at']) ? \Carbon\Carbon::parse($pending['created_at'])->format('d M Y, H:i') : '-' }}
                                </span>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-auto">
                            <a href="{{ route('verifikasi.detail', $pending['id'] ?? $pending['_id']) }}" class="btn btn-light rounded-pill flex-grow-1 text-muted fw-semibold border">Detail</a>
                            <form action="{{ route('umkm.verify', $pending['id'] ?? $pending['_id']) }}" method="POST" class="flex-grow-1 d-flex">
                                @csrf
                                @method('PUT') {{-- Pastikan method PUT terbaca --}}
                                <button type="submit" class="btn wertugo-btn-green rounded-pill w-100 fw-semibold" onclick="return confirm('Yakin ingin memverifikasi UMKM ini?');">Verifikasi</button>
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
            :data="$historyTable"
            :headers="['Profil UMKM', 'Email Pemilik', 'Tanggal Dibuat', 'Status Verifikasi', 'Aksi']"
            :addButton="false"
            :exportButton="false"
            :filterOptions="['verified', 'pending', 'rejected']">

            @forelse ($historyTable as $index => $history)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light text-secondary rounded-circle d-flex justify-content-center align-items-center border flex-shrink-0" style="width: 35px; height: 35px;">
                                <i class="bi bi-shop"></i>
                            </div>
                            <div class="d-flex flex-column lh-sm">
                                <span class="fw-bold text-dark">
                                    {{ $history['umkm']['nama_usaha'] ?? 'Data UMKM Dihapus' }}
                                </span>
                                <small class="text-muted" style="font-size: 12px;">
                                    Oleh: {{ $history['umkm']['user']['username'] ?? '-' }}
                                </small>
                            </div>
                        </div>
                    </td>
                    
                    <td class="text-dark">
                        {{ $history['umkm']['user']['email'] ?? '-' }}
                    </td>
                    
                    <td class="text-muted">
                        {{ isset($history['created_at']) ? \Carbon\Carbon::parse($history['created_at'])->format('d M Y') : '-' }}
                    </td>
                    
                    <td>
                        @if(isset($history['verification_status']) && $history['verification_status'] === 'verified')
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-semibold">
                                Terverifikasi
                            </span>
                        @elseif(isset($history['verification_status']) && $history['verification_status'] === 'rejected')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2 fw-semibold">
                                Ditolak
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-2 fw-semibold">
                                Pending
                            </span>
                        @endif
                    </td>
                    
                    <td>
                        <a href="{{ route('verifikasi.detail', $history['id'] ?? $history['_id']) }}" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-eye"></i>
                        </a>
                        <!-- <a href="#" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </a> -->
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
                        Menampilkan <strong>{{ $historyTable->firstItem() ?? 0 }} - {{ $historyTable->lastItem() ?? 0 }}</strong> dari <strong>{{ $historyTable->total() }}</strong> entri
                    </p>
                    
                    @if(isset($historyTable) && $historyTable->hasPages())
                        <nav>
                            {{ $historyTable->links('pagination::bootstrap-5') }}
                        </nav>
                    @endif
                </div>
            </x-slot>
            
        </x-data-table>
    </div>
</div>

@endsection