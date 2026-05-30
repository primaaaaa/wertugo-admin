@extends('layouts.app')
@section('title', 'Daftar User')
@section('header', 'Manajemen User')
@section('admin-content')


<div class="container-fluid p-4">
    <div class="mb-5">
        <h3 class="fw-bold text-dark mb-2">Daftar User</h3>
    </div>
    <div class="row g-4">
        
        <div class="col-12 col-sm-6 col-xl-6">
            <x-stat-card icon="bi-people-fill" iconColor="success" cardTitle="Total User Aktif" :data="$stats['total_active']"></x-stat-card>
        </div>

        <div class="col-12 col-sm-6 col-xl-6">
            <x-stat-card icon="bi-x-circle-fill" iconColor="danger" cardTitle="Total User Ter-suspend" :data="$stats['total_suspended']"></x-stat-card>
        </div>
    </div>

    <x-data-table
    title="Daftar User"
    :data="$users"
    :headers="$tableHeaders"
    :addButton="false"
    :exportButton="false"
    :filterOptions="['active', 'suspe']">

    @forelse ($users as $index => $user)
        <tr>
            <td>
                <div class="d-flex align-items-center gap-3">
                    <x-default-profile-user :foto="$user['foto_profil'] ?? null" />

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
                {{ $user['role'] }}
            </td>

            <td>
                <span class="{{ $user['account_status'] === 'active' ? 'badge text-bg-success' : 'badge text-bg-danger'}}">{{ $user['account_status'] ?? 'Active' }}</span>
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
                Menampilkan <strong>{{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }}</strong> dari <strong>{{ $users->total() }}</strong> user
            </p>
            
            <nav>
                <ul class="custom-pagination mb-0 justify-content-center flex-wrap">
                    
                    {{-- Tombol Previous --}}
                    @if ($users->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link"><i class="bi bi-chevron-left"></i></span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $users->previousPageUrl() }}"><i class="bi bi-chevron-left"></i></a>
                        </li>
                    @endif

                    {{-- Deretan Angka Halaman --}}
                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $users->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    {{-- Tombol Next --}}
                    @if ($users->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $users->nextPageUrl() }}"><i class="bi bi-chevron-right"></i></a>
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