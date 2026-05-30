@extends('errors.layout')

@section('title', 'Akses Ditolak')
@section('image', asset('img/403.png'))
@section('heading', 'Akses Ditolak!')
@section('message', 'Kamu tidak memiliki izin untuk melihat halaman ini. Silahkan login sebagai admin.')

@section('buttons')
    <button onclick="window.history.back()" class="btn btn-custom btn-outline-custom">
        <i class="bi bi-arrow-left"></i> Halaman Sebelumnya
    </button>
@endsection