@extends('layouts.app')

@section('title', 'Dashboard')

@section('sidebar-menu')
    @include('partials.sidebar-menu')
@endsection

@section('page-header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Ringkasan data sistem Padang Dalam Genggaman</p>
        </div>
    </div>
@endsection

@section('content')
    {{-- ============================================================
         STAT CARDS
    ============================================================ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        {{-- Total Lokasi --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Lokasi</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalLokasi) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total User --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total User</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalUser) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Favorit --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Favorit</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalFavorit) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Kunjungan --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Kunjungan</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalKunjungan) }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ============================================================
             VERIFIKASI KONTRIBUTOR (2/3 width)
        ============================================================ --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Verifikasi Kontributor
                </h2>
                <a href="{{ route('admin.approval.index') }}"
                    class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">Lihat Semua →</a>
            </div>

            @if($pendingLokasi->isEmpty())
                <div class="px-6 py-10 text-center">
                    <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-gray-400 font-medium">Tidak ada lokasi yang menunggu verifikasi</p>
                </div>
            @else
                <div class="divide-y divide-gray-50">
                    @foreach($pendingLokasi as $lokasi)
                        <div class="flex items-center justify-between px-6 py-3.5 hover:bg-gray-50/50 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[13px] font-semibold text-gray-800 truncate">{{ $lokasi->nama_tempat }}</p>
                                    <p class="text-[11px] text-gray-400">oleh {{ $lokasi->kontributor->name ?? '-' }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                {{ $lokasi->status_verifikasi === 'pending' ? 'bg-amber-50 text-amber-600' : 'bg-blue-50 text-blue-600' }}">
                                {{ $lokasi->status_verifikasi }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ============================================================
             CHART: Statistik Kunjungan (1/3 width)
        ============================================================ --}}
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Statistik Kunjungan
                </h2>
            </div>
            <div class="p-5">
                <canvas id="chartKunjungan" height="220"></canvas>
            </div>
        </div>
    </div>

    {{-- Welcome Card --}}
    {{-- <div class="mt-6 bg-gradient-to-br from-navy-800 to-navy-950 rounded-2xl p-8 text-white">
        <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}</h2>
        <p class="text-navy-200 text-sm mb-4">Anda login sebagai <span class="font-semibold text-gold-400 capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</span></p>
        <div class="flex gap-3">
            <a href="{{ route('profile.edit') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-navy-800 rounded-xl text-sm font-semibold hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Profil
            </a>
        </div>
    </div> --}}
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('chartKunjungan').getContext('2d');
            const data = @json($kunjunganPerBulan);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(d => d.label),
                    datasets: [{
                        label: 'Kunjungan',
                        data: data.map(d => d.count),
                        backgroundColor: 'rgba(26, 69, 118, 0.8)',
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: { size: 11 },
                                color: '#9CA3AF',
                                stepSize: 1,
                            },
                            grid: { color: '#F3F4F6' },
                        },
                        x: {
                            ticks: {
                                font: { size: 10 },
                                color: '#9CA3AF',
                            },
                            grid: { display: false },
                        }
                    }
                }
            });
        });
    </script>
@endpush
