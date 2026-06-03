@extends('layouts.app')

@section('title', 'GIS & Maps')

@section('sidebar-menu')
    @include('partials.sidebar-menu')
@endsection

@section('page-header')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="brand-font text-2xl font-extrabold text-slate-900 tracking-tight">Interactive Map Explorer</h1>
            <p class="text-xs text-slate-500 mt-0.5">Pantau dan kelola sebaran lokasi spot strategis Kota Padang secara real-time</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="flex h-2.5 w-2.5 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
            </span>
            <span class="text-xs font-extrabold text-slate-600 tracking-wide uppercase">System Active</span>
        </div>
    </div>
@endsection

@push('styles')
    <!-- Google Fonts: Plus Jakarta Sans for high-fidelity Display Titles -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <!-- MarkerCluster CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
    
    <style>
        .font-display {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        #map {
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 99px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* High-Fidelity Custom Glassmorphic Popups Overrides */
        .leaflet-popup-content-wrapper {
            background: transparent !important;
            box-shadow: none !important;
            padding: 0 !important;
            border-radius: 1rem !important;
            overflow: hidden !important;
        }

        .leaflet-popup-content {
            margin: 0 !important;
            padding: 0 !important;
            width: 280px !important;
        }

        .leaflet-popup-tip-container {
            margin-top: -1px !important;
        }

        .leaflet-popup-tip {
            background: #ffffff !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05) !important;
        }

        .leaflet-popup-close-button {
            top: 8px !important;
            right: 8px !important;
            color: #ffffff !important;
            background: rgba(15, 23, 42, 0.4) !important;
            border-radius: 99px !important;
            width: 20px !important;
            height: 20px !important;
            line-height: 20px !important;
            font-size: 14px !important;
            text-align: center !important;
            backdrop-filter: blur(4px) !important;
            z-index: 9999 !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            transition: all 0.2s !important;
        }

        .leaflet-popup-close-button:hover {
            background: rgba(15, 23, 42, 0.7) !important;
            color: #ffffff !important;
        }

        /* High-Fidelity Custom Tooltip for Kecamatan Polygons */
        .custom-tooltip {
            background: rgba(15, 23, 42, 0.85) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-weight: 800 !important;
            font-size: 10px !important;
            padding: 4px 8px !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
            backdrop-filter: blur(4px) !important;
        }
        .leaflet-tooltip-top:before { border-top-color: rgba(15, 23, 42, 0.85) !important; }
        .leaflet-tooltip-bottom:before { border-bottom-color: rgba(15, 23, 42, 0.85) !important; }
        .leaflet-tooltip-left:before { border-left-color: rgba(15, 23, 42, 0.85) !important; }
        .leaflet-tooltip-right:before { border-right-color: rgba(15, 23, 42, 0.85) !important; }
    </style>
@endpush

@section('content')
    @php
        function getCategoryColor($name) {
            $name = strtolower($name);
            if (str_contains($name, 'ibadah') || str_contains($name, 'masjid')) return '#10b981'; // Green
            if (str_contains($name, 'sekolah') || str_contains($name, 'kampus')) return '#3b82f6'; // Blue
            if (str_contains($name, 'makan') || str_contains($name, 'cafe')) return '#f59e0b'; // Orange
            if (str_contains($name, 'spbu')) return '#ef4444'; // Red
            if (str_contains($name, 'hotel') || str_contains($name, 'penginapan')) return '#8b5cf6'; // Purple
            if (str_contains($name, 'wisata') || str_contains($name, 'pantai')) return '#ec4899'; // Pink
            if (str_contains($name, 'pemerintah') || str_contains($name, 'polisi') || str_contains($name, 'tni')) return '#475569'; // Slate
            if (str_contains($name, 'sakit') || str_contains($name, 'puskesmas') || str_contains($name, 'apotek')) return '#14b8a6'; // Teal
            if (str_contains($name, 'atm') || str_contains($name, 'bank')) return '#eab308'; // Yellow
            if (str_contains($name, 'olahraga')) return '#f97316'; // Orange
            if (str_contains($name, 'terminal') || str_contains($name, 'pelabuhan')) return '#6366f1'; // Indigo
            if (str_contains($name, 'umkm')) return '#84cc16'; // Lime
            return '#64748b'; // Default Slate
        }
    @endphp

    <!-- Outer Premium Glassmorphic Layout Frame -->
    <div class="relative w-full h-[calc(100vh-170px)] min-h-[600px] rounded-3xl overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.12)] border border-slate-200/60 bg-white flex flex-col lg:flex-row">
        
        <!-- ==========================================
             LEFT SIDEBAR: LOCATIONS LIST & SEARCH
             ========================================== -->
        <div class="w-full lg:w-[360px] xl:w-[400px] h-[350px] lg:h-full border-b lg:border-b-0 lg:border-r border-slate-200/60 flex flex-col bg-slate-50/30 z-10">
            <!-- Header Search Section -->
            <div class="p-4 bg-white border-b border-slate-100 flex flex-col gap-3 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" id="sidebar-search" autocomplete="off" placeholder="Cari nama spot atau alamat..."
                        class="w-full pl-10 pr-10 py-2.5 bg-slate-100 hover:bg-slate-200/60 focus:bg-white border-0 rounded-2xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all duration-200">
                    <button type="button" id="clear-search" class="absolute inset-y-0 right-0 pr-3.5 hidden items-center justify-center text-slate-400 hover:text-slate-600 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex items-center justify-between text-[10px] font-extrabold tracking-wider uppercase text-slate-500">
                    <span>Menampilkan <span id="spots-count" class="text-indigo-600 text-xs font-black">0</span> Spot</span>
                    <span class="flex items-center gap-1.5">
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        Live Map
                    </span>
                </div>
            </div>
            
            <!-- Location Cards Scrollable Area -->
            <div id="location-list" class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-3">
                <!-- Dynamic Location Cards Rendered via JS -->
            </div>
        </div>

        <!-- ==========================================
             RIGHT PANEL: MAP VIEWPORT
             ========================================== -->
        <div class="flex-1 h-full relative">
            <div id="map"></div>

            <!-- ==========================================
                 FLOATING CATEGORY FILTER PANEL (TOP RIGHT)
                 ========================================== -->
            <div class="absolute top-4 right-4 z-[999] w-72 px-2 md:px-0" x-data="{ collapsed: true }">
                <div class="backdrop-blur-xl bg-white/80 border border-white/50 shadow-[0_8px_32px_0_rgba(31,38,135,0.08)] rounded-2xl overflow-hidden transition-all duration-300">
                    <!-- Header -->
                    <div class="p-3.5 flex items-center justify-between border-b border-white/40 bg-white/30">
                        <div class="flex items-center gap-2 cursor-pointer select-none" @click="collapsed = !collapsed">
                            <span class="text-sm">🔮</span>
                            <h3 class="font-display text-xs font-extrabold text-slate-800 uppercase tracking-widest">Kategori Filter</h3>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1.5" x-show="!collapsed" x-transition>
                                <button type="button" id="btn-select-all"
                                    class="text-[9px] font-extrabold text-indigo-600 hover:text-indigo-800 uppercase tracking-widest transition-colors bg-indigo-50/80 hover:bg-indigo-100/80 px-2 py-1 rounded-md">Semua</button>
                                <button type="button" id="btn-deselect-all"
                                    class="text-[9px] font-extrabold text-slate-400 hover:text-slate-600 uppercase tracking-widest transition-colors bg-slate-50/80 hover:bg-slate-100/80 px-2 py-1 rounded-md">Reset</button>
                            </div>
                            <button @click="collapsed = !collapsed" class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg hover:bg-white/50">
                                <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': collapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Categories Scrollable Area -->
                    <div class="p-2.5 max-h-[300px] overflow-y-auto custom-scrollbar space-y-1" x-show="!collapsed" x-transition>
                        @foreach($kategoris as $kat)
                            @php
                                $color = getCategoryColor($kat->nama_kategori);
                            @endphp
                            <label class="flex items-center justify-between gap-3 cursor-pointer group p-2 rounded-xl hover:bg-slate-50/80 border border-transparent hover:border-slate-100/50 transition-all duration-200">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="w-1.5 h-6 rounded-full flex-shrink-0" style="background-color: {{ $color }}"></span>
                                    <span class="text-xs font-bold text-slate-700 group-hover:text-indigo-900 transition-colors truncate">{{ $kat->nama_kategori }}</span>
                                </div>
                                <input type="checkbox" value="{{ $kat->id_kategori }}" checked
                                    class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/20 kategori-checkbox transition-all duration-200">
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <!-- MarkerCluster JS -->
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Pusat Kota Padang
            const map = L.map('map', {
                zoomControl: false, // Matikan tombol default agar layout tetap bersih & HUD-able
                preferCanvas: true
            }).setView([-0.9471, 100.4172], 13);

            // Tambahkan kontrol zoom kustom di bagian kanan bawah
            L.control.zoom({
                position: 'bottomright'
            }).addTo(map);

            L.tileLayer('https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                attribution: '&copy; Google Maps',
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            }).addTo(map);

            // ==========================================
            // KECAMATAN POLYGONS (GEOJSON) INTEGRATION
            // ==========================================
            function getKecamatanColor(name) {
                const cleanName = (name || '').toLowerCase().replace('kecamatan', '').replace('kec.', '').trim();
                if (cleanName.includes('barat')) return '#6366f1'; // Indigo
                if (cleanName.includes('timur')) return '#3b82f6'; // Blue
                if (cleanName.includes('utara')) return '#14b8a6'; // Teal
                if (cleanName.includes('selatan')) return '#f59e0b'; // Orange
                if (cleanName.includes('kuranji')) return '#10b981'; // Green
                if (cleanName.includes('tangah')) return '#8b5cf6'; // Purple
                if (cleanName.includes('nanggalo')) return '#ec4899'; // Pink
                if (cleanName.includes('begalung')) return '#06b6d4'; // Cyan
                if (cleanName.includes('kilangan')) return '#84cc16'; // Lime
                if (cleanName.includes('pauh')) return '#f97316'; // Orange-Red
                if (cleanName.includes('bungus') || cleanName.includes('kabung')) return '#ef4444'; // Red
                return '#64748b'; // Default Slate
            }

            fetch('/geojson/padang-kecamatan.geojson')
                .then(response => {
                    if (!response.ok) throw new Error('File GeoJSON tidak ditemukan');
                    return response.json();
                })
                .then(geojsonData => {
                    L.geoJSON(geojsonData, {
                        style: function(feature) {
                            const name = feature.properties.nama || feature.properties.nama_kecamatan || feature.properties.NAME_3 || feature.properties.KECAMATAN || '';
                            const color = getKecamatanColor(name);
                            return {
                                color: color,
                                weight: 2,
                                opacity: 0.6,
                                fillColor: color,
                                fillOpacity: 0.12
                            };
                        },
                        onEachFeature: function(feature, layer) {
                            layer.on({
                                mouseover: function(e) {
                                    const l = e.target;
                                    const name = feature.properties.nama || feature.properties.nama_kecamatan || feature.properties.NAME_3 || feature.properties.KECAMATAN || '';
                                    const color = getKecamatanColor(name);
                                    l.setStyle({
                                        fillOpacity: 0.28,
                                        weight: 3,
                                        color: color
                                    });
                                    l.bringToBack();
                                },
                                mouseout: function(e) {
                                    const l = e.target;
                                    const name = feature.properties.nama || feature.properties.nama_kecamatan || feature.properties.NAME_3 || feature.properties.KECAMATAN || '';
                                    const color = getKecamatanColor(name);
                                    l.setStyle({
                                        fillOpacity: 0.12,
                                        weight: 2,
                                        color: color
                                    });
                                }
                            });

                            const name = feature.properties.nama || feature.properties.nama_kecamatan || feature.properties.NAME_3 || feature.properties.KECAMATAN || 'Kecamatan';
                            layer.bindTooltip(name, {
                                permanent: false,
                                direction: 'center',
                                className: 'custom-tooltip'
                            });
                        }
                    }).addTo(map);
                })
                .catch(error => {
                    console.warn('GeoJSON kecamatan tidak dimuat:', error.message);
                });

            const lokasiData = @json($lokasi);
            const allMarkers = []; // Simpan referensi ke semua marker

            // Konfigurasi Modern Gen-Z Cluster
            const markerClusterGroup = L.markerClusterGroup({
                maxClusterRadius: 80,
                iconCreateFunction: function(cluster) {
                    const count = cluster.getChildCount();
                    let sizeClass = 'w-10 h-10 text-xs';
                    if (count > 100) sizeClass = 'w-14 h-14 text-sm';
                    else if (count > 50) sizeClass = 'w-12 h-12 text-xs';

                    return L.divIcon({
                        html: `
                            <div class="${sizeClass} flex items-center justify-center rounded-full bg-white/95 backdrop-blur-md shadow-2xl border border-slate-200/50 text-slate-800 font-extrabold ring-[6px] ring-indigo-500/10 transition-transform hover:scale-110 duration-200">
                                ${count}
                            </div>
                        `,
                        className: 'custom-cluster-icon',
                        iconSize: L.point(40, 40)
                    });
                },
                spiderfyOnMaxZoom: true,
                showCoverageOnHover: false,
                zoomToBoundsOnClick: true
            });

            // Color palette mapping for categories
            function getCategoryStyle(categoryName) {
                const name = (categoryName || '').toLowerCase();
                if (name.includes('ibadah') || name.includes('masjid')) return { color: '#10b981', initial: '🕌' }; // Green
                if (name.includes('sekolah') || name.includes('kampus')) return { color: '#3b82f6', initial: '🎓' }; // Blue
                if (name.includes('makan') || name.includes('cafe')) return { color: '#f59e0b', initial: '🍽️' }; // Orange
                if (name.includes('spbu')) return { color: '#ef4444', initial: '⛽' }; // Red
                if (name.includes('hotel') || name.includes('penginapan')) return { color: '#8b5cf6', initial: '🏨' }; // Purple
                if (name.includes('wisata') || name.includes('pantai')) return { color: '#ec4899', initial: '🏖️' }; // Pink
                if (name.includes('pemerintah') || name.includes('polisi') || name.includes('tni')) return { color: '#475569', initial: '🏛️' }; // Slate
                if (name.includes('sakit') || name.includes('puskesmas') || name.includes('apotek')) return { color: '#14b8a6', initial: '🏥' }; // Teal
                if (name.includes('atm') || name.includes('bank')) return { color: '#eab308', initial: '🏧' }; // Yellow
                if (name.includes('olahraga')) return { color: '#f97316', initial: '⚽' }; // Orange
                if (name.includes('terminal') || name.includes('pelabuhan')) return { color: '#6366f1', initial: '🚌' }; // Indigo
                if (name.includes('umkm')) return { color: '#84cc16', initial: '🏪' }; // Lime
                return { color: '#64748b', initial: '📍' }; // Default Slate
            }

            // Generate all markers
            lokasiData.forEach(loc => {
                if (!loc.latitude || !loc.longitude) return;

                const style = getCategoryStyle(loc.kategori?.nama_kategori);
                const imageUrl = loc.foto_utama ? `{{ asset('storage') }}/` + loc.foto_utama.file_foto : null;
                const urlDetail = `{{ url('admin/lokasi') }}/` + loc.id_lokasi;
                
                // Custom SVG Marker with Emoji
                const customIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `
                        <div class="relative flex items-center justify-center w-9 h-9 -mt-4 -ml-4 transition-all duration-300 hover:scale-115 hover:-translate-y-0.5 drop-shadow-[0_8px_16px_rgba(0,0,0,0.12)]">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="absolute w-full h-full top-0 left-0">
                                <path d="M12 21.5C12 21.5 20.5 15.79 20.5 9.5C20.5 4.80558 16.6944 1 12 1C7.30558 1 3.5 4.80558 3.5 9.5C3.5 15.79 12 21.5 12 21.5Z" fill="${style.color}" stroke="white" stroke-width="2"/>
                                <circle cx="12" cy="9.5" r="4.5" fill="white"/>
                            </svg>
                            <span class="relative z-10 text-[11px] -mt-1 leading-none select-none">${style.initial}</span>
                        </div>
                    `,
                    iconSize: [36, 36],
                    iconAnchor: [18, 36],
                    popupAnchor: [0, -32]
                });

                // Generate HTML Popup
                const popupContent = `
                    <div class="overflow-hidden rounded-2xl bg-white shadow-[0_20px_40px_rgba(0,0,0,0.15)] flex flex-col font-sans max-w-[280px]">
                        <div class="relative h-28 w-full bg-slate-100 overflow-hidden select-none">
                            ${imageUrl ? `
                                <img src="${imageUrl}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                            ` : `
                                <div class="w-full h-full flex items-center justify-center relative overflow-hidden select-none" style="background: linear-gradient(135deg, ${style.color}15, ${style.color}28)">
                                    <div class="absolute inset-0 opacity-[0.06]" style="background-image: radial-gradient(${style.color} 1.5px, transparent 1.5px); background-size: 10px 10px;"></div>
                                    <div class="absolute -right-4 -bottom-4 w-16 h-16 rounded-full blur-xl opacity-30" style="background-color: ${style.color}"></div>
                                    <div class="absolute -left-4 -top-4 w-16 h-16 rounded-full blur-xl opacity-25" style="background-color: ${style.color}"></div>
                                    <div class="relative z-10 w-12 h-12 rounded-2xl bg-white/90 backdrop-blur-md shadow-[0_8px_24px_rgba(15,23,42,0.08)] border border-white flex items-center justify-center text-2xl transform transition-transform duration-300 hover:scale-110">
                                        <span class="drop-shadow-[0_2px_4px_rgba(0,0,0,0.08)] filter saturate-[1.1]">${style.initial}</span>
                                    </div>
                                </div>
                            `}
                            <span class="absolute top-2.5 left-2.5 px-2 py-0.5 text-[9px] font-extrabold text-white uppercase tracking-wider rounded-md backdrop-blur-md shadow-sm border border-white/10" style="background-color: ${style.color}">
                                ${loc.kategori?.nama_kategori || 'Spot'}
                            </span>
                        </div>
                        <div class="p-3.5 flex-1 flex flex-col">
                            <h4 class="text-xs font-extrabold text-slate-800 leading-snug mb-1 font-display hover:text-indigo-600 transition-colors cursor-pointer" onclick="window.location.href='${urlDetail}'">
                                ${loc.nama_tempat}
                            </h4>
                            <div class="flex items-center gap-1.5 mb-3.5 min-w-0">
                                ${loc.rating_avg ? `
                                    <div class="flex items-center text-amber-500 text-[10px] font-bold flex-shrink-0">
                                        ★ <span class="text-slate-600 ml-0.5">${loc.rating_avg.toFixed(1)}</span>
                                    </div>
                                    <span class="text-slate-300 text-[9px] flex-shrink-0">•</span>
                                ` : ''}
                                <span class="text-slate-400 text-[10px] truncate w-full" title="${loc.alamat || ''}">${loc.alamat || ''}</span>
                            </div>
                            <div class="mt-auto pt-2.5 border-t border-slate-100 flex flex-col gap-1.5">
                                <a href="${urlDetail}" class="w-full text-center px-3 py-2 bg-slate-900 text-white text-[10px] font-extrabold rounded-xl transition-all duration-200 hover:bg-indigo-600 hover:shadow-[0_4px_12px_rgba(99,102,241,0.25)] flex items-center justify-center gap-1 group">
                                    Buka Detail Spot <span class="inline-block transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5">↗</span>
                                </a>
                            </div>
                        </div>
                    </div>
                `;

                const marker = L.marker([loc.latitude, loc.longitude], { icon: customIcon })
                    .bindPopup(popupContent);

                // Inject variables on marker
                marker._kategoriId = loc.id_kategori;
                marker._locId = loc.id_lokasi;
                
                marker.on('click', function () {
                    highlightSidebarCard(loc.id_lokasi);
                });

                allMarkers.push(marker);
            });

            // Re-usable elements
            const searchInput = document.getElementById('sidebar-search');
            const clearSearchBtn = document.getElementById('clear-search');
            const locationListContainer = document.getElementById('location-list');
            const checkboxes = document.querySelectorAll('.kategori-checkbox');

            // Synchronized Filter & Render Logic
            function updateMapAndList() {
                const query = searchInput.value.toLowerCase().trim();
                const checkedIds = [...checkboxes].filter(c => c.checked).map(c => parseInt(c.value));

                if (query) {
                    clearSearchBtn.classList.remove('hidden');
                    clearSearchBtn.classList.add('flex');
                } else {
                    clearSearchBtn.classList.remove('flex');
                    clearSearchBtn.classList.add('hidden');
                }

                // Filter Data
                const filteredData = lokasiData.filter(loc => {
                    const matchesCategory = checkedIds.includes(loc.id_kategori);
                    const matchesSearch = !query || 
                        (loc.nama_tempat && loc.nama_tempat.toLowerCase().includes(query)) ||
                        (loc.alamat && loc.alamat.toLowerCase().includes(query));
                    return matchesCategory && matchesSearch;
                });

                // 1. Rebuild Map Clusters
                markerClusterGroup.clearLayers();
                const filteredMarkers = allMarkers.filter(m => 
                    filteredData.some(fd => fd.id_lokasi === m._locId)
                );
                markerClusterGroup.addLayers(filteredMarkers);

                // 2. Update Counter
                document.getElementById('spots-count').textContent = filteredData.length;

                // 3. Render List
                if (filteredData.length === 0) {
                    locationListContainer.innerHTML = `
                        <div class="flex flex-col items-center justify-center py-16 text-center select-none">
                            <span class="text-3xl mb-2">🔍</span>
                            <p class="text-xs font-bold text-slate-400 font-display">Tidak ada spot yang cocok</p>
                            <p class="text-[10px] text-slate-400 mt-1">Coba sesuaikan kata kunci atau kategori filter</p>
                        </div>
                    `;
                    return;
                }

                let html = '';
                filteredData.forEach(loc => {
                    const style = getCategoryStyle(loc.kategori?.nama_kategori);
                    const imageUrl = loc.foto_utama ? `{{ asset('storage') }}/` + loc.foto_utama.file_foto : null;
                    const urlDetail = `{{ url('admin/lokasi') }}/` + loc.id_lokasi;
                    const ratingHtml = loc.rating_avg ? `
                        <div class="flex items-center text-[10px] text-amber-500 font-black flex-shrink-0 select-none">
                            ★ <span class="text-slate-700 ml-0.5">${loc.rating_avg.toFixed(1)}</span>
                        </div>
                    ` : '';

                    html += `
                        <div class="location-card bg-white border border-slate-200/50 rounded-2xl p-3 shadow-sm hover:shadow-md hover:border-indigo-200 cursor-pointer transition-all duration-300 flex gap-3 group" data-id="${loc.id_lokasi}">
                            <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0 relative select-none">
                                ${imageUrl ? `
                                    <img src="${imageUrl}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                ` : `
                                    <div class="w-full h-full flex items-center justify-center text-xl bg-slate-50 border border-slate-100" style="background: linear-gradient(135deg, ${style.color}10, ${style.color}25)">
                                        ${style.initial}
                                    </div>
                                `}
                            </div>
                            <div class="min-w-0 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between gap-1.5">
                                        <h4 class="text-xs font-extrabold text-slate-800 leading-tight truncate w-full group-hover:text-indigo-600 transition-colors font-display">${loc.nama_tempat}</h4>
                                        ${ratingHtml}
                                    </div>
                                    <p class="text-[9px] text-slate-400 truncate mt-0.5" title="${loc.alamat || ''}">${loc.alamat || ''}</p>
                                </div>
                                <div class="flex items-center justify-between mt-2.5">
                                    <span class="text-[8px] font-black px-1.5 py-0.5 rounded-md text-white tracking-wider uppercase select-none" style="background-color: ${style.color}e0">
                                        ${loc.kategori?.nama_kategori || 'Spot'}
                                    </span>
                                    <a href="${urlDetail}" class="text-[9px] font-extrabold text-slate-500 hover:text-indigo-600 flex items-center gap-0.5 uppercase tracking-wider transition-colors">
                                        Detail ➔
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                });

                locationListContainer.innerHTML = html;

                // Re-bind click event to sidebar cards
                document.querySelectorAll('.location-card').forEach(card => {
                    card.addEventListener('click', function() {
                        const id = parseInt(this.getAttribute('data-id'));
                        const loc = lokasiData.find(l => l.id_lokasi === id);
                        const marker = allMarkers.find(m => m._locId === id);

                        if (loc && marker) {
                            // Active styling highlight
                            document.querySelectorAll('.location-card').forEach(c => {
                                c.classList.remove('ring-2', 'ring-indigo-500/20', 'border-indigo-400', 'shadow-md');
                                c.classList.add('border-slate-200/50');
                            });
                            this.classList.remove('border-slate-200/50');
                            this.classList.add('ring-2', 'ring-indigo-500/20', 'border-indigo-400', 'shadow-md');

                            // Pan to map coordinates
                            map.setView([loc.latitude, loc.longitude], 17, {
                                animate: true,
                                duration: 1.2
                            });

                            // Open popup on animation completion
                            setTimeout(() => {
                                marker.openPopup();
                            }, 400);
                        }
                    });
                });
            }

            // Helper to highlight card on map marker click and scroll it into view
            function highlightSidebarCard(id) {
                const card = document.querySelector(`.location-card[data-id="${id}"]`);
                if (card) {
                    card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    document.querySelectorAll('.location-card').forEach(c => {
                        c.classList.remove('ring-2', 'ring-indigo-500/20', 'border-indigo-400', 'shadow-md');
                        c.classList.add('border-slate-200/50');
                    });
                    card.classList.remove('border-slate-200/50');
                    card.classList.add('ring-2', 'ring-indigo-500/20', 'border-indigo-400', 'shadow-md');
                }
            }

            // Event Listeners
            searchInput.addEventListener('input', updateMapAndList);
            clearSearchBtn.addEventListener('click', () => {
                searchInput.value = '';
                updateMapAndList();
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateMapAndList);
            });

            document.getElementById('btn-select-all').addEventListener('click', () => {
                checkboxes.forEach(cb => cb.checked = true);
                updateMapAndList();
            });

            document.getElementById('btn-deselect-all').addEventListener('click', () => {
                checkboxes.forEach(cb => cb.checked = false);
                updateMapAndList();
            });

            // Initialize Map Group & Trigger Initial Render
            map.addLayer(markerClusterGroup);
            updateMapAndList();

            // Handle Resize
            window.addEventListener('resize', () => {
                setTimeout(() => {
                    map.invalidateSize({ animate: true });
                }, 200);
            });
        });
    </script>
@endpush