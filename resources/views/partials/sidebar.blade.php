<!-- Sidebar -->
<div class="sidebar d-flex flex-column px-3" id="sidebar">

    <!-- Header Sidebar -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Tombol Close -->
        <button class="btn btn-sm btn-danger d-md-none" id="closeSidebar">
            <i class="bi bi-x-lg"></i>
        </button>


        <a href="/" class="brand text-decoration-none">
            <img src="{{ asset('asset/logo_top.png') }}" alt="Wertugo Logo" class="logo-img">
            <span class="brand-text">WertugoAdmin</span>
        </a>
    </div>

    <nav class="nav flex-column">

        <x-side-link href="/admin/dashboard" :active="request()->is('admin')">
            <i class="bi bi-pie-chart-fill"></i>
            <span>Dashboard</span>
        </x-side-link>

        <x-side-link href="/admin/kamar" :active="request()->is('admin/kamar') || request()->is('admin/kamar-detail/*')">
            <i class="bi bi-door-closed-fill"></i>
            <span>Daftar User</span>
        </x-side-link>

    </nav>

</div>
