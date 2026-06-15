{{-- ============================================================
     SIDEBAR MENU — reusable partial
     Include dengan: @include('partials.sidebar-menu')
     ============================================================ --}}

<p class="px-3 pt-1 pb-2 text-[10px] font-semibold text-gray-400 uppercase tracking-widest">Menu Utama</p>

{{-- Dashboard --}}
@php $active = request()->routeIs('admin.dashboard'); @endphp
<a href="{{ route('admin.dashboard') }}"
    class="relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium
          transition-all {{ $active ? 'nav-item-active bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-[17px] h-[17px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
            stroke-width="{{ $active ? '2.2' : '1.8' }}"
            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
    </svg>
    Dashboard
</a>

<p class="px-3 pt-4 pb-2 text-[10px] font-semibold text-gray-400 uppercase tracking-widest">Data Master</p>

{{-- Master Lokasi --}}
@php $active = request()->routeIs('admin.lokasi.*'); @endphp
<a href="{{ route('admin.lokasi.index') }}"
    class="relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium
          transition-all {{ $active ? 'nav-item-active bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-[17px] h-[17px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
            stroke-width="{{ $active ? '2.2' : '1.8' }}"
            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
        <path stroke-linecap="round" stroke-linejoin="round"
            stroke-width="{{ $active ? '2.2' : '1.8' }}"
            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
    </svg>
    Master Lokasi
</a>

{{-- GIS & Maps --}}
@php $active = request()->routeIs('admin.map.*'); @endphp
<a href="{{ route('admin.map.index') }}"
    class="relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium
          transition-all {{ $active ? 'nav-item-active bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-[17px] h-[17px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
            stroke-width="{{ $active ? '2.2' : '1.8' }}"
            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
    </svg>
    GIS & Maps
</a>

<p class="px-3 pt-4 pb-2 text-[10px] font-semibold text-gray-400 uppercase tracking-widest">Moderasi</p>

{{-- Approval Kontributor --}}
@php $active = request()->routeIs('admin.approval.*'); @endphp
<a href="{{ route('admin.approval.index') }}"
    class="relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium
          transition-all {{ $active ? 'nav-item-active bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-[17px] h-[17px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
            stroke-width="{{ $active ? '2.2' : '1.8' }}"
            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    Approval Kontributor
</a>

{{-- Pengaduan --}}
@php $active = request()->routeIs('admin.pengaduan.*'); @endphp
<a href="{{ route('admin.pengaduan.index') }}"
    class="relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium
          transition-all {{ $active ? 'nav-item-active bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-[17px] h-[17px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
            stroke-width="{{ $active ? '2.2' : '1.8' }}"
            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
    </svg>
    Pengaduan
</a>

<p class="px-3 pt-4 pb-2 text-[10px] font-semibold text-gray-400 uppercase tracking-widest">Event</p>

{{-- Event Kota --}}
@php $active = request()->routeIs('admin.events.*'); @endphp
<a href="{{ route('admin.events.index') }}"
    class="relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium
          transition-all {{ $active ? 'nav-item-active bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-[17px] h-[17px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
            stroke-width="{{ $active ? '2.2' : '1.8' }}"
            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
    </svg>
    Event Kota
</a>

{{-- Banner Mobile --}}
@php $active = request()->routeIs('admin.banners.*'); @endphp
<a href="{{ route('admin.banners.index') }}"
    class="relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium
          transition-all {{ $active ? 'nav-item-active bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="w-[17px] h-[17px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
            stroke-width="{{ $active ? '2.2' : '1.8' }}"
            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
    </svg>
    Banner Mobile
</a>

{{-- Manajemen User (hanya super_admin & admin) --}}
@if(auth()->check() && in_array(auth()->user()->role, ['super_admin', 'admin']))
    <p class="px-3 pt-4 pb-2 text-[10px] font-semibold text-gray-400 uppercase tracking-widest">Pengaturan</p>

    @php $active = request()->routeIs('admin.users.*'); @endphp
    <a href="{{ route('admin.users.index') }}"
        class="relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium
              transition-all {{ $active ? 'nav-item-active bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="w-[17px] h-[17px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                stroke-width="{{ $active ? '2.2' : '1.8' }}"
                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        Manajemen User
    </a>
@endif
