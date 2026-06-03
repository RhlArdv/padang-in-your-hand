@extends('layouts.app')

@section('title', 'Detail Event — ' . $event->nama_event)

@section('sidebar-menu')
    @include('partials.sidebar-menu')
@endsection

@section('page-header')
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.events.index') }}"
                class="p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Event</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $event->nama_event }}</p>
            </div>
        </div>
        <a href="{{ route('admin.events.edit', $event->id_event) }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-navy-800 text-white text-sm font-semibold rounded-xl hover:bg-navy-700 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit Event
        </a>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-6xl">
        
        {{-- Area Utama (Kiri & Tengah) --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Banner Image --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Banner Event</h3>
                
                @if($event->banner)
                    <div class="relative rounded-xl overflow-hidden bg-gray-100 h-80 shadow-sm border border-gray-100">
                        <img src="{{ $event->banner_url }}" class="w-full h-full object-cover transition-all duration-300">
                    </div>
                @else
                    <div class="h-64 bg-gray-50 rounded-xl flex flex-col items-center justify-center text-gray-400 border-2 border-dashed border-gray-200">
                        <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm font-medium">Belum ada banner event</p>
                    </div>
                @endif
            </div>

            {{-- Deskripsi Event --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Deskripsi Event</h3>
                <div class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">
                    {{ $event->deskripsi ?: 'Tidak ada deskripsi untuk event ini.' }}
                </div>
            </div>

            {{-- Peta Lokasi (Hanya muncul jika id_lokasi diatur) --}}
            @if($event->id_lokasi && $event->lokasi)
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Lokasi pada Peta</h3>
                        <a href="https://www.google.com/maps/search/?api=1&query={{ $event->lokasi->latitude }},{{ $event->lokasi->longitude }}" 
                           target="_blank" 
                           class="text-xs text-navy-600 hover:text-navy-700 font-bold flex items-center gap-1">
                            Buka di Google Maps
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                    
                    <div id="show-map" style="height: 380px; z-index: 1;" class="rounded-xl border border-gray-100 overflow-hidden shadow-sm"></div>
                    <div class="mt-3 flex items-center justify-between text-xs text-gray-400">
                        <span class="font-mono bg-gray-50 px-2 py-1 rounded">Coords: {{ $event->lokasi->latitude }}, {{ $event->lokasi->longitude }}</span>
                        <span>Klik pada peta untuk mengaktifkan zoom dengan roda mouse</span>
                    </div>
                </div>
            @endif

        </div>

        {{-- Sidebar (Kanan) --}}
        <div class="space-y-6">
            
            {{-- Informasi Event --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Detail Informasi</h3>
                    <span class="inline-flex px-2.5 py-1 border rounded-full text-[10px] font-bold uppercase tracking-wider 
                        {{ $event->status === 'aktif' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-gray-50 text-gray-600 border-gray-100' }}">
                        {{ $event->status }}
                    </span>
                </div>

                <div class="space-y-4">
                    <div class="border-b border-gray-50 pb-3">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Jenis Event</p>
                        <p class="text-sm text-gray-800 font-semibold capitalize">{{ $event->jenis_event }}</p>
                    </div>

                    <div class="border-b border-gray-50 pb-3">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Tanggal Mulai</p>
                        <p class="text-sm text-gray-800 font-semibold">{{ $event->tanggal_mulai?->format('d M Y') }}</p>
                    </div>

                    <div class="border-b border-gray-50 pb-3">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Tanggal Selesai</p>
                        <p class="text-sm text-gray-800 font-semibold">{{ $event->tanggal_selesai?->format('d M Y') }}</p>
                    </div>

                    <div class="border-b border-gray-50 pb-3">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Nama Lokasi Event</p>
                        <p class="text-sm text-gray-800 font-semibold">{{ $event->lokasi_event }}</p>
                    </div>

                    @if($event->id_lokasi && $event->lokasi)
                        <div class="pb-1">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Terhubung ke Master Lokasi</p>
                            <a href="{{ route('admin.lokasi.show', $event->id_lokasi) }}" 
                               class="text-sm text-navy-600 hover:text-navy-700 hover:underline font-bold flex items-center gap-1">
                                {{ $event->lokasi->nama_tempat }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Pembuat Event --}}
            <div class="bg-gradient-to-br from-blue-50 to-navy-50/20 border border-blue-100 rounded-2xl p-5 shadow-sm flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-navy-700 to-navy-900 flex items-center justify-center text-white font-bold text-sm flex-shrink-0 shadow-md">
                    {{ strtoupper(substr($event->pembuat->name ?? 'A', 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] text-navy-500 font-bold uppercase tracking-wider mb-0.5">Dibuat oleh</p>
                    <p class="text-sm font-bold text-navy-900 truncate">{{ $event->pembuat->name ?? 'Administrator' }}</p>
                    <p class="text-xs text-navy-600 truncate">{{ $event->pembuat->email ?? 'admin@padang.go.id' }}</p>
                </div>
            </div>

        </div>

    </div>
@endsection

@if($event->id_lokasi && $event->lokasi)
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const lat = {{ $event->lokasi->latitude }};
            const lng = {{ $event->lokasi->longitude }};
            
            // Initialize Leaflet Map
            const map = L.map('show-map', {
                scrollWheelZoom: false // Disable scroll zoom by default for better page scrolling
            }).setView([lat, lng], 17);

            // Google Hybrid Satellite Map Layer (matching the style in detail lokasi)
            L.tileLayer('https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                attribution: '&copy; Google Maps',
                maxZoom: 21,
            }).addTo(map);

            // Customize marker icon
            const redIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41],
            });

            // Add Marker
            L.marker([lat, lng], { icon: redIcon })
                .addTo(map)
                .bindPopup(`<strong class="text-sm font-bold text-gray-900">{{ addslashes($event->nama_event) }}</strong><br><span class="text-xs text-gray-500">{{ addslashes($event->lokasi->nama_tempat) }} - {{ addslashes($event->lokasi->alamat) }}</span>`)
                .openPopup();
                
            // Enable scroll wheel zoom on map click/focus
            map.on('click', function() {
                if (map.scrollWheelZoom.enabled()) {
                    map.scrollWheelZoom.disable();
                } else {
                    map.scrollWheelZoom.enable();
                }
            });
        });
    </script>
    @endpush
@endif
