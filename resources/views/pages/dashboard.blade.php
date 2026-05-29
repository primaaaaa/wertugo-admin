@extends('layouts.app')
@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('admin-content')

<div class="container my-4">
    <div class="row g-4">
        
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stat-card icon="bi-people-fill" iconColor="success" cardTitle="Total User" :data="$stats['total_user']"></x-stat-card>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <x-stat-card icon="bi-shop" iconColor="primary" cardTitle="Total UMKM" :data="$stats['total_umkm']"></x-stat-card>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <x-stat-card icon="bi-clock-fill" iconColor="warning" cardTitle="Verifikasi Pending" :data="0"></x-stat-card>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <x-stat-card icon="bi-exclamation-triangle" iconColor="danger" cardTitle="Laporan Masuk" :data="0"></x-stat-card>
        </div>

    </div>
</div>

@endsection