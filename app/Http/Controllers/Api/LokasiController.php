<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LokasiController extends Controller
{
    // -------------------------------------------------------
    // GET /api/lokasi
    // Query params: kategori_id, kecamatan_id, kelurahan_id,
    //               search, sort_by, per_page
    // -------------------------------------------------------
    public function index(Request $request): JsonResponse
    {
        $query = Lokasi::disetujui()
            ->with(['kategori', 'fotoUtama', 'kecamatan', 'kelurahan']);

        // Filter kategori
        if ($request->filled('kategori_id')) {
            $query->where('id_kategori', $request->kategori_id);
        }

        // Filter kecamatan
        if ($request->filled('kecamatan_id')) {
            $query->where('id_kecamatan', $request->kecamatan_id);
        }

        // Filter kelurahan
        if ($request->filled('kelurahan_id')) {
            $query->where('id_kelurahan', $request->kelurahan_id);
        }

        // Search nama tempat
        if ($request->filled('search')) {
            $query->where('nama_tempat', 'like', '%' . $request->search . '%');
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $allowedSort = ['nama_tempat', 'rating_avg', 'jumlah_kunjungan', 'created_at'];
        if (in_array($sortBy, $allowedSort)) {
            $query->orderByDesc($sortBy);
        }

        $lokasi = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $lokasi,
        ]);
    }

    // -------------------------------------------------------
    // GET /api/lokasi/{id}
    // Detail lengkap satu lokasi
    // -------------------------------------------------------
    public function show(int $id): JsonResponse
    {
        $lokasi = Lokasi::disetujui()
            ->with([
                'kategori',
                'kecamatan',
                'kelurahan',
                'fotos',
                'reviews.user',
                'kontributor:id,name,foto',
            ])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $lokasi,
        ]);
    }

    // -------------------------------------------------------
    // GET /api/lokasi/nearby?lat=...&lng=...&radius=...
    // Cari lokasi terdekat dari posisi user
    // radius dalam meter, default 1000m (1km)
    // -------------------------------------------------------
    public function nearby(Request $request): JsonResponse
    {
        $request->validate([
            'lat'    => ['required', 'numeric'],
            'lng'    => ['required', 'numeric'],
            'radius' => ['nullable', 'numeric', 'min:100', 'max:50000'],
        ]);

        $lat    = $request->lat;
        $lng    = $request->lng;
        $radius = $request->get('radius', 1000); // default 1km

        // Hitung jarak pakai Haversine langsung di MySQL
        $lokasi = Lokasi::disetujui()
            ->with(['kategori', 'fotoUtama'])
            ->selectRaw("
                *,
                (6371000 * ACOS(
                    COS(RADIANS(?)) * COS(RADIANS(latitude)) *
                    COS(RADIANS(longitude) - RADIANS(?)) +
                    SIN(RADIANS(?)) * SIN(RADIANS(latitude))
                )) AS jarak
            ", [$lat, $lng, $lat])
            ->having('jarak', '<=', $radius)
            ->orderBy('jarak')
            ->when($request->filled('kategori_id'), function ($q) use ($request) {
                $q->where('id_kategori', $request->kategori_id);
            })
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $lokasi,
            'meta'    => [
                'lat'    => $lat,
                'lng'    => $lng,
                'radius' => $radius,
                'total'  => $lokasi->count(),
            ],
        ]);
    }

    // -------------------------------------------------------
    // GET /api/lokasi/search?q=...
    // -------------------------------------------------------
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => ['required', 'string', 'min:2'],
        ]);

        $lokasi = Lokasi::disetujui()
            ->with(['kategori', 'fotoUtama', 'kecamatan'])
            ->where(function ($query) use ($request) {
                $query->where('nama_tempat', 'like', '%' . $request->q . '%')
                      ->orWhere('alamat',    'like', '%' . $request->q . '%')
                      ->orWhere('deskripsi', 'like', '%' . $request->q . '%');
            })
            ->orderByDesc('rating_avg')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $lokasi,
            'meta'    => ['keyword' => $request->q, 'total' => $lokasi->count()],
        ]);
    }
}