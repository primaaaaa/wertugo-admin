@extends('errors.layout')

@section('title', 'Halaman Tidak Ditemukan')
@section('image', asset('img/404.png'))
@section('heading', 'Halaman Tidak Ditemukan!')
@section('message', 'Mungkin kamu salah jalan atau alamat. Kembali ke halaman sebelumnya?')

@section('buttons')
    <button onclick="window.history.back()" class="btn btn-custom btn-outline-custom">
        <i class="bi bi-arrow-left"></i> Halaman Sebelumnya
    </button>
@endsection