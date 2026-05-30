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
            <x-stat-card icon="bi-shop" iconColor="success" cardTitle="Total UMKM Aktif" :data="$stats['total_umkm']"></x-stat-card>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <x-stat-card icon="bi-check-circle-fill" iconColor="primary" cardTitle="Total UMKM Terverifikasi" :data="$stats['verified_umkm']"></x-stat-card>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <x-stat-card icon="bi-x-circle-fill" iconColor="danger" cardTitle="UMKM Ter-suspend" :data="0"></x-stat-card>
        </div>
    </div>

    <x-data-table
    title="Daftar UMKM"
    :data="$umkm"
    :headers="$tableHeaders"
    :addButton="false"
    :exportButton="false"
    :filterOptions="['verified', 'unverified', 'pending']">

    @forelse ($umkm as $index => $user)
        <tr>
            <td>
                <div class="d-flex align-items-center gap-3">
                    
                    <x-default-profile-umkm :foto="$user['foto_profil'] ?? null"></x-default-profile-umkm>
                    <div class="d-flex flex-column lh-sm">
                        
                        <span class="fw-semibold text-dark" style="font-size: 14px;">
                            {{ $user['username'] }}
                        </span>
                        
                        <small class="text-muted" style="font-size: 12px;">
                            {{ $user['email'] }}
                        </small>

                    </div>
                </div>
            </td>
            <td>
                <span class="{{ $user['account_status'] === 'active' ? 'badge text-bg-success' : 'badge text-bg-danger'}}">{{ $user['account_status'] ?? 'Active' }}</span>
            </td>

            <td>
                <span class="{{ $user['verification_status'] === 'verified' ? 'badge text-bg-success' : ($user['verification_status'] === 'pending' ? 'badge text-bg-warning' : 'badge text-bg-secondary')}}">{{ $user['verification_status'] ?? 'unverified' }}</span>
            </td>

            <td>
                {{ $user['created_at'] }}
            </td>

            <td>
                <a class="btn btn-outline-success">
                    <i class="bi bi-eye"></i>
                </a>
                <a class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i>
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6">Belum ada data user.</td>
        </tr>
    @endforelse
    <x-slot name="footer">
        {{-- TAMBAHAN: flex-column (untuk HP) dan flex-md-row (untuk Laptop), serta gap-3 biar ada jarak saat numpuk --}}
        <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-center bg-light gap-3" 
             style="width: calc(100% + 40px); margin: 0 -20px -20px -20px; padding: 15px 20px; border-top: 1px solid #dee2e6; border-radius: 0 0 12px 12px;">
            
            {{-- TAMBAHAN: text-center di HP, text-md-start di Laptop --}}
            <p class="pagination-info mb-0 text-muted text-center text-md-start">
                Menampilkan <strong>{{ $umkm->firstItem() ?? 0 }} - {{ $umkm->lastItem() ?? 0 }}</strong> dari <strong>{{ $umkm->total() }}</strong> user
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