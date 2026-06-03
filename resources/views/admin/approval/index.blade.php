@extends('layouts.app')

@section('title', 'Approval Kontributor')

@section('sidebar-menu')
    @include('partials.sidebar-menu')
@endsection

@section('page-header')
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Approval Kontributor</h1>
        <p class="text-sm text-gray-500 mt-1">Verifikasi lokasi yang disubmit oleh kontributor</p>
    </div>
@endsection

@section('content')
    {{-- Filter Tabs --}}
    <div class="flex items-center gap-2 mb-5">
        @php $current = request('status', ''); @endphp
        <a href="{{ route('admin.approval.index') }}"
            class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ !$current ? 'bg-navy-800 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            Semua
        </a>
        <a href="{{ route('admin.approval.index', ['status' => 'pending']) }}"
            class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ $current === 'pending' ? 'bg-amber-500 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            Pending
        </a>
        <a href="{{ route('admin.approval.index', ['status' => 'revisi']) }}"
            class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ $current === 'revisi' ? 'bg-blue-500 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            Revisi
        </a>
        <a href="{{ route('admin.approval.index', ['status' => 'disetujui']) }}"
            class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ $current === 'disetujui' ? 'bg-emerald-500 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            Disetujui
        </a>
        <a href="{{ route('admin.approval.index', ['status' => 'ditolak']) }}"
            class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ $current === 'ditolak' ? 'bg-red-500 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            Ditolak
        </a>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Nama Tempat</th>
                        <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Kontributor</th>
                        <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Kategori</th>
                        <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="text-center px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50" x-data>
                    @forelse($lokasi as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            {{-- Nama Tempat — klik untuk buka detail --}}
                            <td class="px-5 py-3.5">
                                <a href="{{ route('admin.approval.show', $item->id_lokasi) }}"
                                    class="text-left font-semibold text-gray-800 hover:text-navy-700 hover:underline transition-colors">
                                    {{ $item->nama_tempat }}
                                </a>
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="text-gray-700">{{ $item->kontributor->name ?? '-' }}</p>
                                <p class="text-[11px] text-gray-400">{{ $item->kontributor->email ?? '' }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-gray-600">{{ $item->kategori->nama_kategori ?? '-' }}</td>
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
                                    {{-- Approve --}}
                                    @if($item->status_verifikasi !== 'disetujui')
                                        <form method="POST" action="{{ route('admin.approval.approve', $item->id_lokasi) }}"
                                            onsubmit="return confirm('Setujui lokasi ini?')">
                                            @csrf
                                            <button type="submit"
                                                class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all" title="Setujui">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Revisi --}}
                                    @if(in_array($item->status_verifikasi, ['pending', 'revisi']))
                                        <button type="button"
                                            @click="$dispatch('open-modal', { id: {{ $item->id_lokasi }}, type: 'revision', name: '{{ $item->nama_tempat }}' })"
                                            class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Minta Revisi">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        </button>
                                    @endif

                                    {{-- Tolak --}}
                                    @if(in_array($item->status_verifikasi, ['pending', 'revisi']))
                                        <button type="button"
                                            @click="$dispatch('open-modal', { id: {{ $item->id_lokasi }}, type: 'reject', name: '{{ $item->nama_tempat }}' })"
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Tolak">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-gray-400">
                                <p class="text-sm font-medium">Tidak ada data untuk ditampilkan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($lokasi->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $lokasi->links() }}
            </div>
        @endif
    </div>



    {{-- ============================================================ --}}
    {{-- MODAL CATATAN (Tolak / Revisi) — sama seperti sebelumnya     --}}
    {{-- ============================================================ --}}
    <div x-data="{ show: false, id: null, type: '', name: '' }"
         @open-modal.window="show = true; id = $event.detail.id; type = $event.detail.type; name = $event.detail.name"
         x-show="show" style="display: none;"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4">

        <div x-show="show" x-transition.opacity @click="show = false"
             class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

        <div x-show="show" x-transition
             class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 z-10">
            <h3 class="text-lg font-bold text-gray-900 mb-1" x-text="type === 'reject' ? 'Tolak Lokasi' : 'Minta Revisi'"></h3>
            <p class="text-sm text-gray-500 mb-4" x-text="name"></p>

            <form :action="type === 'reject'
                    ? '{{ url('admin/approval') }}/' + id + '/reject'
                    : '{{ url('admin/approval') }}/' + id + '/revision'"
                  method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Catatan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="catatan" rows="3" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10"
                        placeholder="Berikan alasan..."></textarea>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit"
                        :class="type === 'reject' ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700'"
                        class="px-5 py-2.5 text-white text-sm font-semibold rounded-xl transition-colors">
                        <span x-text="type === 'reject' ? 'Tolak' : 'Minta Revisi'"></span>
                    </button>
                    <button type="button" @click="show = false"
                        class="px-5 py-2.5 bg-gray-100 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection