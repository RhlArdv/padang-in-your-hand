<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Padang In Your Hand</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    <style>
        .bg-grid {
            background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 0h40v40H0z' fill='none'/%3E%3Cpath d='M0 0h40v40H0z' fill='none' stroke='%23e2e8f0' stroke-width='1'/%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="antialiased font-sans selection:bg-gold-500 selection:text-navy-900 bg-[#F8FAFC]">

    <div class="min-h-screen flex flex-col lg:flex-row w-full overflow-hidden">
        
        <div class="hidden lg:flex lg:w-1/2 bg-navy-900 relative items-center justify-center overflow-hidden">
            <div class="absolute inset-0 bg-grid opacity-20 z-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M0 0h40v40H0z\' fill=\'none\'/%3E%3Cpath d=\'M0 0h40v40H0z\' fill=\'none\' stroke=\'rgba(255,255,255,0.1)\' stroke-width=\'1\'/%3E%3C/svg%3E')"></div>
            
            <div class="absolute top-0 left-0 w-[600px] h-[600px] bg-gold-500/20 rounded-full mix-blend-screen filter blur-[120px] opacity-60 -translate-x-1/4 -translate-y-1/4 z-0 animate-pulse"></div>
            <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-sky-500/20 rounded-full mix-blend-screen filter blur-[100px] opacity-50 translate-x-1/4 translate-y-1/4 z-0"></div>

            <div class="relative z-10 max-w-xl text-center flex flex-col items-center px-12">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-white/20 bg-white/10 backdrop-blur-md mb-8 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-gold-500 animate-ping"></span>
                    <span class="text-xs font-black tracking-widest text-gold-400 uppercase">Admin Panel</span>
                </div>
                
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="h-24 w-auto object-contain mb-6 drop-shadow-xl">
                
                <h1 class="text-5xl xl:text-6xl font-black text-white tracking-tighter uppercase leading-[1.1] mb-6">
                    PADANG IN<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-gold-400 to-gold-600 drop-shadow-sm">YOUR HAND</span>
                </h1>
                
                <p class="text-navy-100 text-lg font-medium leading-relaxed max-w-md">
                    Platform direktori lokasi berbasis peta Kota Padang. Temukan, nilai, dan navigasi ke berbagai tempat penting dengan mudah.
                </p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 md:p-20 relative z-10">
            <div class="absolute top-0 right-0 w-[300px] h-[300px] bg-gold-500/10 rounded-full blur-[80px] lg:hidden z-0 pointer-events-none"></div>

            <div class="w-full max-w-md relative z-10">
                
                <div class="lg:hidden flex items-center justify-center gap-3 mb-10">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="h-12 w-auto object-contain">
                    <div class="text-left">
                        <h2 class="text-xl font-black text-navy-900 tracking-tight leading-none">PADANG IN YOUR HAND</h2>
                        <p class="text-xs font-bold text-gold-600 uppercase tracking-widest mt-1">Kota Padang</p>
                    </div>
                </div>

                <div class="text-center lg:text-left mb-10">
                    <h2 class="text-3xl md:text-4xl font-black tracking-tight text-navy-900 mb-3">Selamat Datang!</h2>
                    <p class="text-navy-500 font-medium text-base">Silakan masuk menggunakan kredensial akun Anda untuk mengakses sistem.</p>
                </div>

                <x-auth-session-status class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 font-medium text-sm flex items-start gap-3" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-bold text-navy-900 uppercase tracking-widest mb-2">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-navy-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                class="w-full pl-12 pr-5 py-4 bg-white border border-navy-100 rounded-xl shadow-sm focus:border-gold-500 focus:ring-4 focus:ring-gold-500/10 transition-all font-medium text-navy-900 placeholder:text-navy-300"
                                placeholder="admin@padang.go.id">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs font-bold" />
                    </div>

                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-navy-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                class="w-full pl-12 pr-5 py-4 bg-white border border-navy-100 rounded-xl shadow-sm focus:border-gold-500 focus:ring-4 focus:ring-gold-500/10 transition-all font-medium text-navy-900 placeholder:text-navy-300"
                                placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs font-bold" />
                    </div>

                    <div class="flex items-center mt-2">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                            <div class="relative flex items-center justify-center w-5 h-5 rounded border border-navy-200 bg-white group-hover:border-gold-500 transition-colors">
                                <input id="remember_me" type="checkbox" class="peer absolute w-full h-full opacity-0 cursor-pointer" name="remember">
                                <div class="absolute inset-0 rounded bg-gold-500 opacity-0 peer-checked:opacity-100 transition-opacity flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>
                            <span class="ms-3 text-sm font-medium text-navy-600 group-hover:text-navy-900 transition-colors">Ingat Saya</span>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full py-4 px-8 bg-navy-900 text-white font-bold rounded-xl hover:bg-navy-800 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-1 flex items-center justify-center gap-3 mt-8 group">
                        <span>Masuk Sistem</span>
                        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>

                    <div class="text-center mt-10">
                        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm font-bold text-navy-400 hover:text-gold-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Halaman Utama
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>

</body>
</html>
