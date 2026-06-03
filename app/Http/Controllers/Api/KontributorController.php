<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FotoLokasi;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class KontributorController extends Controller
{
    // -------------------------------------------------------
    // GET /api/kontributor/lokasi
    // List lokasi milik kontributor yang login (semua status)
    // -------------------------------------------------------
    public function index(Request $request): JsonResponse
    {
        $lokasi = Lokasi::where('created_by', $request->user()->id)
            ->with(['kategori', 'fotoUtama', 'kecamatan', 'kelurahan'])
            ->latest()
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $lokasi,
        ]);
    }

    // -------------------------------------------------------
    // POST /api/kontributor/lokasi
    // Submit lokasi baru + upload foto sekaligus (status: pending)
    // -------------------------------------------------------
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nama_tempat'      => ['required', 'string', 'max:255'],
            'id_kategori'      => ['required', 'exists:kategori,id_kategori'],
            'alamat'           => ['required', 'string'],
            'id_kecamatan'     => ['required', 'exists:kecamatan,id_kecamatan'],
            'id_kelurahan'     => ['required', 'exists:kelurahan,id_kelurahan'],
            'latitude'         => ['required', 'numeric', 'between:-90,90'],
            'longitude'        => ['required', 'numeric', 'between:-180,180'],
            'deskripsi'        => ['nullable', 'string'],
            'jam_operasional'  => ['nullable', 'string', 'max:255'],
            'kontak'           => ['nullable', 'string', 'max:50'],
            'website'          => ['nullable', 'string', 'max:255', 'url'],
            'foto'             => ['nullable', 'array', 'max:5'],       // max 5 foto
            'foto.*'           => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // max 2MB per foto
        ]);

        // Simpan lokasi
        $lokasi = Lokasi::create([
            ...$request->only([
                'nama_tempat', 'id_kategori', 'alamat',
                'id_kecamatan', 'id_kelurahan', 'latitude', 'longitude',
                'deskripsi', 'jam_operasional', 'kontak', 'website',
            ]),
            'created_by'        => $request->user()->id,
            'status_verifikasi' => 'pending',
        ]);

        // Upload foto jika ada
        $fotosUploaded = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('lokasi-foto', 'public');

                $foto = FotoLokasi::create([
                    'id_lokasi' => $lokasi->id_lokasi,
                    'file_foto' => $path,
                    'caption'   => null,
                ]);

                $fotosUploaded[] = $foto;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Lokasi berhasil disubmit dan menunggu verifikasi',
            'data'    => $lokasi->load(['kategori', 'kecamatan', 'kelurahan', 'fotos']),
        ], 201);
    }

    // -------------------------------------------------------
    // PUT /api/kontributor/lokasi/{id}
    // Update lokasi milik sendiri (hanya jika pending/revisi)
    // -------------------------------------------------------
    public function update(Request $request, int $id): JsonResponse
    {
        $lokasi = Lokasi::where('created_by', $request->user()->id)
            ->findOrFail($id);

        // Hanya bisa edit jika status pending atau revisi
        if (! in_array($lokasi->status_verifikasi, ['pending', 'revisi'])) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi dengan status "' . $lokasi->status_verifikasi . '" tidak dapat diedit.',
            ], 422);
        }

        $request->validate([
            'nama_tempat'      => ['sometimes', 'string', 'max:255'],
            'id_kategori'      => ['sometimes', 'exists:kategori,id_kategori'],
            'alamat'           => ['sometimes', 'string'],
            'id_kecamatan'     => ['sometimes', 'exists:kecamatan,id_kecamatan'],
            'id_kelurahan'     => ['sometimes', 'exists:kelurahan,id_kelurahan'],
            'latitude'         => ['sometimes', 'numeric', 'between:-90,90'],
            'longitude'        => ['sometimes', 'numeric', 'between:-180,180'],
            'deskripsi'        => ['sometimes', 'nullable', 'string'],
            'jam_operasional'  => ['sometimes', 'nullable', 'string', 'max:255'],
            'kontak'           => ['sometimes', 'nullable', 'string', 'max:50'],
            'website'          => ['sometimes', 'nullable', 'string', 'max:255', 'url'],
            'foto'             => ['sometimes', 'nullable', 'array', 'max:5'],
            'foto.*'           => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $lokasi->update($request->only([
            'nama_tempat', 'id_kategori', 'alamat',
            'id_kecamatan', 'id_kelurahan', 'latitude', 'longitude',
            'deskripsi', 'jam_operasional', 'kontak', 'website',
        ]));

        // Upload foto tambahan jika ada
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('lokasi-foto', 'public');

                FotoLokasi::create([
                    'id_lokasi' => $lokasi->id_lokasi,
                    'file_foto' => $path,
                    'caption'   => null,
                ]);
            }
        }

        // Jika sebelumnya revisi, kembalikan ke pending setelah diedit
        if ($lokasi->status_verifikasi === 'revisi') {
            $lokasi->update(['status_verifikasi' => 'pending']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lokasi berhasil diperbarui',
            'data'    => $lokasi->load(['kategori', 'kecamatan', 'kelurahan', 'fotos']),
        ]);
    }

    // -------------------------------------------------------
    // DELETE /api/kontributor/lokasi/{id}
    // Hapus lokasi milik sendiri (hanya jika pending/ditolak)
    // -------------------------------------------------------
    public function destroy(Request $request, int $id): JsonResponse
    {
        $lokasi = Lokasi::where('created_by', $request->user()->id)
            ->findOrFail($id);

        if (! in_array($lokasi->status_verifikasi, ['pending', 'ditolak'])) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi yang sudah disetujui atau sedang revisi tidak dapat dihapus.',
            ], 422);
        }

        // Hapus semua foto dari storage
        foreach ($lokasi->fotos as $foto) {
            Storage::disk('public')->delete($foto->file_foto);
        }

        $lokasi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lokasi berhasil dihapus',
        ]);
    }

    // -------------------------------------------------------
    // POST /api/kontributor/lokasi/{id}/foto
    // Upload foto tambahan untuk lokasi yang sudah ada
    // -------------------------------------------------------
    public function uploadFoto(Request $request, int $id): JsonResponse
    {
        $lokasi = Lokasi::where('created_by', $request->user()->id)
            ->findOrFail($id);

        $request->validate([
            'foto'    => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $path = $request->file('foto')->store('lokasi-foto', 'public');

        $foto = FotoLokasi::create([
            'id_lokasi' => $lokasi->id_lokasi,
            'file_foto' => $path,
            'caption'   => $request->caption,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil diupload',
            'data'    => [
                'id_foto'   => $foto->id_foto,
                'file_foto' => $foto->file_foto,
                'url'       => asset('storage/' . $foto->file_foto),
                'caption'   => $foto->caption,
            ],
        ], 201);
    }

    // -------------------------------------------------------
    // DELETE /api/kontributor/lokasi/{id}/foto/{idFoto}
    // Hapus foto tertentu dari lokasi milik sendiri
    // -------------------------------------------------------
    public function hapusFoto(Request $request, int $id, int $idFoto): JsonResponse
    {
        $lokasi = Lokasi::where('created_by', $request->user()->id)
            ->findOrFail($id);

        $foto = FotoLokasi::where('id_lokasi', $lokasi->id_lokasi)
            ->where('id_foto', $idFoto)
            ->firstOrFail();

        Storage::disk('public')->delete($foto->file_foto);
        $foto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil dihapus',
        ]);
    }
}