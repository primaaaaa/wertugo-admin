@props(['active' => false, 'href'])

<a href="{{ $href }}" 
   class="nav-link d-flex align-items-center gap-2 {{ $active ? 'active' : '' }}"
   {{ $attributes }}>
    {{ $slot }}
</a>