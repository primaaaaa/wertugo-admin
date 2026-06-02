@extends('layouts.app')
@section('title', 'Detail UMKM')
@section('header', 'Detail UMKM')

@section('admin-content')

<style>
    .hero-banner {
        position: relative;
        height: 280px;
        border-radius: 24px;
        overflow: hidden;
        background-color: #2d3748;
        background-image: url('https://images.unsplash.com/photo-1498837167922-c77f271667c4?q=80&w=2070&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
    }

    .hero-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.2) 60%, rgba(0,0,0,0) 100%);
    }

    .hero-content {
        position: absolute;
        bottom: 0; left: 0; width: 100%;
        padding: 30px; color: white;
    }

    .badge-custom {
        padding: 6px 12px; border-radius: 50px;
        font-size: 11px; font-weight: 700;
        letter-spacing: 0.5px; display: inline-flex;
        align-items: center; gap: 4px;
    }

    .bg-green-primary { background-color: #0c7b1b; color: white; }
    .bg-blue-verified { background-color: #1d4ed8; color: white; }
    .bg-dark-category { background-color: rgba(0,0,0,0.6); color: white; backdrop-filter: blur(4px); }

    .card-rounded {
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .icon-box-light {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        background-color: #f1f5f9; color: #64748b; font-size: 16px;
    }

    .icon-box-green {
        width: 32px; height: 32px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background-color: #e6f4ea; color: #0c7b1b; font-size: 14px;
    }

    .gallery-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 12px;
    }
    
    .gallery-img {
        width: 100%; height: 120px; object-fit: cover; border-radius: 16px;
    }

    .gallery-img.wide {
        grid-column: span 2; height: 140px;
    }

    .report-alert-box {
        border-left: 4px solid #dc2626;
        background-color: #fef2f2;
        color: #991b1b;
    }
</style>

@php
    $profil = $detail['profil_umkm'] ?? [];
    $pemilik = $detail['pemilik'] ?? [];
    $keamanan = $detail['keamanan'] ?? [];
    $ulasan = $detail['ulasan'] ?? [];
    $galeri = $profil['katalog_galeri'] ?? [];
@endphp

<div class="container-fluid py-4 px-4">
    
    @if(isset($keamanan['total_laporan']) && $keamanan['total_laporan'] > 0)
        <div class="alert report-alert-box rounded-4 p-3 mb-4 shadow-sm d-flex align-items-start gap-3">
            <i class="bi bi-shield-slash-fill fs-4 text-danger mt-1"></i>
            <div>
                <h6 class="fw-bold mb-1">Pemberitahuan Keamanan (Mitra dalam Pengawasan)</h6>
                <p class="mb-2 small opacity-90">
                    Tempat usaha ini telah dilaporkan sebanyak <strong>{{ $keamanan['total_laporan'] }} kali</strong> oleh pengguna aplikasi.
                </p>
                @if($keamanan['pesan_laporan_terbaru'])
                    <div class="bg-white bg-opacity-50 rounded-3 p-2 small fst-italic text-dark border border-danger-subtle">
                        "{{ $keamanan['pesan_laporan_terbaru'] }}"
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="hero-banner mb-4 shadow-sm">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="d-flex gap-2 mb-3">
                <span class="badge-custom bg-dark-category">KULINER</span>
                
                @if(isset($profil['is_open']) && $profil['is_open'])
                    <span class="badge-custom bg-green-primary">
                        <i class="bi bi-circle-fill" style="font-size: 6px;"></i> Active
                    </span>
                @else
                    <span class="badge-custom bg-secondary text-white">
                        <i class="bi bi-circle-fill" style="font-size: 6px;"></i> Closed
                    </span>
                @endif
                
                <span class="badge-custom bg-blue-verified">
                    <i class="bi bi-patch-check-fill"></i> Verified
                </span>
            </div>
            
            <h1 class="fw-bold mb-2" style="font-size: 2.2rem;">{{ $profil['nama_usaha'] ?? 'Nama UMKM' }}</h1>
            
            <div class="d-flex align-items-center gap-3 text-light opacity-75" style="font-size: 13px;">
                <span><i class="bi bi-geo-alt-fill me-1"></i> {{ str()->limit($profil['lokasi'] ?? 'Lokasi belum diatur', 50) }}</span>
                <span>
                    <i class="bi bi-star-fill text-warning me-1"></i> 
                    {{ number_format($profil['rating_avg'] ?? 0, 1) }} ({{ $profil['total_ulasan'] ?? 0 }} ulasan)
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-5">
            <div class="card card-rounded h-100 p-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <h5 class="fw-bold text-dark mb-0">Informasi<br>Pemilik</h5>
                        <div class="icon-box-green"><i class="bi bi-person-fill"></i></div>
                    </div>

                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-box-light flex-shrink-0"><i class="bi bi-person-badge"></i></div>
                        <div>
                            <small class="text-muted d-block" style="font-size: 11px;">Nama Lengkap</small>
                            <span class="fw-semibold text-dark" style="font-size: 14px;">{{ $pemilik['username'] ?? 'User Dihapus' }}</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-box-light flex-shrink-0"><i class="bi bi-envelope"></i></div>
                        <div class="text-truncate">
                            <small class="text-muted d-block" style="font-size: 11px;">Alamat Email</small>
                            <span class="fw-semibold text-dark" style="font-size: 14px;">{{ $pemilik['email'] ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box-light flex-shrink-0"><i class="bi bi-telephone"></i></div>
                        <div>
                            <small class="text-muted d-block" style="font-size: 11px;">Nomor Telepon</small>
                            <span class="fw-semibold text-dark" style="font-size: 14px;">
                                {{ $profil['media_sosial']['whatsapp'] ?? 'Belum diatur' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7">
            <div class="card card-rounded h-100 p-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <h5 class="fw-bold text-dark mb-0">Lokasi Bisnis</h5>
                        <div class="icon-box-green"><i class="bi bi-geo-alt-fill"></i></div>
                    </div>

                    <div class="d-flex gap-3">
                        <div class="icon-box-light flex-shrink-0 text-success bg-success-subtle"><i class="bi bi-map"></i></div>
                        <div>
                            <small class="text-muted d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">ALAMAT LENGKAP</small>
                            <h6 class="fw-bold text-dark lh-base mb-2" style="font-size: 15px;">
                                {{ $profil['lokasi'] ?? 'Alamat belum diatur oleh pemilik.' }}
                            </h6>
                            <p class="text-muted mb-0" style="font-size: 12px;">
                                Negara asal pendaftaran akun: <strong class="text-success">{{ $pemilik['country'] ?? 'Indonesia' }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-rounded p-3 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <h5 class="fw-bold text-dark mb-0">Tentang {{ $profil['nama_usaha'] ?? 'UMKM' }}</h5>
                <div class="icon-box-green"><i class="bi bi-file-earmark-text-fill"></i></div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-lg-7">
                    <p class="text-dark lh-lg" style="font-size: 14.5px; text-align: justify;">
                        @if(!empty($profil['deskripsi']))
                            {{ $profil['deskripsi'] }}
                        @else
                            <span class="text-muted fst-italic">Pemilik belum menambahkan deskripsi untuk tempat usaha ini.</span>
                        @endif
                    </p>
                </div>

                <div class="col-12 col-lg-5">
                    @if(count($galeri) > 0)
                        <div class="gallery-grid">
                            <img src="{{ $galeri[0] }}" class="gallery-img shadow-sm" alt="Gallery 1">
                            @if(isset($galeri[1]))
                                <img src="{{ $galeri[1] }}" class="gallery-img shadow-sm" alt="Gallery 2">
                            @endif
                            @if(isset($galeri[2]))
                                <img src="{{ $galeri[2] }}" class="gallery-img wide shadow-sm mt-2" alt="Gallery 3">
                            @endif
                        </div>
                    @else
                        <div class="gallery-grid">
                            <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=500&auto=format&fit=crop" class="gallery-img shadow-sm" alt="Placeholder 1">
                            <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=500&auto=format&fit=crop" class="gallery-img shadow-sm" alt="Placeholder 2">
                            <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=800&auto=format&fit=crop" class="gallery-img wide shadow-sm mt-2" alt="Placeholder 3">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card card-rounded p-3 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="icon-box-green"><i class="bi bi-chat-left-heart-fill"></i></div>
                    <h5 class="fw-bold mb-0 text-dark">Ulasan Pelanggan Terbaru</h5>
                </div>
                <span class="badge bg-light border text-secondary rounded-pill px-3 py-2 fw-medium" style="font-size: 12px;">
                    Total {{ count($ulasan) }} Komentar
                </span>
            </div>

            <div class="row g-3">
                @forelse($ulasan as $review)
                    <div class="col-12 col-md-6">
                        <div class="card border rounded-4 h-100 bg-light bg-opacity-25">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-success-subtle text-success fw-bold rounded-circle d-flex justify-content-center align-items-center" style="width: 32px; height: 32px; font-size: 11px;">
                                            {{ strtoupper(substr($review['user']['username'] ?? 'U', 0, 2)) }}
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0" style="font-size: 13.5px;">
                                                {{ $review['user']['username'] ?? 'Pengguna Anonim' }}
                                            </h6>
                                            <small class="text-muted" style="font-size: 11px;">
                                                {{ isset($review['created_at']) ? \Carbon\Carbon::parse($review['created_at'])->format('d M Y') : '-' }}
                                            </small>
                                        </div>
                                    </div>
                                    
                                    {{-- SISTEM BINTANG ULASAN DINAMIS --}}
                                    <div class="text-warning" style="font-size: 11px;">
                                        @php 
                                            $rating = $review['rating'] ?? 5; 
                                        @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $rating)
                                                <i class="bi bi-star-fill"></i>
                                            @else
                                                <i class="bi bi-star" style="color: #cbd5e1;"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-secondary mb-0 fst-italic" style="font-size: 13px; line-height: 1.5;">
                                    "{{ $review['content'] ?? '' }}"
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="bi bi-chat-left-x fs-2 d-block mb-2 opacity-50"></i>
                        <p class="mb-0 small">Belum ada ulasan atau penilaian yang masuk untuk mitra ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@endsection