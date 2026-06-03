@extends('layouts.app')

@section('title', 'Event Kota')

@section('sidebar-menu')
    @include('partials.sidebar-menu')
@endsection

@section('page-header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Event Kota</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola event dan kegiatan di Kota Padang</p>
        </div>
        <a href="{{ route('admin.events.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-navy-800 text-white text-sm font-semibold rounded-xl hover:bg-navy-700 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Event
        </a>
    </div>
@endsection

@section('content')
    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-4 mb-5">
        <form method="GET" action="{{ route('admin.events.index') }}" class="flex flex-wrap items-end gap-3">
            {{-- Search --}}
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama event..."
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10 transition-all">
            </div>

            {{-- Jenis Event --}}
            <div class="w-48">
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Jenis Event</label>
                <select name="jenis_event"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10 transition-all">
                    <option value="">Semua</option>
                    <option value="festival" {{ request('jenis_event') == 'festival' ? 'selected' : '' }}>Festival</option>
                    <option value="wisata" {{ request('jenis_event') == 'wisata' ? 'selected' : '' }}>Wisata</option>
                    <option value="olahraga" {{ request('jenis_event') == 'olahraga' ? 'selected' : '' }}>Olahraga</option>
                    <option value="budaya" {{ request('jenis_event') == 'budaya' ? 'selected' : '' }}>Budaya</option>
                    <option value="lainnya" {{ request('jenis_event') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            {{-- Status --}}
            <div class="w-44">
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Status</label>
                <select name="status"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10 transition-all">
                    <option value="">Semua</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2.5 bg-navy-800 text-white text-sm font-semibold rounded-xl hover:bg-navy-700 transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin.events.index') }}"
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
                        <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Nama Event</th>
                        <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Lokasi</th>
                        <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Tanggal Mulai</th>
                        <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Tanggal Selesai</th>
                        <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="text-center px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($events as $event)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    {{-- Banner Thumbnail --}}
                                    @if($event->banner)
                                        <img src="{{ $event->banner_url }}" class="w-14 h-10 object-cover rounded-lg border border-gray-100 shadow-sm flex-shrink-0">
                                    @else
                                        <div class="w-14 h-10 bg-gray-50 rounded-lg flex items-center justify-center border border-dashed border-gray-200 text-gray-400 flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <div>
                                        <a href="{{ route('admin.events.show', $event->id_event) }}"
                                            class="font-semibold text-gray-800 hover:text-navy-700 hover:underline transition-colors block">
                                            {{ $event->nama_event }}
                                        </a>
                                        <span class="inline-flex mt-0.5 px-1.5 py-0.5 rounded bg-gray-100 text-[10px] font-medium text-gray-500 capitalize">
                                            {{ $event->jenis_event }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-gray-600">
                                @if($event->id_lokasi && $event->lokasi)
                                    <a href="{{ route('admin.lokasi.show', $event->id_lokasi) }}" class="text-navy-600 hover:underline font-medium">
                                        {{ $event->lokasi->nama_tempat }}
                                    </a>
                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ $event->lokasi_event }}</p>
                                @else
                                    {{ $event->lokasi_event }}
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-gray-600">{{ $event->tanggal_mulai?->format('d M Y') }}</td>
                            <td class="px-5 py-3.5 text-gray-600">{{ $event->tanggal_selesai?->format('d M Y') }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $event->status === 'aktif' ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $event->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('admin.events.show', $event->id_event) }}"
                                        class="p-2 text-gray-400 hover:text-navy-700 hover:bg-navy-50 rounded-lg transition-all" title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.events.edit', $event->id_event) }}"
                                        class="p-2 text-gray-400 hover:text-navy-700 hover:bg-navy-50 rounded-lg transition-all" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.events.destroy', $event->id_event) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus event ini?')">
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
                            <td colspan="6" class="px-5 py-10 text-center text-gray-400">
                                <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-sm font-medium">Belum ada data event</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($events->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $events->links() }}
            </div>
        @endif
    </div>
@endsection
