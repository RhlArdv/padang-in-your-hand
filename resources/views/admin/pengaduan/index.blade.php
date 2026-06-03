@extends('layouts.app')

@section('title', 'Pengaduan')

@section('sidebar-menu')
    @include('partials.sidebar-menu')
@endsection

@section('page-header')
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Pengaduan</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola pengaduan dari pengguna</p>
    </div>
@endsection

@section('content')
    {{-- Filter --}}
    <div class="flex items-center gap-2 mb-5">
        @php $current = request('status', ''); @endphp
        <a href="{{ route('admin.pengaduan.index') }}"
            class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ !$current ? 'bg-navy-800 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            Semua
        </a>
        <a href="{{ route('admin.pengaduan.index', ['status' => 'menunggu']) }}"
            class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ $current === 'menunggu' ? 'bg-amber-500 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            Menunggu
        </a>
        <a href="{{ route('admin.pengaduan.index', ['status' => 'diproses']) }}"
            class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ $current === 'diproses' ? 'bg-blue-500 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            Diproses
        </a>
        <a href="{{ route('admin.pengaduan.index', ['status' => 'selesai']) }}"
            class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ $current === 'selesai' ? 'bg-emerald-500 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            Selesai
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Lokasi</th>
                        <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Jenis</th>
                        <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Pelapor</th>
                        <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="text-center px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($pengaduan as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-3.5 font-semibold text-gray-800">{{ $item->lokasi->nama_tempat ?? '-' }}</td>
                            <td class="px-5 py-3.5 text-gray-600 capitalize">{{ $item->jenis_pengaduan }}</td>
                            <td class="px-5 py-3.5 text-gray-600">{{ $item->user->name ?? '-' }}</td>
                            <td class="px-5 py-3.5">
                                @php
                                    $badgeClass = match($item->status) {
                                        'menunggu' => 'bg-amber-50 text-amber-700',
                                        'diproses' => 'bg-blue-50 text-blue-700',
                                        'selesai'  => 'bg-emerald-50 text-emerald-700',
                                        default    => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <a href="{{ route('admin.pengaduan.show', $item->id_pengaduan) }}"
                                    class="p-2 inline-flex text-gray-400 hover:text-navy-700 hover:bg-navy-50 rounded-lg transition-all" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-gray-400 text-sm font-medium">Belum ada pengaduan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengaduan->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $pengaduan->links() }}
            </div>
        @endif
    </div>
@endsection
