@extends('layouts.app')

@section('title', 'Detail Pengaduan')

@section('sidebar-menu')
    @include('partials.sidebar-menu')
@endsection

@section('page-header')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.pengaduan.index') }}" class="p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Pengaduan</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $pengaduan->lokasi->nama_tempat ?? '-' }}</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-5xl">
        {{-- Detail --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-2xl border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Informasi Pengaduan</h3>
                    @php
                        $badgeClass = match($pengaduan->status) {
                            'menunggu' => 'bg-amber-50 text-amber-700',
                            'diproses' => 'bg-blue-50 text-blue-700',
                            'selesai'  => 'bg-emerald-50 text-emerald-700',
                            default    => 'bg-gray-100 text-gray-600',
                        };
                    @endphp
                    <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                        {{ $pengaduan->status }}
                    </span>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mb-1">Lokasi</p>
                        <p class="text-sm text-gray-800 font-medium">{{ $pengaduan->lokasi->nama_tempat ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mb-1">Jenis Pengaduan</p>
                        <p class="text-sm text-gray-800 capitalize">{{ $pengaduan->jenis_pengaduan }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mb-1">Pelapor</p>
                        <p class="text-sm text-gray-800">{{ $pengaduan->user->name ?? '-' }} ({{ $pengaduan->user->email ?? '-' }})</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mb-1">Isi Pengaduan</p>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $pengaduan->isi_pengaduan }}</p>
                    </div>

                    @if($pengaduan->foto_bukti)
                        <div>
                            <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mb-2">Foto Bukti</p>
                            <img src="{{ asset('storage/' . $pengaduan->foto_bukti) }}" alt="Foto Bukti"
                                class="max-h-64 rounded-xl object-cover border border-gray-100">
                        </div>
                    @endif

                    @if($pengaduan->catatan_admin)
                        <div class="bg-navy-50 rounded-xl p-4 mt-4">
                            <p class="text-[11px] text-navy-500 font-semibold uppercase tracking-wider mb-1">Catatan Admin</p>
                            <p class="text-sm text-navy-800">{{ $pengaduan->catatan_admin }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Form Tindak Lanjut --}}
        <div>
            <div class="bg-white rounded-2xl border border-gray-100 p-6 sticky top-[80px]">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Tindak Lanjut</h3>

                <form method="POST" action="{{ route('admin.pengaduan.update', $pengaduan->id_pengaduan) }}" class="space-y-4">
                    @csrf @method('PUT')

                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-1.5">Status</label>
                        <select name="status" id="status"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10">
                            <option value="menunggu" {{ $pengaduan->status === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="diproses" {{ $pengaduan->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ $pengaduan->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <div>
                        <label for="catatan_admin" class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan Admin</label>
                        <textarea name="catatan_admin" id="catatan_admin" rows="4"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10"
                            placeholder="Tambahkan catatan...">{{ old('catatan_admin', $pengaduan->catatan_admin) }}</textarea>
                    </div>

                    <button type="submit"
                        class="w-full px-5 py-2.5 bg-navy-800 text-white text-sm font-semibold rounded-xl hover:bg-navy-700 transition-colors shadow-sm">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
