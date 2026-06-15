@extends('layouts.app')

@section('title', 'Dashboard')

@section('sidebar-menu')
    @include('partials.sidebar-menu')
@endsection

@section('page-header')
    @php
        $hour = now()->format('H');
        if ($hour >= 5 && $hour < 12) {
            $sapaan = 'Pagi';
        } elseif ($hour >= 12 && $hour < 15) {
            $sapaan = 'Siang';
        } elseif ($hour >= 15 && $hour < 18) {
            $sapaan = 'Sore';
        } else {
            $sapaan = 'Malam';
        }
    @endphp

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-navy-600 mb-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight leading-tight">
                Selamat {{ $sapaan }}, <span class="text-navy-800 font-extrabold">{{ auth()->user()->name }}</span>!
            </h1>
            <p class="text-sm text-gray-500 mt-1 max-w-xl">
                Ada <span class="font-bold text-gray-700">{{ $pendingLokasi->count() }} lokasi</span> menunggu verifikasi dan <span class="font-bold text-gray-700">{{ $totalPengaduan }} pengaduan aktif</span> hari ini.
            </p>
        </div>

        <div class="flex items-center gap-2.5 flex-shrink-0">
            <a href="{{ route('admin.events.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-navy-800 text-white font-semibold rounded-xl text-xs hover:bg-navy-700 active:scale-95 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Event Baru
            </a>
            <a href="{{ route('admin.pengaduan.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 font-semibold rounded-xl text-xs active:scale-95 transition-all shadow-sm">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                Pantau Laporan
            </a>
        </div>
    </div>
@endsection

