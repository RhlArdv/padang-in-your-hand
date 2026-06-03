<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorit;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FavoritController extends Controller
{
    // -------------------------------------------------------
    // GET /api/favorit
    // List semua lokasi yang difavoritkan user yang login
    // -------------------------------------------------------
    public function index(Request $request): JsonResponse
    {
        $favorits = Favorit::where('id_user', $request->user()->id)
            ->with(['lokasi' => function ($q) {
                $q->with(['kategori', 'fotoUtama', 'kecamatan']);
            }])
            ->latest()
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $favorits,
        ]);
    }

    // -------------------------------------------------------
    // POST /api/favorit/{idLokasi}
    // Toggle favorit: jika sudah ada → hapus, belum → tambah
    // -------------------------------------------------------
    public function toggle(Request $request, int $idLokasi): JsonResponse
    {
        // Pastikan lokasi ada dan sudah disetujui
        Lokasi::disetujui()->findOrFail($idLokasi);

        $userId = $request->user()->id;

        $existing = Favorit::where('id_user', $userId)
            ->where('id_lokasi', $idLokasi)
            ->first();

        if ($existing) {
            $existing->delete();

            return response()->json([
                'success'    => true,
                'message'    => 'Lokasi dihapus dari favorit',
                'is_favorit' => false,
            ]);
        }

        Favorit::create([
            'id_user'   => $userId,
            'id_lokasi' => $idLokasi,
        ]);

        return response()->json([
            'success'    => true,
            'message'    => 'Lokasi ditambahkan ke favorit',
            'is_favorit' => true,
        ], 201);
    }

    // -------------------------------------------------------
    // GET /api/favorit/check/{idLokasi}
    // Cek apakah lokasi sudah difavoritkan oleh user yang login
    // -------------------------------------------------------
    public function check(Request $request, int $idLokasi): JsonResponse
    {
        $isFavorit = Favorit::where('id_user', $request->user()->id)
            ->where('id_lokasi', $idLokasi)
            ->exists();

        return response()->json([
            'success'    => true,
            'is_favorit' => $isFavorit,
        ]);
    }
}
