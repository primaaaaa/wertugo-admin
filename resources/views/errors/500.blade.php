@extends('errors.layout')

@section('title', 'Terjadi Masalah Sistem')
@section('image', asset('img/500.png'))
@section('heading', 'Terjadi Masalah Sistem')
@section('message', 'Server kami sedang sedikit lelah. Tenang, tim teknis kami sedang memperbaikinya. Coba muat ulang halaman atau kembali nanti.')

@section('buttons')
    <a href="{{ url('/') }}" class="btn btn-custom btn-primary-custom">
        <i class="bi bi-house-door"></i> Kembali ke Beranda
    </a>
    <button onclick="window.location.reload()" class="btn btn-custom btn-outline-custom">
        <i class="bi bi-arrow-clockwise"></i> Muat Ulang
    </button>
@endsection