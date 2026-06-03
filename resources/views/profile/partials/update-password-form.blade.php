<section class="space-y-6">
    <header class="border-b border-gray-100 pb-4 mb-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900">
                {{ __('Ubah Password') }}
            </h2>
            <button type="button" x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <p class="mt-1.5 text-xs text-gray-500">
            {{ __('Pastikan akun Anda menggunakan password yang panjang dan acak untuk menjaga keamanan.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __('Password Saat Ini') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" 
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10 transition-all placeholder-gray-400"
                placeholder="••••••••" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5" />
        </div>

        <div>
            <label for="update_password_password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __('Password Baru') }}</label>
            <input id="update_password_password" name="password" type="password" 
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10 transition-all placeholder-gray-400"
                placeholder="Minimal 8 karakter" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __('Konfirmasi Password Baru') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-500/10 transition-all placeholder-gray-400"
                placeholder="Ketik ulang password baru" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5" />
        </div>

        <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-4 mt-6">
            <button type="button" x-on:click="$dispatch('close')" 
                class="px-5 py-2.5 bg-gray-100 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                {{ __('Batal') }}
            </button>
            <button type="submit" 
                class="px-5 py-2.5 bg-navy-800 text-white text-sm font-semibold rounded-xl hover:bg-navy-700 hover:shadow-lg hover:shadow-navy-800/10 active:scale-[0.98] transition-all">
                {{ __('Simpan Password') }}
            </button>
        </div>
    </form>
</section>
