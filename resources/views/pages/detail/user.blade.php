@extends('layouts.app')
@section('title', 'Detail User')
@section('header', 'Detail User')

@section('admin-content')

<style>
    /* Styling khusus agar mirip desain figma/UI kamu */
    .bg-gradient-light-green {
        background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
    }
    
    .card-rounded {
        border-radius: 20px;
        border: 1px solid #e2e8f0;
    }

    .report-badge-container {
        width: 100px;
        height: 120px;
        border: 4px solid #fecaca; /* Warna merah muda pudar */
        border-radius: 50px 50px 20px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        background-color: white;
    }

    .report-badge-container::before {
        content: '';
        position: absolute;
        top: -4px;
        left: -4px;
        right: -4px;
        height: 50%;
        border: 4px solid #dc2626; /* Merah tua untuk lengkungan atas */
        border-bottom: none;
        border-radius: 50px 50px 0 0;
    }

    .quote-box {
        border-left: 3px solid #dc2626;
        background-color: #fef2f2;
        padding: 15px 20px;
        border-radius: 0 12px 12px 0;
        font-style: italic;
        color: #b91c1c;
        font-size: 13px;
        position: relative;
        overflow: hidden;
    }

    /* Elemen dekorasi air di belakang quote (opsional) */
    .quote-box::after {
        content: '\F5CC'; /* Ikon shield bi-shield-fill dari bootstrap icons */
        font-family: bootstrap-icons;
        position: absolute;
        right: -10px;
        bottom: -20px;
        font-size: 80px;
        color: #fecaca;
        opacity: 0.3;
        z-index: 0;
    }
    
    .quote-text {
        position: relative;
        z-index: 1;
    }
</style>

