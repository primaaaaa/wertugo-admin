@extends('layouts.app')
@section('title', 'Daftar Pending Verification')
@section('header', 'Pending Verification')

@section('admin-content')

<style>
    /* Styling khusus menyerupai desain Figma */
    .card-rounded {
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }
    
    .stat-card-outline {
        border-radius: 16px;
        border: 1px solid #cbd5e1;
        background-color: #ffffff;
        padding: 20px;
        width: fit-content;
        min-width: 220px;
    }

    .icon-box-green {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #e6f4ea;
        color: #0c7b1b;
        font-size: 18px;
        margin-bottom: 24px;
    }

    /* Tabel Styling */
    .custom-table th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 13px;
        padding: 16px;
        border-bottom: 1px solid #e2e8f0;
    }

    .custom-table td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
    }

    /* Icon Bulat di Nama UMKM */
    .umkm-icon-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #f1f5f9;
        color: #0c7b1b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    /* Badge Kategori Minimalis */
    .badge-category {
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 600;
        background-color: #f1f5f9;
        color: #64748b;
    }
    .badge-category.kuliner { background-color: #e6f4ea; color: #0c7b1b; }

    /* Tombol Aksi */
    .btn-lihat {
        border: 1px solid #0c7b1b;
        color: #0c7b1b;
        background: transparent;
        border-radius: 50px;
        padding: 6px 20px;
        font-size: 13px;
        font-weight: 600;
    }
    .btn-lihat:hover {
        background: #e6f4ea;
        color: #0c7b1b;
    }
    .btn-verifikasi {
        background: #0c7b1b;
        color: white;
        border: none;
        border-radius: 50px;
        padding: 6px 20px;
        font-size: 13px;
        font-weight: 600;
    }
    .btn-verifikasi:hover {
        background: #095c14;
        color: white;
    }
</style>

<div class="container-fluid py-4 px-4">
    
    <!-- HEADER -->
    <div class="mb-4">
        <h3 class="fw-bold text-dark mb-1">Daftar Pending Verification</h3>
        <p class="text-muted small">Tinjau dan validasi {{ $totalPending }} permintaan pendaftaran UMKM yang sedang menunggu.</p>
    </div>

    <!-- STAT CARD -->
    <div class="stat-card-outline mb-4">
        <div class="icon-box-green">
            <i class="bi bi-clipboard-check-fill"></i>
        </div>
        <div class="text-muted mb-1" style="font-size: 13px;">Total Pending</div>
        <h2 class="fw-bold text-dark mb-0" style="font-size: 2.2rem;">{{ $totalPending }}</h2>
    </div>

    <!-- TABEL DATA -->
    <div class="card card-rounded border-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th style="padding-left: 24px;">Nama UMKM</th>
                        <th>Kategori</th>
                        <th>Pemilik</th>
                        <th>Tanggal Dikirim</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingTable as $item)
                        @php
                            // Mengambil relasi dari UMKM dan User
                            $umkm = $item['umkm'] ?? [];
                            $pemilik = $umkm['user'] ?? [];
                            $kategori = strtolower($umkm['kategori'] ?? 'Lainnya');
                        @endphp
                        <tr>
                            <td style="padding-left: 24px;">
                                <div class="d-flex align-items-center gap-3">
                                    
                                    {{-- Memanggil komponen default UMKM --}}
                                    <div class="flex-shrink-0">
                                        <x-default-profile-umkm :foto="$umkm['foto_profil'] ?? null" />
                                    </div>
                                    
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0" style="font-size: 14px;">{{ $umkm['nama_usaha'] ?? 'Data UMKM Terhapus' }}</h6>
                                        <small class="text-muted" style="font-size: 11px;">ID: {{ strtoupper(substr($item['id'] ?? $item['_id'], -8)) }}</small>
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                <span class="badge-category {{ $kategori == 'kuliner' ? 'kuliner' : '' }}">
                                    {{ ucfirst($umkm['kategori'] ?? 'Lainnya') }}
                                </span>
                            </td>
                            
                            <td class="text-dark fw-medium">
                                {{ $pemilik['username'] ?? 'Pemilik Tidak Diketahui' }}
                            </td>
                            
                            <td>
                                <div class="text-dark" style="font-size: 13px;">
                                    {{ isset($item['created_at']) ? \Carbon\Carbon::parse($item['created_at'])->format('M d, Y') : '-' }}
                                    <br>
                                    <small class="text-muted">{{ isset($item['created_at']) ? \Carbon\Carbon::parse($item['created_at'])->format('H:i') : '' }}</small>
                                </div>
                            </td>
                            
                            <td>
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    {{-- Tombol Lihat (Diarahkan ke halaman Detail Verifikasi yang baru) --}}
                                    <a href="{{ route('verifikasi.detail', $item['id'] ?? $item['_id']) }}" class="btn-lihat text-decoration-none">
                                        Lihat
                                    </a>
                                    
                                    {{-- Tombol Verifikasi Instan --}}
                                    <form action="{{ route('umkm.verify', $item['id'] ?? $item['_id']) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn-verifikasi" onclick="return confirm('Apakah Anda yakin ingin memverifikasi tempat usaha {{ $umkm['nama_usaha'] ?? '' }}?');">
                                            Verifikasi
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-check2-circle fs-1 text-success opacity-50 mb-2 d-block"></i>
                                Tidak ada antrean verifikasi yang pending saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION SECTION -->
        @if($pendingTable->hasPages())
        <div class="d-flex justify-content-between align-items-center px-4 py-3 bg-white border-top">
            <span class="text-muted" style="font-size: 13px;">
                Menampilkan {{ $pendingTable->firstItem() ?? 0 }} dari {{ $pendingTable->total() }} permintaan pending.
            </span>
            
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    {{-- Pagination menggunakan Bootstrap bawaan yang akan menyesuaikan dengan desain --}}
                    {{ $pendingTable->links('pagination::bootstrap-5') }}
                </ul>
            </nav>
        </div>
        @endif
    </div>
</div>

@endsection