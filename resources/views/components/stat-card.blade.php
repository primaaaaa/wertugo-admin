@props([
    'icon' => '',
    'iconColor' => '',
    'cardTitle' => '',
    'data' => null
])

<div class="card h-100 card-stats rounded-4 shadow-none">
    <div class="card-body p-4">
            <div class="icon-box rounded-circle d-flex align-items-center justify-content-center bg-{{ $iconColor }}-subtle mb-4">
                <i class="bi {{ $icon }} text-{{ $iconColor }} fs-5"></i>
            </div>
        <p class="text-secondary mb-1 fw-medium" style="font-size: 0.9rem;">{{ $cardTitle }}</p>
        <h2 class="fw-bold text-dark mb-0">{{ $data }}</h2>
    </div>
</div>