<div class="container-fluid py-4 px-4">
    
    <!-- BARIS 1: HEADER PROFIL -->
    <div class="card card-rounded bg-gradient-light-green shadow-sm mb-4">
        <div class="card-body p-4 d-flex align-items-center gap-4 flex-wrap">
            <div class="position-relative">
                {{-- Gunakan default image jika foto_profil dari db kosong atau tidak valid --}}
                <x-default-profile-user :foto="$user['foto_profil'] ?? null" pages="detail-user" />
            </div>

            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-3 mb-1">
                    <h3 class="fw-bold text-dark mb-0">{{ $detail['profil']['username'] }}</h3>
                    
                    {{-- Status Badge --}}
                    @if($detail['profil']['account_status'] === 'active')
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                            <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> Aktif
                        </span>
                    @else
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1">
                            <i class="bi bi-slash-circle me-1" style="font-size: 10px;"></i> Suspended
                        </span>
                    @endif
                </div>
                
                <p class="text-muted mb-3">{{ $detail['profil']['email'] }}</p>
                
                {{-- Tombol Suspend --}}
                @if($detail['profil']['account_status'] === 'active')
                    <form action="#" method="POST" class="d-inline"> {{-- Route untuk suspend disiapkan di sini --}}
                        @csrf
                        <button class="btn bg-danger-subtle text-danger fw-bold rounded-pill border-0 px-4 py-2" style="font-size: 14px;">
                            <i class="bi bi-ban me-1"></i> Suspend User
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- BARIS 2: INFORMASI & KEAMANAN -->
    <div class="row g-4 mb-4">
        <!-- 2A. Kolom Informasi User -->
        <div class="col-12 col-md-4">
            <div class="card card-rounded shadow-sm h-100 p-2">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <div class="bg-success-subtle text-success rounded px-2 py-1">
                            <i class="bi bi-person-vcard fs-5"></i>
                        </div>
                        <h6 class="fw-bold mb-0 text-dark">Informasi User</h6>
                    </div>

                    <div class="mb-3 pb-3 border-bottom">
                        <small class="text-muted fw-bold d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">ID AKUN</small>
                        <span class="fw-semibold text-dark">
                            #USR-{{ strtoupper(substr($detail['profil']['id'], -8)) }}
                        </span>
                    </div>

                    <div class="mb-3 pb-3 border-bottom">
                        <small class="text-muted fw-bold d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">TANGGAL BERGABUNG</small>
                        <span class="fw-semibold text-dark">
                            {{ isset($detail['profil']['created_at']) ? \Carbon\Carbon::parse($detail['profil']['created_at'])->format('d M Y') : '-' }}
                        </span>
                    </div>

                    <div class="mb-3 pb-3 border-bottom">
                        <small class="text-muted fw-bold d-block mb-2" style="font-size: 11px; letter-spacing: 0.5px;">ROLE</small>
                        <span class="badge bg-light border text-secondary rounded-pill px-3 py-1 fw-medium">
                            {{ ucfirst($detail['profil']['role']) }}
                        </span>
                    </div>

                    <div>
                        <small class="text-muted fw-bold d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">TOTAL TRIP DIRENCANAKAN</small>
                        <span class="fw-semibold text-dark">
                            {{ $detail['profil']['total_trip'] ?? 0 }} Destinasi
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2B. Kolom Keamanan & Laporan -->
        <div class="col-12 col-md-8">
            <div class="card card-rounded shadow-sm h-100 p-2">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <div class="bg-danger-subtle text-danger rounded px-2 py-1">
                            <i class="bi bi-shield-exclamation fs-5"></i>
                        </div>
                        <h6 class="fw-bold mb-0 text-dark">Keamanan & Laporan</h6>
                    </div>

                    <div class="d-flex align-items-center flex-wrap gap-4 mt-2">
                        <!-- Indikator Angka Merah -->
                        <div class="report-badge-container shadow-sm flex-shrink-0">
                            <h2 class="fw-bold text-danger mb-0" style="font-size: 2.5rem;">{{ $detail['keamanan']['total_laporan'] }}</h2>
                            <small class="text-danger fw-bold" style="font-size: 10px; letter-spacing: 1px;">LAPORAN</small>
                        </div>

                        <!-- Pesan Teks -->
                        <div class="flex-grow-1">
                            <p class="text-dark fw-medium mb-3" style="font-size: 15px;">
                                Akun ini telah menerima {{ $detail['keamanan']['total_laporan'] }} laporan dari pemilik UMKM atau sistem terkait pelanggaran kebijakan.
                            </p>
                            
                            @if($detail['keamanan']['pesan_laporan_terbaru'])
                                <div class="quote-box">
                                    <span class="quote-text">"{{ $detail['keamanan']['pesan_laporan_terbaru'] }}"</span>
                                    <br>
                                    <small class="text-danger opacity-75 mt-2 d-block">— Laporan Terbaru</small>
                                </div>
                            @else
                                <div class="alert alert-success border-0 py-2">
                                    <i class="bi bi-check-circle me-2"></i> Belum ada catatan pelanggaran.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BARIS 3: RIWAYAT REVIEW/KOMENTAR -->
    <div class="card card-rounded shadow-sm p-2 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-success-subtle text-success rounded px-2 py-1">
                        <i class="bi bi-chat-square-text fs-5"></i>
                    </div>
                    <h6 class="fw-bold mb-0 text-dark">Riwayat Review/Komentar</h6>
                </div>
                <span class="badge bg-light border text-secondary rounded-pill px-3 py-2 fw-medium">
                    Total: {{ count($detail['komentar']) }} Review
                </span>
            </div>

            <!-- Grid Review Cards -->
            <div class="row g-3">
                @forelse($detail['komentar'] as $komen)
                    <div class="col-12 col-md-6">
                        <div class="card border rounded-4 h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="fw-bold text-success mb-1">{{ $komen['umkm']['username'] ?? 'UMKM Tidak Tersedia' }}</h6>
                                        <small class="text-muted" style="font-size: 12px;">
                                            Dikomentari pada {{ isset($komen['created_at']) ? \Carbon\Carbon::parse($komen['created_at'])->format('d M Y') : '-' }}
                                        </small>
                                    </div>
                                    <!-- Asumsi rating 5 bintang, bisa disesuaikan kalau ada field rating -->
                                    <div class="text-warning" style="font-size: 12px;">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                    </div>
                                </div>
                                <p class="text-dark mt-3 mb-0" style="font-size: 14px;">
                                    "{{ $komen['content'] }}"
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4">
                        <p class="text-muted">User ini belum pernah menulis review atau komentar. 💬</p>
                    </div>
                @endforelse
            </div>

            @if(count($detail['komentar']) > 0)
                <div class="text-center mt-4">
                    <button class="btn btn-outline-success rounded-pill px-4 py-2 fw-bold" style="font-size: 14px;">
                        Lihat Lebih Banyak <i class="bi bi-chevron-down ms-1"></i>
                    </button>
                </div>
            @endif
        </div>
    </div>

</div>

@endsection