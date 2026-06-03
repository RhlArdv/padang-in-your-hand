@extends('layouts.app')

@section('title', 'Edit Profil')

@section('sidebar-menu')
    @include('partials.sidebar-menu')
@endsection

@section('page-header')
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Pengaturan Profil</h1>
        <p class="text-sm text-gray-500 mt-1">Ubah data diri, kelola keamanan, dan lihat ringkasan akun Anda</p>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6" x-data="{}">
        
        {{-- Kolom Kiri: Form Informasi Utama (col-span 8) --}}
        <div class="lg:col-span-8 space-y-6">
            <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Kolom Kanan: Detail Akun & Quick Actions (col-span 4) --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm">
                <header class="border-b border-gray-100 pb-4 mb-4">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Ringkasan Akun</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Status dan informasi login Anda</p>
                </header>

                <div class="space-y-4 text-sm">
                    {{-- Status Role --}}
                    <div class="flex justify-between items-center py-1">
                        <span class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Role</span>
                        @php
                            $roleBadge = match(auth()->user()->role) {
                                'super_admin' => 'bg-purple-50 text-purple-700 border-purple-100',
                                'admin'       => 'bg-navy-50 text-navy-700 border-navy-100',
                                'operator'    => 'bg-sky-50 text-sky-700 border-sky-100',
                                'kontributor' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                default       => 'bg-gray-50 text-gray-600 border-gray-100',
                            };
                        @endphp
                        <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg border {{ $roleBadge }}">
                            {{ str_replace('_', ' ', auth()->user()->role) }}
                        </span>
                    </div>

                    {{-- Email Terverifikasi --}}
                    <div class="flex justify-between items-center py-1">
                        <span class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Email</span>
                        @if(auth()->user()->email_verified_at)
                            <span class="inline-flex items-center gap-1 text-emerald-600 text-xs font-bold bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Terverifikasi
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-amber-600 text-xs font-bold bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-100">
                                Belum Verifikasi
                            </span>
                        @endif
                    </div>

                    {{-- Tanggal Bergabung --}}
                    <div class="flex justify-between items-center py-1">
                        <span class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Bergabung</span>
                        <span class="text-gray-800 font-semibold text-xs">{{ auth()->user()->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="border-t border-gray-100 pt-5 mt-5 space-y-3">
                    {{-- Ubah Password Trigger --}}
                    <button type="button" x-on:click.prevent="$dispatch('open-modal', 'change-password')"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-sm font-semibold text-gray-700 rounded-xl hover:bg-gray-50 active:scale-[0.98] transition-all shadow-sm cursor-pointer">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Ubah Password
                    </button>

                    {{-- Hapus Akun Trigger --}}
                    <button type="button" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-50 text-sm font-semibold text-red-600 rounded-xl hover:bg-red-100 hover:text-red-700 active:scale-[0.98] transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Akun
                    </button>
                </div>
            </div>
        </div>

    </div>

    {{-- ================================================
    POPUP MODALS
    ================================================ --}}
    
    {{-- Modal Ubah Password --}}
    <x-modal name="change-password" :show="$errors->updatePassword->isNotEmpty()" focusable>
        <div class="p-6">
            @include('profile.partials.update-password-form')
        </div>
    </x-modal>

    {{-- Modal Hapus Akun --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <div class="p-6">
            @include('profile.partials.delete-user-form')
        </div>
    </x-modal>

@endsection


