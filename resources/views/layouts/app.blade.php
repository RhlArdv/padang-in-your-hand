<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Admin Panel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

    @stack('styles')

    <style>
        * {
            font-family: 'DM Sans', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 99px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }

        .nav-item-active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 6px;
            bottom: 6px;
            width: 3px;
            background: #1a4576;
            border-radius: 0 3px 3px 0;
        }

        main {
            animation: fadeUp 0.25s ease both;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #sidebar {
            transition: transform 0.25s cubic-bezier(.4, 0, .2, 1);
        }

        .brand-font {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-[#F3F4F6] text-gray-800 antialiased" x-data="{ sidebarOpen: false }">

    {{-- Mobile overlay --}}
    <div x-show="sidebarOpen" style="display: none;" x-transition.opacity @click="sidebarOpen = false"
        class="fixed inset-0 z-20 bg-black/30 backdrop-blur-sm lg:hidden"></div>

    <div class="flex min-h-screen">

        {{-- ================================================
        SIDEBAR
        ================================================ --}}
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-[260px] bg-white border-r border-gray-100
                      flex flex-col -translate-x-full lg:translate-x-0"
            :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">

            {{-- Brand --}}
            <div class="flex items-center justify-between px-5 h-[60px] border-b border-gray-100 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 flex items-center justify-center flex-shrink-0 overflow-hidden rounded-lg">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="h-full w-full object-cover">
                    </div>
                    <span class="brand-font font-bold text-gray-900 text-[13px] tracking-tight leading-tight">Padang Dalam<br>Genggaman</span>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden p-1 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
                @yield('sidebar-menu')
            </nav>

            {{-- User card bottom --}}
            <div class="flex-shrink-0 border-t border-gray-100 p-3">
                <div class="flex items-center gap-3 px-2 py-2 rounded-xl hover:bg-gray-50 transition-colors">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 flex-1 min-w-0">
                        @if(auth()->user()->foto)
                            <img src="{{ asset('storage/' . auth()->user()->foto) }}" class="w-8 h-8 rounded-lg object-cover flex-shrink-0">
                        @else
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-navy-700 to-navy-900
                                        flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                @php
                                    $words = explode(' ', auth()->user()->name);
                                    $initials = '';
                                    if (count($words) >= 2) {
                                        $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                                    } else {
                                        $initials = strtoupper(substr(auth()->user()->name, 0, 2));
                                    }
                                @endphp
                                {{ $initials }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-[13px] font-semibold text-gray-800 truncate leading-tight">
                                {{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-gray-400 truncate capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                        </div>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Logout" class="p-1.5 text-gray-300 hover:text-red-500 hover:bg-red-50
                                       rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

        </aside>

        {{-- ================================================
        MAIN CONTENT
        ================================================ --}}
        <div class="flex flex-col flex-1 min-w-0 lg:pl-[260px]">

            {{-- NAVBAR --}}
            <header class="sticky top-0 z-10 h-[60px] bg-white/90 backdrop-blur-md
                           border-b border-gray-100 flex items-center px-5 gap-4">

                {{-- Hamburger mobile --}}
                <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="flex-1"></div>

                {{-- Avatar + nama --}}
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 hover:opacity-90 transition-opacity">
                    <div class="text-right hidden md:block">
                        <p class="text-[13px] font-semibold text-gray-800 leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-gray-400 leading-none mt-0.5 capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                    </div>
                    @if(auth()->user()->foto)
                        <img src="{{ asset('storage/' . auth()->user()->foto) }}" class="w-8 h-8 rounded-lg object-cover cursor-pointer shadow-sm shadow-navy-200">
                    @else
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-navy-700 to-navy-900
                                    flex items-center justify-center text-white text-xs font-bold cursor-pointer
                                    shadow-sm shadow-navy-200">
                            @php
                                $words = explode(' ', auth()->user()->name);
                                $initials = '';
                                if (count($words) >= 2) {
                                    $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                                } else {
                                    $initials = strtoupper(substr(auth()->user()->name, 0, 2));
                                }
                            @endphp
                            {{ $initials }}
                        </div>
                    @endif
                </a>

            </header>

            {{-- KONTEN --}}
            <main class="flex-1 p-5 lg:p-6">
                @hasSection('page-header')
                    <div class="mb-5">@yield('page-header')</div>
                @endif

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl">
                        <p class="text-sm text-green-800">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="px-6 py-3 text-center text-xs text-gray-400 border-t border-gray-100 bg-white">
                © {{ date('Y') }} Padang Dalam Genggaman — DISKOMINFO Kota Padang
            </footer>

        </div>
    </div>

    @stack('scripts')
</body>

</html>
