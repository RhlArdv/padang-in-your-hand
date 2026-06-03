@extends('layouts.app')

@section('title', 'Edit Banner')

@section('sidebar-menu')
    @include('partials.sidebar-menu')
@endsection

@section('page-header')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.banners.index') }}" class="p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Banner</h1>
            <p class="text-sm text-gray-500 mt-1">Perbarui detail banner promosi/informasi aplikasi mobile</p>
        </div>
    </div>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.banners.update', $banner->id_banner) }}" enctype="multipart/form-data" class="max-w-2xl">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-2xl border border-gray-100 p-6 space-y-5 shadow-sm">
            {{-- Judul Banner --}}
            <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Banner <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title', $banner->title) }}" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all"
                    placeholder="Contoh: Festival Kuliner Minangkabau 2026">
                @error('title') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Tautan Link & Urutan --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="md:col-span-2">
                    <label for="link" class="block text-sm font-semibold text-gray-700 mb-1.5">Tautan / Link <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <input type="text" name="link" id="link" value="{{ old('link', $banner->link) }}"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all"
                        placeholder="Contoh: https://padang.go.id atau event/12">
                    @error('link') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="order" class="block text-sm font-semibold text-gray-700 mb-1.5">Urutan Tampil <span class="text-red-500">*</span></label>
                    <input type="number" name="order" id="order" value="{{ old('order', $banner->order) }}" required min="0"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all">
                    @error('order') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Upload Image --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Gambar Banner <span class="text-red-500">*</span></label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-2xl hover:border-indigo-500 transition-colors group relative cursor-pointer" id="drop-zone">
                    {{-- Default prompt - hidden if there's an image --}}
                    <div class="space-y-1 text-center {{ $banner->image ? 'hidden' : '' }}" id="upload-prompt">
                        <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-indigo-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <span class="relative bg-white rounded-md font-semibold text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                Unggah berkas gambar baru
                            </span>
                            <p class="pl-1">atau seret dan lepas</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, JPEG sampai dengan 2MB</p>
                    </div>
                    
                    {{-- Image Preview --}}
                    <div id="image-preview-container" class="{{ $banner->image ? '' : 'hidden' }} w-full flex flex-col items-center">
                        <img id="image-preview" src="{{ $banner->image_url }}" alt="Preview" class="max-h-48 object-cover rounded-xl border border-gray-100 shadow-sm">
                        <button type="button" id="remove-preview" class="mt-3 px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl text-xs font-semibold transition-colors">
                            Ganti Gambar / Hapus
                        </button>
                    </div>

                    <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                </div>
                <p class="text-xs text-gray-400 mt-1 ml-1">Biarkan kosong jika tidak ingin mengubah gambar banner.</p>
                @error('image') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Toggle Status Aktif --}}
            <div class="pt-2">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" id="is_active" class="sr-only peer" {{ $banner->is_active ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    <span class="ml-3 text-sm font-semibold text-gray-700">Tampilkan Banner (Aktif)</span>
                </label>
                <p class="text-xs text-gray-400 mt-1 ml-14">Aktifkan untuk langsung menampilkan banner ini pada aplikasi mobile.</p>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-3 mt-5">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-500 hover:shadow-md transition-all transform hover:-translate-y-0.5 shadow-sm">
                Perbarui Banner
            </button>
            <a href="{{ route('admin.banners.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                Batal
            </a>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById('drop-zone');
            const fileInput = document.getElementById('image');
            const uploadPrompt = document.getElementById('upload-prompt');
            const previewContainer = document.getElementById('image-preview-container');
            const previewImg = document.getElementById('image-preview');
            const removePreviewBtn = document.getElementById('remove-preview');

            // Open file selection when clicking the drop zone
            dropZone.addEventListener('click', function(e) {
                if (e.target !== removePreviewBtn && !removePreviewBtn.contains(e.target)) {
                    fileInput.click();
                }
            });

            // Prevent defaults for drag events
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            // Highlight drop zone on drag over
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                dropZone.classList.add('border-indigo-500', 'bg-indigo-50/30');
            }

            function unhighlight() {
                dropZone.classList.remove('border-indigo-500', 'bg-indigo-50/30');
            }

            // Handle dropped files
            dropZone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files.length > 0) {
                    fileInput.files = files;
                    showPreview(files[0]);
                }
            }

            // Handle selected files
            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    showPreview(this.files[0]);
                }
            });

            function showPreview(file) {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        uploadPrompt.classList.add('hidden');
                        previewContainer.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            }

            // Remove/Reset preview
            removePreviewBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                fileInput.value = '';
                previewImg.src = '#';
                previewContainer.classList.add('hidden');
                uploadPrompt.classList.remove('hidden');
            });
        });
    </script>
@endsection
