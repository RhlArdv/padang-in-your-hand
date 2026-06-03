<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class KategoriController extends Controller
{
    // -------------------------------------------------------
    // GET /api/kategori
    // List semua kategori
    // -------------------------------------------------------
    public function index(): JsonResponse
    {
        $kategori = Kategori::withCount(['lokasis as jumlah_lokasi' => function ($q) {
            $q->where('status_verifikasi', 'disetujui');
        }])->get();

        return response()->json([
            'success' => true,
            'data'    => $kategori,
        ]);
    }

    // -------------------------------------------------------
    // GET /api/kategori/{id}/lokasi
    // List lokasi yang sudah disetujui per kategori
    // -------------------------------------------------------
    public function lokasi(Request $request, int $id): JsonResponse
    {
        $kategori = Kategori::findOrFail($id);

        $lokasi = Lokasi::disetujui()
            ->where('id_kategori', $id)
            ->with(['fotoUtama', 'kecamatan', 'kelurahan'])
            ->orderByDesc('rating_avg')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => [
                'kategori' => $kategori,
                'lokasi'   => $lokasi,
            ],
        ]);
    }

    // -------------------------------------------------------
    // POST /api/admin/kategori
    // Buat kategori baru (admin, super_admin)
    // -------------------------------------------------------
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255', 'unique:kategori,nama_kategori'],
            'icon'          => ['nullable', 'string', 'max:100'],
        ]);

        $kategori = Kategori::create($request->only('nama_kategori', 'icon'));

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dibuat',
            'data'    => $kategori,
        ], 201);
    }

    // -------------------------------------------------------
    // PUT /api/admin/kategori/{id}
    // Update kategori (admin, super_admin)
    // -------------------------------------------------------
    public function update(Request $request, int $id): JsonResponse
    {
        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'nama_kategori' => ['sometimes', 'string', 'max:255', 'unique:kategori,nama_kategori,' . $id . ',id_kategori'],
            'icon'          => ['sometimes', 'nullable', 'string', 'max:100'],
        ]);

        $kategori->update($request->only('nama_kategori', 'icon'));

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui',
            'data'    => $kategori,
        ]);
    }

    // -------------------------------------------------------
    // DELETE /api/admin/kategori/{id}
    // Hapus kategori — tolak jika masih ada lokasi
    // -------------------------------------------------------
    public function destroy(int $id): JsonResponse
    {
        $kategori = Kategori::findOrFail($id);

        // Cek apakah masih ada lokasi di kategori ini
        if ($kategori->lokasis()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak bisa dihapus karena masih memiliki lokasi.',
            ], 422);
        }

        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus',
        ]);
    }
}
