<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MapController extends Controller
{
    /**
     * Halaman utama GIS & Maps.
     *
     * Hanya memuat data minimal (tanpa foto) untuk rendering marker & sidebar.
     * Foto dimuat secara lazy melalui endpoint popupData().
     */
    public function index()
    {
        // Cache data lokasi peta selama 1 jam (3600 detik) sebagai plain array
        // agar tidak terkena error PHP __PHP_Incomplete_Class saat deserialisasi.
        // Sengaja TIDAK eager-load fotoUtama untuk mengurangi ukuran payload awal.
        $lokasi = Cache::remember('map_lokasi_disetujui', 3600, function () {
            return Lokasi::disetujui()
                ->with(['kategori:id_kategori,nama_kategori,icon'])
                ->get(['id_lokasi', 'nama_tempat', 'id_kategori', 'alamat', 'latitude', 'longitude', 'rating_avg'])
                ->toArray();
        });

        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('admin.map.index', compact('lokasi', 'kategoris'));
    }

    /**
     * Endpoint AJAX untuk lazy-load data popup satu lokasi (termasuk foto).
     *
     * Dipanggil saat user mengklik marker di peta, sehingga foto
     * hanya dimuat untuk lokasi yang benar-benar dilihat user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function popupData(int $id)
    {
        $correlationId = uniqid('popup_', true);
        $startTime = microtime(true);

        Log::info('map.popupData started', [
            'correlationId' => $correlationId,
            'operation'     => 'map_popup_data',
            'lokasi_id'     => $id,
        ]);

        $lokasi = Lokasi::with(['kategori:id_kategori,nama_kategori', 'fotoUtama'])
            ->find($id, ['id_lokasi', 'nama_tempat', 'id_kategori', 'alamat', 'latitude', 'longitude', 'rating_avg']);

        if (! $lokasi) {
            Log::warning('map.popupData lokasi not found', [
                'correlationId' => $correlationId,
                'operation'     => 'map_popup_data',
                'lokasi_id'     => $id,
                'duration_ms'   => round((microtime(true) - $startTime) * 1000, 2),
            ]);

            return response()->json(['error' => 'Lokasi tidak ditemukan'], 404);
        }

        $durationMs = round((microtime(true) - $startTime) * 1000, 2);
        Log::info('map.popupData completed', [
            'correlationId' => $correlationId,
            'operation'     => 'map_popup_data',
            'lokasi_id'     => $id,
            'duration_ms'   => $durationMs,
        ]);

        return response()->json($lokasi);
    }
}
