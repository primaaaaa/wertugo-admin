@props(['foto' => 'default-profile.png'])

@if($foto && $foto !== 'default-profile.png')
    {{-- Tampilkan gambar asli dari API --}}
    <img src="{{ env('WERTUGO_API') . '/storage/profiles/' . $foto }}" 
         alt="Avatar" 
         class="rounded-circle border border-light shadow-sm" 
         style="width: 40px; height: 40px; object-fit: cover;">
@else
    {{-- Tampilkan Icon Bootstrap jika profilnya default atau kosong --}}
    <div class="d-flex align-items-center justify-content-center bg-light border border-secondary-subtle rounded-circle text-secondary shadow-sm" 
         style="width: 40px; height: 40px;">
        <i class="bi bi-person-fill fs-4"></i>
    </div>
@endif