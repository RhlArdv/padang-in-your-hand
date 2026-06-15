<section class="space-y-6">
    <header class="border-b border-gray-100 pb-4">
        <h2 class="text-lg font-bold text-gray-900">
            {{ __('Informasi Profil') }}
        </h2>
        <p class="mt-1 text-xs text-gray-500">
            {{ __('Perbarui data profil akun Anda, alamat email, nomor telepon, dan foto profil.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('patch')

        {{-- Profile Picture Uploader --}}
        <div class="bg-gray-50/50 border border-gray-100 rounded-2xl p-5 flex flex-col sm:flex-row items-center gap-5">
            <div
                class="relative w-24 h-24 rounded-2xl overflow-hidden bg-gradient-to-br from-navy-700 to-navy-900 flex items-center justify-center text-white text-3xl font-bold border-2 border-white shadow-md flex-shrink-0 group">
                @if($user->foto)
                    <img id="avatar-preview" src="{{ asset('storage/' . $user->foto) }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                    @php
                        $words = explode(' ', $user->name);
                        $initials = '';
                        if (count($words) >= 2) {
                            $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                        } else {
                            $initials = strtoupper(substr($user->name, 0, 2));
                        }
                    @endphp
                    <span id="avatar-initials"
                        class="group-hover:scale-105 transition-transform duration-300">{{ $initials }}</span>
                    <img id="avatar-preview"
                        class="w-full h-full object-cover hidden group-hover:scale-105 transition-transform duration-300">
                @endif
                <div
                    class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>

            <div class="flex-1 text-center sm:text-left">
                <label for="foto"
                    class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __('Foto Profil') }}</label>
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <input type="file" name="foto" id="foto" accept="image/*" class="hidden"
                        onchange="previewImage(event)">
                    <button type="button" onclick="document.getElementById('foto').click()"
                        class="px-4 py-2 bg-white border border-gray-200 text-xs font-semibold text-gray-700 rounded-xl hover:bg-gray-50 active:scale-[0.98] transition-all shadow-sm">
                        {{ __('Pilih Foto Baru') }}
                    </button>
                    <p class="text-[10px] text-gray-400">JPEG, PNG, JPG (Maks. 2MB)</p>
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('foto')" />
            </div>
        </div>

        {{-- Form Fields --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="name"
                    class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __('Nama Lengkap') }}</label>
                <input id="name" name="name" type="text"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10 transition-all placeholder-gray-400"
                    value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                <x-input-error class="mt-1.5" :messages="$errors->get('name')" />
            </div>

            <div>
                <label for="no_hp"
                    class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __('Nomor HP') }}</label>
                <input id="no_hp" name="no_hp" type="text"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10 transition-all placeholder-gray-400"
                    value="{{ old('no_hp', $user->no_hp) }}" placeholder="Contoh: 08123456789" />
                <x-input-error class="mt-1.5" :messages="$errors->get('no_hp')" />
            </div>
        </div>

        <div>
            <label for="email"
                class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __('Alamat Email') }}</label>
            <input id="email" name="email" type="email"
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10 transition-all placeholder-gray-400"
                value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-1.5" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                    <p class="text-xs text-amber-800 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        {{ __('Alamat email Anda belum terverifikasi.') }}
                        <button form="send-verification"
                            class="underline font-semibold text-amber-900 hover:text-amber-950">
                            {{ __('Kirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1.5 text-[10px] font-semibold text-emerald-600">
                            {{ __('Link verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 border-t border-gray-100 pt-4 mt-6">
            <button type="submit"
                class="px-5 py-2.5 bg-navy-800 text-white text-sm font-semibold rounded-xl hover:bg-navy-700 hover:shadow-lg hover:shadow-navy-800/10 active:scale-[0.98] transition-all">
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="flex items-center gap-1.5 text-emerald-600 text-xs font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('Profil berhasil disimpan!') }}
                </div>
            @endif
        </div>
    </form>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const preview = document.getElementById('avatar-preview');
                const initials = document.getElementById('avatar-initials');
                preview.src = reader.result;
                preview.classList.remove('hidden');
                if (initials) {
                    initials.classList.add('hidden');
                }
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</section>