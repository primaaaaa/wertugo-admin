<!-- Header Admin -->
@props(['header' => ''])

<div class="topbar">

    <div class="d-flex align-items-center gap-2">

        <!-- Tombol muncul hanya di HP -->
        <button class="btn btn-dark d-md-none" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>

        <h4 class="m-0">{{ $header }}</h4>

    </div>

    <div class="profile">
        <div class="profile-icon">
            <i class="bi bi-person-fill"></i>
        </div>

        <div class="profile-info">
            Admin
            Super Admin
        </div>
    </div>

</div>