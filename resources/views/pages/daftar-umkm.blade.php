@extends('layouts.app')
@section('title', 'Daftar UMKM')
@section('header', 'Manajemen UMKM')
@section('admin-content')

<div class="container-fluid p-4">
    <div class="mb-5">
        <h3 class="fw-bold text-dark mb-2">Daftar UMKM</h3>
        <p class="text-muted" style="font-size: 15px;">
            Kelola dan pantau seluruh mitra UMKM yang terdaftar di Wertugo.
        </p>
    </div>
    <div class="row g-4">
        
        <div class="col-12 col-sm-6 col-xl-4">
            <x-stat-card icon="bi-shop" iconColor="success" cardTitle="Total UMKM Aktif" :data="$stats['total_umkm'] ?? 0"></x-stat-card>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <x-stat-card icon="bi-check-circle-fill" iconColor="primary" cardTitle="Total UMKM Terverifikasi" :data="$stats['verified_umkm'] ?? 0"></x-stat-card>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <x-stat-card icon="bi-x-circle-fill" iconColor="danger" cardTitle="UMKM Ter-suspend" :data="$stats['suspended_umkm'] ?? 0"></x-stat-card>
        </div>
    </div>

    <x-data-table
    title="Daftar UMKM"
    :data="$umkm"
    :headers="$tableHeaders"
    :addButton="false"
    :exportButton="false"
    :filterOptions="['verified', 'unverified', 'pending']">

    {{-- Saya ubah variabel $user menjadi $item agar tidak bingung dengan relasi 'user' --}}
    @forelse ($umkm as $index => $item)
        <tr>
            <td>
                <div class="d-flex align-items-center gap-3">
                    {{-- Menarik foto profil dari relasi tabel Account --}}
                    <x-default-profile-umkm :foto="$item['user']['foto_profil'] ?? null"></x-default-profile-umkm>
                    
                    <div class="d-flex flex-column lh-sm">
                        {{-- Menampilkan Nama Usaha sebagai sorotan utama --}}
                        <span class="fw-semibold text-dark" style="font-size: 14px;">
                            {{ $item['nama_usaha'] ?? 'Nama Usaha Kosong' }}
                        </span>
                        
                        {{-- Menampilkan Username Pemilik di bawahnya --}}
                        <small class="text-muted" style="font-size: 12px;">
                            Pemilik: <span class="fw-medium text-success">{{ $item['user']['username'] ?? 'Tidak Diketahui' }}</span>
                        </small>
                    </div>
                </div>
            </td>

            <td>
                @if(isset($item['is_open']) && $item['is_open'] == true)
                    <span class="badge text-bg-success rounded-pill px-3 py-1 fw-medium" style="font-size: 11px;">Buka</span>
                @else
                    <span class="badge text-bg-danger rounded-pill px-3 py-1 fw-medium" style="font-size: 11px;">Tutup</span>
                @endif
            </td>

            <td>
                @php
                    $verifStatus = $item['verification_status'] ?? 'unverified';
                    $badgeClass = 'text-bg-secondary';
                    
                    if($verifStatus === 'verified') $badgeClass = 'text-bg-primary';
                    elseif($verifStatus === 'pending') $badgeClass = 'text-bg-warning';
                @endphp
                <span class="badge {{ $badgeClass }} rounded-pill px-3 py-1 fw-medium" style="font-size: 11px;">
                    {{ ucfirst($verifStatus) }}
                </span>
            </td>

            <td class="text-secondary" style="font-size: 13px;">
                {{ isset($item['created_at']) ? \Carbon\Carbon::parse($item['created_at'])->format('d M Y') : '-' }}
            </td>
            <td>
                {{-- Arahkan ke route 'umkm.detail' dan bawa ID UMKM-nya --}}
                <a href="{{ route('umkm.detail', $item['id']) }}" class="btn btn-outline-success btn-sm rounded-circle me-1" title="Lihat Detail">
                    <i class="bi bi-eye"></i>
                </a>
                
                {{-- Tombol Hapus (opsional, biarkan jika belum ada fungsinya) --}}
                <a href="#" class="btn btn-outline-danger btn-sm rounded-circle" title="Hapus UMKM">
                    <i class="bi bi-trash"></i>
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center py-5 text-muted">Belum ada data UMKM yang terdaftar.</td>
        </tr>
    @endforelse
    
    <x-slot name="footer">
        <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-center bg-light gap-3" 
             style="width: calc(100% + 40px); margin: 0 -20px -20px -20px; padding: 15px 20px; border-top: 1px solid #dee2e6; border-radius: 0 0 12px 12px;">
            
            <p class="pagination-info mb-0 text-muted text-center text-md-start" style="font-size: 14px;">
                Menampilkan <strong>{{ $umkm->firstItem() ?? 0 }} - {{ $umkm->lastItem() ?? 0 }}</strong> dari <strong>{{ $umkm->total() }}</strong> UMKM
            </p>
            
            <nav>
                <ul class="custom-pagination mb-0 justify-content-center flex-wrap">
                    {{-- Tombol Previous --}}
                    @if ($umkm->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link"><i class="bi bi-chevron-left"></i></span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $umkm->previousPageUrl() }}"><i class="bi bi-chevron-left"></i></a>
                        </li>
                    @endif

                    {{-- Deretan Angka Halaman --}}
                    @foreach ($umkm->getUrlRange(1, $umkm->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $umkm->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    {{-- Tombol Next --}}
                    @if ($umkm->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $umkm->nextPageUrl() }}"><i class="bi bi-chevron-right"></i></a>
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

@endsection