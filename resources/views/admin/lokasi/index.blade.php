@extends('layouts.app')

@section('title', 'Master Lokasi')

@section('sidebar-menu')
    @include('partials.sidebar-menu')
@endsection

@section('page-header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Master Lokasi</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola semua data lokasi di Kota Padang</p>
        </div>
        <a href="{{ route('admin.lokasi.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-navy-800 text-white text-sm font-semibold rounded-xl hover:bg-navy-700 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Lokasi
        </a>
    </div>
@endsection

@section('content')
    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-4 mb-5">
        <form method="GET" action="{{ route('admin.lokasi.index') }}" class="flex flex-wrap items-end gap-3">
            {{-- Search --}}
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama tempat..."
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10 transition-all">
            </div>

            {{-- Kategori --}}
            <div class="w-48">
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Kategori</label>
                <select name="kategori"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10 transition-all">
                    <option value="">Semua</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id_kategori }}" {{ request('kategori') == $kat->id_kategori ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div class="w-44">
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Status</label>
                <select name="status"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10 transition-all">
                    <option value="">Semua</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="revisi" {{ request('status') == 'revisi' ? 'selected' : '' }}>Revisi</option>
                </select>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2.5 bg-navy-800 text-white text-sm font-semibold rounded-xl hover:bg-navy-700 transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin.lokasi.index') }}"
                    class="px-4 py-2.5 bg-gray-100 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Nama Tempat</th>
                        <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Kategori</th>
                        <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Kecamatan</th>
                        <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="text-center px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($lokasi as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-3.5">
                                <a href="{{ route('admin.lokasi.show', $item->id_lokasi) }}"
                                    class="text-left font-semibold text-gray-800 hover:text-navy-700 hover:underline transition-colors">
                                    {{ $item->nama_tempat }}
                                </a>
                                <p class="text-[11px] text-gray-400 mt-0.5">oleh {{ $item->kontributor->name ?? 'Admin' }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-gray-600">{{ $item->kategori->nama_kategori ?? '-' }}</td>
                            <td class="px-5 py-3.5 text-gray-600">{{ $item->kecamatan->nama_kecamatan ?? '-' }}</td>
                            <td class="px-5 py-3.5">
                                @php
                                    $badgeClass = match($item->status_verifikasi) {
                                        'disetujui' => 'bg-emerald-50 text-emerald-700',
                                        'pending'   => 'bg-amber-50 text-amber-700',
                                        'ditolak'   => 'bg-red-50 text-red-700',
                                        'revisi'    => 'bg-blue-50 text-blue-700',
                                        default     => 'bg-gray-50 text-gray-600',
                                    };
                                @endphp
                                <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                                    {{ $item->status_verifikasi }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('admin.lokasi.edit', $item->id_lokasi) }}"
                                        class="p-2 text-gray-400 hover:text-navy-700 hover:bg-navy-50 rounded-lg transition-all" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.lokasi.destroy', $item->id_lokasi) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus lokasi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-gray-400">
                                <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                <p class="text-sm font-medium">Belum ada data lokasi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($lokasi->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $lokasi->links() }}
            </div>
        @endif
    </div>
@endsection
