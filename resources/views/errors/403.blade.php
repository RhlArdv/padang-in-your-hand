@extends('layouts.app')

@section('title', 'Akses Ditolak')

@section('sidebar-menu')
    @include('partials.sidebar-menu')
@endsection

@section('page-header')
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Akses Ditolak</h1>
    </div>
@endsection

@section('content')
    <div class="flex flex-col items-center justify-center py-20">
        <div class="w-20 h-20 rounded-full bg-red-50 flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-800 mb-2">403 — Forbidden</h2>
        <p class="text-sm text-gray-500 mb-6 text-center max-w-sm">Anda tidak memiliki izin untuk mengakses halaman ini. Silakan hubungi administrator jika Anda merasa ini kesalahan.</p>
        <a href="{{ route('admin.dashboard') }}"
            class="px-5 py-2.5 bg-navy-800 text-white text-sm font-semibold rounded-xl hover:bg-navy-700 transition-colors shadow-sm">
            Kembali ke Dashboard
        </a>
    </div>
@endsection
