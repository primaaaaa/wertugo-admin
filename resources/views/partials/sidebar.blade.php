<!-- Komponen untuk Sidebar di halaman admin -->
<div class="sidebar d-flex flex-column px-3">
    <a href="/" class="brand text-decoration-none">
        <img src="{{ asset('asset/logo_top.png') }}" alt="Wertugo Logo" class="logo-img">
        <span class="brand-text">WertugoAdmin</span>
    </a>

    <nav class="nav flex-column">
        <x-side-link href="/admin/dashboard" :active="request()->is('admin')">
            <i class="bi bi-pie-chart-fill"></i>
            <span>Dashboard</span>
        </x-side-link>

        <x-side-link href="/admin/kamar" :active="request()->is('admin/kamar') || request()->is('admin/kamar-detail/*')">
            <i class="bi bi-door-closed-fill"></i>
            <span>Daftar User</span>
        </x-side-link>

        <x-side-link href="/admin/reservasi" :active="request()->is('admin/reservasi') || request()->is('admin/reservasi-detail/*')">
            <i class="bi bi-people-fill"></i>
            <span>Daftar UMKM</span>
        </x-side-link>

        <x-side-link href="/admin/ulasan" :active="request()->is('admin/ulasan')">
            <i class="bi bi-chat-dots-fill"></i>
            <span>Verifikasi UMKM</span>
        </x-side-link>

        <form action="{{ route('logout') }}" method="POST" style="margin: 5px 0; padding: 0 20px;">
            @csrf
            <button type="submit" class="side-link">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
            </button>
        </form>


    </nav>
</div>