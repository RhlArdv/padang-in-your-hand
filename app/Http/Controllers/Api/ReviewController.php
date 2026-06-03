<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    // -------------------------------------------------------
    // POST /api/lokasi/{id}/review
    // Buat review baru (user yang sudah login)
    // 1 user = 1 review per lokasi (seperti Google Maps)
    // -------------------------------------------------------
    public function store(Request $request, int $id): JsonResponse
    {
        $lokasi = Lokasi::disetujui()->findOrFail($id);

        $request->validate([
            'rating'   => ['required', 'integer', 'min:1', 'max:5'],
            'komentar' => ['nullable', 'string', 'max:1000'],
        ]);

        // Cek apakah user sudah pernah review lokasi ini
        $existing = Review::where('id_lokasi', $id)
            ->where('id_user', $request->user()->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah pernah memberikan review untuk lokasi ini. Silakan edit review Anda.',
            ], 422);
        }

        $review = Review::create([
            'id_lokasi' => $id,
            'id_user'   => $request->user()->id,
            'rating'    => $request->rating,
            'komentar'  => $request->komentar,
        ]);

        $this->recalculateRating($lokasi);

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil ditambahkan',
            'data'    => $review->load('user:id,name,foto'),
        ], 201);
    }

    // -------------------------------------------------------
    // PUT /api/review/{id}
    // Edit review milik sendiri
    // -------------------------------------------------------
    public function update(Request $request, int $id): JsonResponse
    {
        $review = Review::findOrFail($id);

        // Pastikan review milik user yang login
        if ($review->id_user !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengedit review ini.',
            ], 403);
        }

        $request->validate([
            'rating'   => ['sometimes', 'integer', 'min:1', 'max:5'],
            'komentar' => ['sometimes', 'nullable', 'string', 'max:1000'],
        ]);

        $review->update($request->only('rating', 'komentar'));

        $this->recalculateRating($review->lokasi);

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil diperbarui',
            'data'    => $review->load('user:id,name,foto'),
        ]);
    }

    // -------------------------------------------------------
    // DELETE /api/review/{id}
    // Hapus review milik sendiri
    // -------------------------------------------------------
    public function destroy(Request $request, int $id): JsonResponse
    {
        $review = Review::findOrFail($id);

        // Pastikan review milik user yang login
        if ($review->id_user !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus review ini.',
            ], 403);
        }

        $lokasi = $review->lokasi;
        $review->delete();

        $this->recalculateRating($lokasi);

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil dihapus',
        ]);
    }

    // -------------------------------------------------------
    // Helper: hitung ulang rating_avg & jumlah_review di lokasi
    // -------------------------------------------------------
    private function recalculateRating(Lokasi $lokasi): void
    {
        $lokasi->update([
            'rating_avg'    => $lokasi->reviews()->avg('rating') ?? 0,
            'jumlah_review' => $lokasi->reviews()->count(),
        ]);
    }
}
