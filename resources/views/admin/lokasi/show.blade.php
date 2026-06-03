@extends('layouts.app')

@section('title', 'Detail Lokasi — ' . $lokasi->nama_tempat)

@section('sidebar-menu')
    @include('partials.sidebar-menu')
@endsection

@section('page-header')
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.lokasi.index') }}"
                class="p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Lokasi</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $lokasi->nama_tempat }}</p>
            </div>
        </div>
        <a href="{{ route('admin.lokasi.edit', $lokasi->id_lokasi) }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-navy-800 text-white text-sm font-semibold rounded-xl hover:bg-navy-700 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit Lokasi
        </a>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-6xl">
        
        {{-- Area Utama (Kiri & Tengah) --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Galeri Foto --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Galeri Foto</h3>
                
                @if($lokasi->fotos->count())
                    <div x-data="{ fotoIndex: 0, fotosCount: {{ $lokasi->fotos->count() }} }">
                        {{-- Foto Utama --}}
                        <div class="relative rounded-xl overflow-hidden bg-gray-100 h-80 mb-3 shadow-sm border border-gray-100">
                            @foreach($lokasi->fotos as $idx => $foto)
                                <img src="{{ asset('storage/' . $foto->file_foto) }}" 
                                     x-show="fotoIndex === {{ $idx }}" 
                                     class="w-full h-full object-cover transition-opacity duration-300"
                                     style="display: {{ $idx === 0 ? 'block' : 'none' }};">
                            @endforeach
                            
                            {{-- Navigasi slide jika > 1 --}}
                            @if($lokasi->fotos->count() > 1)
                                <div class="absolute inset-0 flex items-center justify-between px-4">
                                    <button @click="fotoIndex = fotoIndex > 0 ? fotoIndex - 1 : fotosCount - 1"
                                        class="bg-black/35 hover:bg-black/50 text-white rounded-full p-2 backdrop-blur-sm transition-all focus:outline-none">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </button>
                                    <button @click="fotoIndex = fotoIndex < fotosCount - 1 ? fotoIndex + 1 : 0"
                                        class="bg-black/35 hover:bg-black/50 text-white rounded-full p-2 backdrop-blur-sm transition-all focus:outline-none">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                                
                                {{-- Counter Foto --}}
                                <div class="absolute bottom-3 right-3 bg-black/60 backdrop-blur-md text-white text-xs font-semibold px-3 py-1 rounded-full"
                                     x-text="(fotoIndex + 1) + ' / ' + fotosCount">
                                </div>
                            @endif
                        </div>
                        
                        {{-- Thumbnail Slider --}}
                        @if($lokasi->fotos->count() > 1)
                            <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-thin">
                                @foreach($lokasi->fotos as $idx => $foto)
                                    <img src="{{ asset('storage/' . $foto->file_foto) }}" 
                                         @click="fotoIndex = {{ $idx }}"
                                         class="w-16 h-16 object-cover rounded-lg cursor-pointer border-2 transition-all flex-shrink-0"
                                         :class="fotoIndex === {{ $idx }} ? 'border-navy-600 ring-2 ring-navy-600/10 scale-95 opacity-100' : 'border-transparent opacity-60 hover:opacity-100'">
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="h-64 bg-gray-50 rounded-xl flex flex-col items-center justify-center text-gray-400 border-2 border-dashed border-gray-200">
                        <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm font-medium">Belum ada foto lokasi</p>
                    </div>
                @endif
            </div>

            {{-- Deskripsi Lokasi --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Deskripsi Tempat</h3>
                <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">
                    {{ $lokasi->deskripsi ?: 'Tidak ada deskripsi untuk lokasi ini.' }}
                </p>
            </div>

            {{-- Peta Lokasi --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Lokasi pada Peta</h3>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $lokasi->latitude }},{{ $lokasi->longitude }}" 
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
                    <span class="font-mono bg-gray-50 px-2 py-1 rounded">Coords: {{ $lokasi->latitude }}, {{ $lokasi->longitude }}</span>
                    <span>Klik pada peta untuk mengaktifkan zoom dengan roda mouse</span>
                </div>
            </div>

        </div>

        {{-- Sidebar (Kanan) --}}
        <div class="space-y-6">
            
            {{-- Informasi Lokasi --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Detail Informasi</h3>
                    @php
                        $badgeClass = match($lokasi->status_verifikasi) {
                            'disetujui' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                            'pending'   => 'bg-amber-50 text-amber-700 border-amber-100',
                            'ditolak'   => 'bg-red-50 text-red-700 border-red-100',
                            'revisi'    => 'bg-blue-50 text-blue-700 border-blue-100',
                            default     => 'bg-gray-50 text-gray-600 border-gray-100',
                        };
                    @endphp
                    <span class="inline-flex px-2.5 py-1 border rounded-full text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                        {{ $lokasi->status_verifikasi }}
                    </span>
                </div>

                <div class="space-y-4">
                    <div class="border-b border-gray-50 pb-3">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Kategori</p>
                        <p class="text-sm text-gray-800 font-semibold">{{ $lokasi->kategori->nama_kategori ?? '-' }}</p>
                    </div>
                    
                    <div class="border-b border-gray-50 pb-3">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Jam Operasional</p>
                        <p class="text-sm text-gray-800 font-semibold">{{ $lokasi->jam_operasional ?: '-' }}</p>
                    </div>

                    <div class="border-b border-gray-50 pb-3">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Kecamatan</p>
                        <p class="text-sm text-gray-800 font-semibold">{{ $lokasi->kecamatan->nama_kecamatan ?? '-' }}</p>
                    </div>

                    <div class="border-b border-gray-50 pb-3">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Kelurahan</p>
                        <p class="text-sm text-gray-800 font-semibold">{{ $lokasi->kelurahan->nama_kelurahan ?? '-' }}</p>
                    </div>

                    <div class="border-b border-gray-50 pb-3">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Kontak</p>
                        <p class="text-sm text-gray-800 font-semibold">{{ $lokasi->kontak ?: '-' }}</p>
                    </div>

                    <div class="border-b border-gray-50 pb-3">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Website</p>
                        @if($lokasi->website && $lokasi->website !== '-')
                            <a href="{{ Str::startsWith($lokasi->website, 'http') ? $lokasi->website : 'https://' . $lokasi->website }}" 
                               target="_blank" 
                               class="text-sm text-navy-600 hover:text-navy-700 hover:underline font-semibold truncate block">
                                {{ $lokasi->website }}
                            </a>
                        @else
                            <p class="text-sm text-gray-400 font-semibold">-</p>
                        @endif
                    </div>

                    <div class="pb-1">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Alamat Lengkap</p>
                        <p class="text-sm text-gray-700 leading-relaxed font-semibold">{{ $lokasi->alamat }}</p>
                    </div>
                </div>
            </div>

            {{-- Kontributor --}}
            <div class="bg-gradient-to-br from-blue-50 to-navy-50/20 border border-blue-100 rounded-2xl p-5 shadow-sm flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-navy-700 to-navy-900 flex items-center justify-center text-white font-bold text-sm flex-shrink-0 shadow-md">
                    {{ strtoupper(substr($lokasi->kontributor->name ?? 'A', 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] text-navy-500 font-bold uppercase tracking-wider mb-0.5">Disubmit oleh</p>
                    <p class="text-sm font-bold text-navy-900 truncate">{{ $lokasi->kontributor->name ?? 'Administrator' }}</p>
                    <p class="text-xs text-navy-600 truncate">{{ $lokasi->kontributor->email ?? 'admin@padang.go.id' }}</p>
                </div>
            </div>

            {{-- Riwayat Approval --}}
            @if($lokasi->approvalLogs->count())
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Riwayat Approval</h3>
                    
                    <div class="relative pl-4 border-l border-gray-100 space-y-4">
                        @foreach($lokasi->approvalLogs as $log)
                            <div class="relative">
                                @php
                                    $dotClass = match($log->status) {
                                        'disetujui' => 'bg-emerald-500',
                                        'pending'   => 'bg-amber-500',
                                        'ditolak'   => 'bg-red-500',
                                        'revisi'    => 'bg-blue-500',
                                        default     => 'bg-gray-400',
                                    };
                                @endphp
                                <div class="absolute -left-[21px] top-1.5 w-2.5 h-2.5 rounded-full border border-white {{ $dotClass }}"></div>
                                
                                <div class="bg-gray-50 rounded-xl px-4 py-3 border border-gray-50">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-xs font-bold text-gray-800 capitalize">{{ $log->status }}</span>
                                        <span class="text-[9px] text-gray-400 font-semibold">{{ $log->created_at->format('d M Y H:i') }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 leading-relaxed">{{ $log->catatan ?: '-' }}</p>
                                    <p class="text-[9px] text-gray-400 font-semibold mt-1.5">oleh {{ $log->admin->name ?? 'System' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lat = {{ $lokasi->latitude }};
        const lng = {{ $lokasi->longitude }};
        
        // Initialize Leaflet Map
        const map = L.map('show-map', {
            scrollWheelZoom: false // Disable scroll zoom by default for better page scrolling
        }).setView([lat, lng], 17);

        // Google Hybrid Satellite Map Layer (matching the style in edit.blade.php)
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
            .bindPopup(`<strong class="text-sm font-bold text-gray-900">{{ addslashes($lokasi->nama_tempat) }}</strong><br><span class="text-xs text-gray-500">{{ addslashes($lokasi->alamat) }}</span>`)
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
