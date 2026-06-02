<!-- Sidebar -->
<div class="sidebar d-flex flex-column px-3" id="sidebar">

    <!-- Header Sidebar -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Tombol Close -->
        <button class="btn btn-sm btn-danger d-md-none" id="closeSidebar">
            <i class="bi bi-x-lg"></i>
        </button>


        <a href="/" class="brand text-decoration-none">
            <img src="{{ asset('img/logo.png') }}" alt="Wertugo Logo" class="logo-img">
            <span class="brand-text">WertugoAdmin</span>
        </a>
    </div>

    <nav class="nav flex-column">

        <x-side-link href="/admin/" :active="request()->is('admin')">
            <i class="bi bi-pie-chart-fill"></i>
            <span>Dashboard</span>
        </x-side-link>

        <x-side-link href="/admin/users" :active="request()->is('admin/users') || request()->is('admin/users/*')">
            <i class="bi bi-person-circle"></i>
            <span>Daftar User</span>
        </x-side-link>

        <x-side-link href="/admin/umkm" :active="request()->is('admin/umkm') || request()->is('admin/umkm-detail/*')">
            <i class="bi bi-shop"></i>
            <span>Daftar UMKM</span>
        </x-side-link>

        <x-side-link href="/admin/verifikasi-umkm" :active="request()->is('admin/verifikasi-umkm') || request()->is('admin/verifikasi-detail/*')">
            <i class="bi bi-file-earmark-fill"></i>
            <span>Verifikasi UMKM</span>
        </x-side-link>

        <x-side-link href="/admin/report" :active="request()->is('admin/report') || request()->is('admin/report-detail')">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>Report Notice</span>
        </x-side-link>

        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100 text-start">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </button>
        </form>

    </nav>

</div>
