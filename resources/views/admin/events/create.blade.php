@extends('layouts.app')

@section('title', 'Tambah Event')

@section('sidebar-menu')
    @include('partials.sidebar-menu')
@endsection

@section('page-header')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.events.index') }}" class="p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Event</h1>
            <p class="text-sm text-gray-500 mt-1">Buat event baru di Kota Padang</p>
        </div>
    </div>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="max-w-3xl">
        @csrf
        <div class="bg-white rounded-2xl border border-gray-100 p-6 space-y-5">
            <div>
                <label for="nama_event" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Event <span class="text-red-500">*</span></label>
                <input type="text" name="nama_event" id="nama_event" value="{{ old('nama_event') }}" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10">
                @error('nama_event') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="lokasi_event" class="block text-sm font-semibold text-gray-700 mb-1.5">Lokasi Event <span class="text-red-500">*</span></label>
                    <input type="text" name="lokasi_event" id="lokasi_event" value="{{ old('lokasi_event') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10"
                        placeholder="Nama tempat / alamat">
                </div>
                <div>
                    <label for="jenis_event" class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Event <span class="text-red-500">*</span></label>
                    <select name="jenis_event" id="jenis_event" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10">
                        <option value="">-- Pilih Jenis Event --</option>
                        <option value="festival" {{ old('jenis_event') == 'festival' ? 'selected' : '' }}>Festival</option>
                        <option value="wisata" {{ old('jenis_event') == 'wisata' ? 'selected' : '' }}>Wisata</option>
                        <option value="olahraga" {{ old('jenis_event') == 'olahraga' ? 'selected' : '' }}>Olahraga</option>
                        <option value="budaya" {{ old('jenis_event') == 'budaya' ? 'selected' : '' }}>Budaya</option>
                        <option value="lainnya" {{ old('jenis_event') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('jenis_event') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                @if(old('jenis_event') == 'lainnya')
                <div id="jenis_event_lainnya_container">
                @else
                <div id="jenis_event_lainnya_container" class="hidden">
                @endif
                    <label for="jenis_event_lainnya" class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Event Lainnya</label>
                    <input type="text" name="jenis_event_lainnya" id="jenis_event_lainnya" value="{{ old('jenis_event_lainnya') }}"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10"
                        placeholder="Sebutkan jenis event lainnya">
                    @error('jenis_event_lainnya') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10">
                </div>
                <div>
                    <label for="tanggal_selesai" class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Selesai <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10">
                </div>
            </div>

            <div>
                <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="3"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10">{{ old('deskripsi') }}</textarea>
            </div>

            <div>
                <label for="banner" class="block text-sm font-semibold text-gray-700 mb-1.5">Banner</label>
                <input type="file" name="banner" id="banner" accept="image/*"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-navy-50 file:text-navy-700 hover:file:bg-navy-100">
            </div>
        </div>

        <div class="flex items-center gap-3 mt-5">
            <button type="submit" class="px-6 py-2.5 bg-navy-800 text-white text-sm font-semibold rounded-xl hover:bg-navy-700 transition-colors shadow-sm">
                Simpan Event
            </button>
            <a href="{{ route('admin.events.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                Batal
            </a>
        </div>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const jenisEventSelect = document.getElementById('jenis_event');
        const lainnyaContainer = document.getElementById('jenis_event_lainnya_container');

        function toggleLainnyaField() {
            if (jenisEventSelect.value === 'lainnya') {
                lainnyaContainer.classList.remove('hidden');
            } else {
                lainnyaContainer.classList.add('hidden');
            }
        }

        jenisEventSelect.addEventListener('change', toggleLainnyaField);
        toggleLainnyaField(); // Initialize on page load
    });
    </script>
@endsection
