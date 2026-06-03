@extends('layouts.app')

@section('title', 'Manajemen User')

@section('sidebar-menu')
    @include('partials.sidebar-menu')
@endsection

@section('page-header')
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen User</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola pengguna dan role akses sistem</p>
    </div>
@endsection

@section('content')
    {{-- Filter --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-4 mb-5">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau email..."
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10">
            </div>
            <div class="w-44">
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Role</label>
                <select name="role"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10">
                    <option value="">Semua</option>
                    @foreach(['super_admin', 'admin', 'operator', 'kontributor', 'user'] as $r)
                        <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $r)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2.5 bg-navy-800 text-white text-sm font-semibold rounded-xl hover:bg-navy-700 transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin.users.index') }}"
                    class="px-4 py-2.5 bg-gray-100 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden" x-data>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Nama</th>
                        <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Email</th>
                        <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Role</th>
                        <th class="text-center px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    @if($user->foto)
                                        <img src="{{ asset('storage/' . $user->foto) }}" class="w-8 h-8 rounded-lg object-cover flex-shrink-0">
                                    @else
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-navy-700 to-navy-900
                                                    flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                            @php
                                                $words = explode(' ', $user->name);
                                                $initials = '';
                                                if (count($words) >= 2) {
                                                    $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                                                } else {
                                                    $initials = strtoupper(substr($user->name, 0, 2));
                                                }
                                            @endphp
                                            {{ $initials }}
                                        </div>
                                    @endif
                                    <span class="font-semibold text-gray-800">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-gray-600">{{ $user->email }}</td>
                            <td class="px-5 py-3.5">
                                @php
                                    $roleBadge = match($user->role) {
                                        'super_admin' => 'bg-purple-50 text-purple-700',
                                        'admin'       => 'bg-navy-50 text-navy-700',
                                        'operator'    => 'bg-sky-50 text-sky-700',
                                        'kontributor' => 'bg-emerald-50 text-emerald-700',
                                        default       => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $roleBadge }}">
                                    {{ str_replace('_', ' ', $user->role) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($user->id !== auth()->id())
                                    <div class="flex items-center justify-center gap-1">
                                        {{-- Ubah Role --}}
                                        <button type="button"
                                            @click="$dispatch('open-role-modal', { id: {{ $user->id }}, name: '{{ $user->name }}', role: '{{ $user->role }}' })"
                                            class="p-2 text-gray-400 hover:text-navy-700 hover:bg-navy-50 rounded-lg transition-all" title="Ubah Role">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        {{-- Hapus --}}
                                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                            onsubmit="return confirm('Yakin ingin menghapus user ini?')">
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
                                @else
                                    <p class="text-center text-[11px] text-gray-300 italic">Anda</p>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-10 text-center text-gray-400 text-sm font-medium">Tidak ada user ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Ubah Role --}}
    <div x-data="{ show: false, id: null, name: '', role: '' }"
         @open-role-modal.window="show = true; id = $event.detail.id; name = $event.detail.name; role = $event.detail.role"
         x-show="show" style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4">

        <div x-show="show" x-transition.opacity @click="show = false" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

        <div x-show="show" x-transition class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 z-10">
            <h3 class="text-lg font-bold text-gray-900 mb-1">Ubah Role</h3>
            <p class="text-sm text-gray-500 mb-4" x-text="name"></p>

            <form :action="'{{ url('admin/users') }}/' + id + '/role'" method="POST">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Role Baru</label>
                    <select name="role" x-model="role"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10">
                        @foreach(['super_admin', 'admin', 'operator', 'kontributor', 'user'] as $r)
                            <option value="{{ $r }}">{{ ucwords(str_replace('_', ' ', $r)) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                        class="px-5 py-2.5 bg-navy-800 text-white text-sm font-semibold rounded-xl hover:bg-navy-700 transition-colors">
                        Simpan
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