@section('content')
    {{-- ============================================================
         STAT CARDS
    ============================================================ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        {{-- Total Lokasi --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Lokasi Aktif</p>
                    <p class="text-3xl font-black text-gray-900 leading-none tracking-tight">{{ number_format($totalLokasi) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 group-hover:bg-blue-100 rounded-xl flex items-center justify-center transition-colors">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1.5 text-xs text-blue-600 font-semibold">
                <span class="inline-flex px-1.5 py-0.5 rounded bg-blue-50 text-[10px] font-bold">+3%</span>
                <span>dari bulan lalu</span>
            </div>
        </div>

        {{-- Total Kunjungan --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Kunjungan</p>
                    <p class="text-3xl font-black text-gray-900 leading-none tracking-tight">{{ number_format($totalKunjungan) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 group-hover:bg-green-100 rounded-xl flex items-center justify-center transition-colors">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1.5 text-xs text-green-600 font-semibold">
                <span class="inline-flex px-1.5 py-0.5 rounded bg-green-50 text-[10px] font-bold">+12%</span>
                <span>peningkatan tren</span>
            </div>
        </div>

        {{-- Pengaduan Aktif --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Pengaduan Aktif</p>
                    <p class="text-3xl font-black text-gray-900 leading-none tracking-tight">{{ number_format($totalPengaduan) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-50 group-hover:bg-red-100 rounded-xl flex items-center justify-center transition-colors">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1.5 text-xs text-red-600 font-semibold">
                <span class="inline-flex px-1.5 py-0.5 rounded bg-red-50 text-[10px] font-bold">Penting</span>
                <span>butuh tindak lanjut</span>
            </div>
        </div>

        {{-- Event Kota Aktif --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Event Kota Aktif</p>
                    <p class="text-3xl font-black text-gray-900 leading-none tracking-tight">{{ number_format($totalEvent) }}</p>
                </div>
                <div class="w-12 h-12 bg-gold-50 group-hover:bg-gold-100 rounded-xl flex items-center justify-center transition-colors">
                    <svg class="w-6 h-6 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1.5 text-xs text-gold-600 font-semibold">
                <span class="inline-flex px-1.5 py-0.5 rounded bg-gold-50 text-[10px] font-bold">Aktif</span>
                <span>pada bulan berjalan</span>
            </div>
        </div>
    </div>

    {{-- ============================================================
         DOUBLE-COLUMN LAYOUT
    ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left/Main column (2/3 width) --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Card 1: Verifikasi Lokasi Kontributor --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                        Verifikasi Lokasi Kontributor
                    </h2>
                    <a href="{{ route('admin.approval.index') }}"
                        class="text-[11px] font-bold text-navy-600 hover:text-navy-800 transition-colors uppercase tracking-wider">Lihat Semua →</a>
                </div>

                @if($pendingLokasi->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-sm text-gray-400 font-medium">Semua pengajuan kontributor telah terverifikasi</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-wider bg-gray-50/20">
                                    <th class="px-6 py-3">Lokasi / Tempat</th>
                                    <th class="px-6 py-3">Kontributor</th>
                                    <th class="px-6 py-3">Kategori</th>
                                    <th class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($pendingLokasi as $lokasi)
                                    <tr class="hover:bg-gray-50/40 transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                @if($lokasi->fotoUtama)
                                                    <img src="{{ asset('storage/' . $lokasi->fotoUtama->file_foto) }}" class="w-10 h-10 rounded-xl object-cover flex-shrink-0 border border-gray-100">
                                                @else
                                                    <div class="w-10 h-10 rounded-xl bg-navy-50 text-navy-600 flex items-center justify-center flex-shrink-0 border border-navy-100/50">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div class="min-w-0">
                                                    <p class="text-[13px] font-bold text-gray-800 truncate group-hover:text-navy-700 transition-colors">{{ $lokasi->nama_tempat }}</p>
                                                    <p class="text-[11px] text-gray-400 mt-0.5 truncate">{{ $lokasi->alamat }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-[13px] font-semibold text-gray-700">{{ $lokasi->kontributor->name ?? 'Anonim' }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-navy-50 text-navy-600 border border-navy-100">
                                                {{ $lokasi->kategori->nama_kategori ?? 'Umum' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('admin.approval.show', $lokasi->id_lokasi) }}"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-navy-600 hover:bg-navy-700 text-white rounded-lg text-xs font-bold transition-all shadow-sm">
                                                Periksa
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Card 2: Pengaduan Warga Terbaru --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span>
                        Pengaduan Warga Terbaru
                    </h2>
                    <a href="{{ route('admin.pengaduan.index') }}"
                        class="text-[11px] font-bold text-navy-600 hover:text-navy-800 transition-colors uppercase tracking-wider">Kelola Semua →</a>
                </div>

                @if($recentPengaduan->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-sm text-gray-400 font-medium">Belum ada laporan pengaduan masuk</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-wider bg-gray-50/20">
                                    <th class="px-6 py-3">Pelapor / Lokasi</th>
                                    <th class="px-6 py-3">Masalah</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($recentPengaduan as $pengaduan)
                                    <tr class="hover:bg-gray-50/40 transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="min-w-0">
                                                <p class="text-[13px] font-bold text-gray-800">{{ $pengaduan->user->name ?? 'Warga' }}</p>
                                                <p class="text-[11px] text-gray-400 mt-0.5 truncate flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    </svg>
                                                    {{ $pengaduan->lokasi->nama_tempat ?? 'Lokasi Umum' }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="max-w-[200px]">
                                                <p class="text-[12px] font-semibold text-gray-700 capitalize leading-tight">{{ str_replace('_', ' ', $pengaduan->jenis_pengaduan) }}</p>
                                                <p class="text-[11px] text-gray-400 mt-0.5 truncate leading-tight">{{ $pengaduan->isi_pengaduan }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                                {{ $pengaduan->status === 'menunggu' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }}">
                                                {{ $pengaduan->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('admin.pengaduan.show', $pengaduan->id_pengaduan) }}"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-50 border border-gray-200 hover:bg-gray-100 hover:border-gray-300 text-gray-700 rounded-lg text-xs font-semibold transition-all">
                                                Tinjau
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>

        {{-- Right column (1/3 width) --}}
        <div class="space-y-6">

            {{-- Card 1: Grafik Kunjungan --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Statistik Kunjungan
                    </h2>
                </div>
                <div class="p-5">
                    <div class="relative h-[220px]">
                        <canvas id="chartKunjungan"></canvas>
                    </div>
                </div>
            </div>

            {{-- Card 2: Event Terdekat --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Event Terdekat
                    </h2>
                    <a href="{{ route('admin.events.index') }}"
                        class="text-[10px] font-bold text-navy-600 hover:text-navy-800 transition-colors uppercase tracking-wider">Semua</a>
                </div>

                <div class="p-5 divide-y divide-gray-100">
                    @if($upcomingEvents->isEmpty())
                        <div class="text-center py-6">
                            <svg class="w-8 h-8 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-xs text-gray-400">Tidak ada event aktif mendatang</p>
                        </div>
                    @else
                        @foreach($upcomingEvents as $event)
                            <div class="flex gap-4 items-start py-3.5 first:pt-0 last:pb-0">
                                <div class="flex-shrink-0 w-12 h-12 bg-navy-50 rounded-xl flex flex-col items-center justify-center border border-navy-100/50">
                                    <span class="text-[9px] font-bold text-navy-500 uppercase tracking-wider leading-none">{{ $event->tanggal_mulai->translatedFormat('M') }}</span>
                                    <span class="text-base font-black text-navy-800 leading-none mt-1">{{ $event->tanggal_mulai->format('d') }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[13px] font-bold text-gray-800 truncate">{{ $event->nama_event }}</p>
                                    <p class="text-[11px] text-gray-400 mt-1 truncate flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        {{ $event->lokasi_event }}
                                    </p>
                                </div>
                                <a href="{{ route('admin.events.show', $event->id_event) }}" class="p-1 hover:bg-gray-50 rounded-lg transition-colors text-gray-400 hover:text-navy-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('chartKunjungan').getContext('2d');
            const data = @json($kunjunganPerBulan);

            // Create background gradient for the bars
            const gradient = ctx.createLinearGradient(0, 0, 0, 200);
            gradient.addColorStop(0, '#1a4576'); // Navy-700
            gradient.addColorStop(1, '#73b0e3'); // Navy-400

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(d => d.label),
                    datasets: [{
                        label: 'Kunjungan',
                        data: data.map(d => d.count),
                        backgroundColor: gradient,
                        hoverBackgroundColor: '#14355a', // Navy-800
                        borderRadius: 8,
                        borderSkipped: false,
                        maxBarThickness: 32
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0a1729', // Navy-950
                            titleFont: { size: 11, weight: 'bold' },
                            bodyFont: { size: 11 },
                            padding: 10,
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: { size: 10, weight: '500' },
                                color: '#9CA3AF',
                                stepSize: 1,
                            },
                            grid: { color: '#F3F4F6' },
                            border: { dash: [4, 4] }
                        },
                        x: {
                            ticks: {
                                font: { size: 9, weight: '600' },
                                color: '#9CA3AF',
                            },
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
@endpush
