<section class="space-y-6">
    <header class="border-b border-red-50 pb-4 mb-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-red-700 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                {{ __('Hapus Akun Permanen') }}
            </h2>
            <button type="button" x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <p class="mt-1.5 text-xs text-red-500/80">
            {{ __('Setelah akun Anda dihapus, semua data dan aset di dalamnya akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
        @csrf
        @method('delete')

        <div>
            <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __('Masukkan Password Anda untuk Konfirmasi') }}</label>
            <input id="password" name="password" type="password" 
                class="w-full px-4 py-2.5 border border-red-200 rounded-xl text-sm focus:border-red-500 focus:ring-2 focus:ring-red-500/10 transition-all placeholder-gray-400"
                placeholder="Masukkan password saat ini untuk menghapus" />
            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5" />
        </div>

        <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-4 mt-6">
            <button type="button" x-on:click="$dispatch('close')" 
                class="px-5 py-2.5 bg-gray-100 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                {{ __('Batal') }}
            </button>
            <button type="submit" 
                class="px-5 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 hover:shadow-lg hover:shadow-red-600/10 active:scale-[0.98] transition-all">
                {{ __('Hapus Akun Saya') }}
            </button>
        </div>
    </form>
</section>
