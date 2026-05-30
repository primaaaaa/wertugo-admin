@extends('errors.layout')

@section('title', 'Akses Ditolak')
@section('image', asset('img/403.png'))
@section('heading', 'Akses Ditolak!')
@section('message', 'Kamu tidak memiliki izin untuk melihat halaman ini. Silahkan login sebagai admin.')

@section('buttons')
    <a href="{{ url('/login') }}" class="btn btn-custom btn-primary-custom">
        <i class="bi bi-person"></i> Login sebagai Admin
    </a>
    <button onclick="window.history.back()" class="btn btn-custom btn-outline-custom">
        <i class="bi bi-arrow-left"></i> Halaman Sebelumnya
    </button>
@endsection