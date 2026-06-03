@extends('layouts.app')

@section('title', 'Tambah Lokasi')

@section('sidebar-menu')
    @include('partials.sidebar-menu')
@endsection

@section('page-header')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.lokasi.index') }}"
            class="p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Lokasi</h1>
            <p class="text-sm text-gray-500 mt-1">Tambahkan lokasi baru ke sistem</p>
        </div>
    </div>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.lokasi.store') }}" enctype="multipart/form-data" class="max-w-3xl">
        @csrf

        <div class="bg-white rounded-2xl border border-gray-100 divide-y divide-gray-100">
            {{-- Informasi Utama --}}
            <div class="p-6 space-y-5">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Informasi Utama</h3>

                <div>
                    <label for="nama_tempat" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Tempat <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_tempat" id="nama_tempat" value="{{ old('nama_tempat') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10">
                    @error('nama_tempat') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="id_kategori" class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                        <select name="id_kategori" id="id_kategori" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id_kategori }}" {{ old('id_kategori') == $kat->id_kategori ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_kategori') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="kontak" class="block text-sm font-semibold text-gray-700 mb-1.5">Kontak</label>
                        <input type="text" name="kontak" id="kontak" value="{{ old('kontak') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10"
                            placeholder="08xx atau (0751)xxx">
                    </div>
                </div>

                <div>
                    <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat <span class="text-red-500">*</span></label>
                    <textarea name="alamat" id="alamat" rows="2" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10">{{ old('alamat') }}</textarea>
                    @error('alamat') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Wilayah --}}
            <div class="p-6 space-y-5">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Wilayah</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="id_kecamatan" class="block text-sm font-semibold text-gray-700 mb-1.5">Kecamatan <span class="text-red-500">*</span></label>
                        <select name="id_kecamatan" id="id_kecamatan" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10">
                            <option value="">Pilih Kecamatan</option>
                            @foreach($kecamatans as $kec)
                                <option value="{{ $kec->id_kecamatan }}" {{ old('id_kecamatan') == $kec->id_kecamatan ? 'selected' : '' }}>
                                    {{ $kec->nama_kecamatan }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_kecamatan') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="id_kelurahan" class="block text-sm font-semibold text-gray-700 mb-1.5">Kelurahan <span class="text-red-500">*</span></label>
                        <select name="id_kelurahan" id="id_kelurahan" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10">
                            <option value="">Pilih Kelurahan</option>
                        </select>
                        @error('id_kelurahan') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Koordinat --}}
            <div class="p-6 space-y-5">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Koordinat GPS</h3>
                <p class="text-xs text-gray-400">Input koordinat manual atau geser marker di peta untuk menentukan lokasi</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="latitude" class="block text-sm font-semibold text-gray-700 mb-1.5">Latitude <span class="text-red-500">*</span></label>
                        <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude') }}" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10"
                            placeholder="-0.9471">
                        @error('latitude') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="longitude" class="block text-sm font-semibold text-gray-700 mb-1.5">Longitude <span class="text-red-500">*</span></label>
                        <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude') }}" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10"
                            placeholder="100.4172">
                        @error('longitude') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Map Preview with Draggable Marker --}}
                <div>
                    <div id="koordinat-map" style="height: 350px; border-radius: 0.75rem; z-index: 10;" class="border border-gray-200 shadow-sm"></div>
                    <p class="text-[11px] text-gray-400 mt-2 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Klik pada peta atau geser marker merah untuk menentukan titik koordinat
                    </p>
                </div>
            </div>

            {{-- Detail Tambahan --}}
            <div class="p-6 space-y-5">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Detail Tambahan</h3>

                <div>
                    <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="jam_operasional" class="block text-sm font-semibold text-gray-700 mb-1.5">Jam Operasional</label>
                        <input type="text" name="jam_operasional" id="jam_operasional" value="{{ old('jam_operasional') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10"
                            placeholder="08:00 - 17:00">
                    </div>
                    <div>
                        <label for="website" class="block text-sm font-semibold text-gray-700 mb-1.5">Website</label>
                        <input type="url" name="website" id="website" value="{{ old('website') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10"
                            placeholder="https://...">
                    </div>
                </div>
            </div>

            {{-- Upload Foto --}}
            <div class="p-6 space-y-5">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Foto Lokasi</h3>

                <div>
                    <label for="foto" class="block text-sm font-semibold text-gray-700 mb-1.5">Upload Foto</label>
                    <input type="file" name="foto[]" id="foto" accept="image/*" multiple
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-navy-50 file:text-navy-700 hover:file:bg-navy-100">
                    <p class="text-[11px] text-gray-400 mt-1">Bisa upload beberapa foto sekaligus (maks 2MB per foto)</p>
                    @error('foto.*') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 mt-5">
            <button type="submit"
                class="px-6 py-2.5 bg-navy-800 text-white text-sm font-semibold rounded-xl hover:bg-navy-700 transition-colors shadow-sm">
                Simpan Lokasi
            </button>
            <a href="{{ route('admin.lokasi.index') }}"
                class="px-6 py-2.5 bg-gray-100 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                Batal
            </a>
        </div>
    </form>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    // Dynamic kelurahan berdasarkan kecamatan
    document.getElementById('id_kecamatan').addEventListener('change', function () {
        const kelSelect = document.getElementById('id_kelurahan');
        kelSelect.innerHTML = '<option value="">Memuat...</option>';

        if (!this.value) {
            kelSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            return;
        }

        fetch(`/api/kecamatan/${this.value}/kelurahan`)
            .then(r => r.json())
            .then(data => {
                kelSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                (data.data || data).forEach(kel => {
                    kelSelect.innerHTML += `<option value="${kel.id_kelurahan}">${kel.nama_kelurahan}</option>`;
                });
            })
            .catch(() => {
                kelSelect.innerHTML = '<option value="">Gagal memuat</option>';
            });
    });

    // Interactive Map with Draggable Marker
    document.addEventListener('DOMContentLoaded', function () {
        const defaultLat = -0.9471;
        const defaultLng = 100.4172;

        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');

        const initialLat = parseFloat(latInput.value) || defaultLat;
        const initialLng = parseFloat(lngInput.value) || defaultLng;
        const hasInitialCoords = latInput.value && lngInput.value;

        const map = L.map('koordinat-map').setView([initialLat, initialLng], hasInitialCoords ? 18 : 17);

        L.tileLayer('https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            attribution: '&copy; Google Maps',
            maxZoom: 21,
        }).addTo(map);

        // Red marker icon
        const redIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41],
        });

        const marker = L.marker([initialLat, initialLng], {
            draggable: true,
            icon: redIcon,
        }).addTo(map);

        // Update input fields when marker is dragged
        function updateInputsFromMarker(latlng) {
            latInput.value = latlng.lat.toFixed(7);
            lngInput.value = latlng.lng.toFixed(7);
        }

        marker.on('dragend', function (e) {
            updateInputsFromMarker(e.target.getLatLng());
        });

        // Click on map to reposition marker
        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            updateInputsFromMarker(e.latlng);
        });

        // Update marker when input fields change
        function updateMarkerFromInputs() {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                const newLatLng = L.latLng(lat, lng);
                marker.setLatLng(newLatLng);
                map.setView(newLatLng, Math.max(map.getZoom(), 15));
            }
        }

        latInput.addEventListener('change', updateMarkerFromInputs);
        lngInput.addEventListener('change', updateMarkerFromInputs);
    });
</script>
@endpush
