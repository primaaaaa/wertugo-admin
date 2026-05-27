@props(['active' => false, 'href'])

<a href="{{ $href }}" style="{{ $active ? 'color: #f4a43a; opacity: 1;' : ''}}">{{$slot}}</a>
