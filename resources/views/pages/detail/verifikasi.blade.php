@extends('layouts.app')
@section('title', 'Detail Verifikasi')
@section('header', 'Detail Verifikasi')

@section('admin-content')

<style>
    .card-rounded {
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        background-color: #ffffff;
    }
    
    .doc-bar-uploaded {
        background-color: #065f10; /* Hijau gelap khas desainmu */
        color: white;
        border-radius: 50px;
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-decoration: none;
        transition: 0.2s;
    }
    .doc-bar-uploaded:hover {
        background-color: #04420b;
        color: white;
    }
    
    .doc-bar-empty {
        background-color: transparent;
        color: #94a3b8;
        border: 2px dashed #cbd5e1;
        border-radius: 50px;
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .icon-circle-green {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #0c7b1b;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    /* Profil Card UI */
    .profile-img-header {
        height: 140px;
        width: 100%;
        object-fit: cover;
        border-radius: 20px 20px 0 0;
    }
    .profile-card-body {
        background-color: #eef2ff; /* Biru sangat muda pudar */
        border-radius: 0 0 20px 20px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        border-top: none;
    }
    
    /* Progress Card UI */
    .progress-card {
        background-color: #0c7b1b;
        border-radius: 20px;
        padding: 24px;
        color: white;
    }
    .progress-bar-custom {
        height: 10px;
        background-color: #3b9e47;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 8px;
        margin-bottom: 8px;
    }
    .progress-bar-fill {
        height: 100%;
        background-color: #86efac; /* Hijau muda terang */
        border-radius: 10px;
    }

    .btn-kembali {
        background-color: #64748b;
        color: white;
        border-radius: 50px;
        padding: 10px 24px;
        font-weight: 600;
        border: none;
    }
    .btn-kembali:hover { background-color: #475569; color: white; }
    
    .btn-konfirmasi {
        background-color: #0c7b1b;
        color: white;
        border-radius: 50px;
        padding: 10px 24px;
        font-weight: 600;
        border: none;
    }
    .btn-konfirmasi:hover { background-color: #095c14; color: white; }
</style>

@php
    $umkm = $detail['umkm'] ?? [];
    $pemilik = $detail['pemilik'] ?? [];
    $dokumen = $detail['dokumen'] ?? [];
    $progress = $detail['progress'] ?? ['terunggah' => 0, 'total' => 0, 'persentase' => 0];
@endphp

<div class="container-fluid py-4 px-4">
    
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">Verifikasi Dokumen</h2>
        <div class="d-flex align-items-center gap-2">
            <span class="fw-bold text-secondary" style="font-size: 18px;">{{ $umkm['nama_usaha'] ?? 'Nama UMKM' }}</span>
            
            @if($detail['status'] === 'pending')
                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1" style="font-size: 10px; letter-spacing: 0.5px;">SEDANG DITINJAU</span>
            @elseif($detail['status'] === 'verified')
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1" style="font-size: 10px; letter-spacing: 0.5px;">TERVERIFIKASI</span>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card card-rounded shadow-sm p-4 h-100">
                <div class="d-flex align-items-start gap-3 mb-4 border-bottom pb-4">
                    <div class="icon-circle-green flex-shrink-0">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Checklist Dokumen</h5>
                        <p class="text-muted small mb-0">Pastikan semua dokumen valid dan masih berlaku sesuai hukum yang ada.</p>
                    </div>
                </div>

                <div class="d-flex flex-column gap-4">
                    @foreach($dokumen as $doc)
                        <div>
                            <p class="text-dark fw-medium mb-2" style="font-size: 13px;">{{ $doc['nama'] }}</p>
                            
                            @if($doc['file'])
                                {{-- JIKA DOKUMEN ADA --}}
                                <a href="{{ env('WERTUGO_API') . '/storage/' . $doc['file'] }}" target="_blank" class="doc-bar-uploaded mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-image"></i>
                                        <span style="font-size: 14px;">{{ basename($doc['file']) }}</span>
                                    </div>
                                    <i class="bi {{ $doc['tipe_aksi'] == 'download' ? 'bi-download' : 'bi-eye' }} fs-5"></i>
                                </a>
                                <small class="text-success fw-bold" style="font-size: 11px;"><i class="bi bi-check-circle me-1"></i> Status: Terunggah</small>
                            @else
                                {{-- JIKA DOKUMEN KOSONG --}}
                                <div class="doc-bar-empty mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-file-earmark-plus"></i>
                                        <span style="font-size: 14px;">Belum diunggah</span>
                                    </div>
                                    <i class="bi bi-info-circle fs-5"></i>
                                </div>
                                <small class="text-muted" style="font-size: 11px;">
                                    <i class="bi bi-info-circle me-1"></i> Status: {{ $doc['is_required'] ? 'Wajib Diunggah' : 'Opsional' }}
                                </small>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 pt-4 border-top d-flex gap-3">
                    <a href="{{ route('verifikasi.pending') }}" class="btn-kembali text-decoration-none d-inline-flex align-items-center justify-content-center flex-grow-1">
                        <i class="bi bi-arrow-left me-2"></i> Kembali
                    </a>
                    
                    {{-- Form Konfirmasi Verifikasi (Menembak rute yang sudah kita buat kemarin) --}}
                    <form action="{{ route('umkm.verify', $detail['id_verifikasi']) }}" method="POST" class="flex-grow-1 d-flex">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn-konfirmasi w-100" onclick="return confirm('Yakin ingin memverifikasi dokumen ini?');">
                            <i class="bi bi-patch-check-fill me-2"></i> Konfirmasi Verifikasi
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 d-flex flex-column gap-4">
            
            <div class="card card-rounded shadow-sm border-0 bg-transparent">
                {{-- Foto Sampul UMKM --}}
                @php 
                    $sampul = !empty($umkm['katalog_galeri']) ? $umkm['katalog_galeri'][0] : 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=500&auto=format&fit=crop';
                @endphp
                <img src="{{ $sampul }}" alt="Sampul UMKM" class="profile-img-header">
                
                <div class="profile-card-body">
                    <h5 class="fw-bold text-dark mb-1">{{ $umkm['nama_usaha'] ?? 'Nama UMKM' }}</h5>
                    <p class="text-muted small mb-4"><i class="bi bi-geo-alt me-1"></i> {{ str()->limit($umkm['lokasi'] ?? 'Lokasi tidak tersedia', 40) }}</p>
                    
                    <div class="bg-white rounded-4 p-3 shadow-sm border">
                        <small class="text-dark fw-bold d-block mb-2" style="font-size: 11px;">Data Pemilik</small>
                        <p class="text-dark fw-medium mb-0" style="font-size: 14px;">{{ $pemilik['username'] ?? 'User Dihapus' }}</p>
                        <p class="text-muted mb-0" style="font-size: 12px;">{{ $pemilik['email'] ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="progress-card shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <small class="fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">PROGRES VERIFIKASI</small>
                    <h3 class="fw-bold mb-0">{{ $progress['terunggah'] }}/{{ $progress['total'] }}</h3>
                </div>
                
                <div class="progress-bar-custom">
                    <div class="progress-bar-fill" style="width: {{ $progress['persentase'] }}%;"></div>
                </div>
                
                @if($progress['terunggah'] == $progress['total'])
                    <p class="mb-0 mt-3" style="font-size: 12px; line-height: 1.6; opacity: 0.9;">
                        Seluruh dokumen telah lengkap. Silakan tinjau keabsahan dokumen sebelum melakukan konfirmasi verifikasi.
                    </p>
                @else
                    <p class="mb-0 mt-3" style="font-size: 12px; line-height: 1.6; opacity: 0.9;">
                        Satu atau beberapa dokumen opsional belum diunggah. UMKM tetap dapat mencapai verifikasi 100% jika dokumen wajib terpenuhi.
                    </p>
                @endif
            </div>

        </div>
    </div>
</div>

@endsection