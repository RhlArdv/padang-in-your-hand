<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use App\Models\RiwayatKunjungan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RiwayatKunjunganController extends Controller
{
    // Radius dalam meter — user dianggap "tiba" jika dalam radius ini
    private const RADIUS_TIBA = 100;

    // -------------------------------------------------------
    // GET /api/riwayat
    // List riwayat kunjungan user yang login
    // -------------------------------------------------------
    public function index(Request $request): JsonResponse
    {
        $riwayat = RiwayatKunjungan::where('id_user', $request->user()->id)
            ->with([
                'lokasi' => function ($q) {
                    $q->with(['kategori', 'fotoUtama']);
                }
            ])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest('dikunjungi_pada')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $riwayat,
        ]);
    }

    // -------------------------------------------------------
    // POST /api/riwayat/mulai/{idLokasi}
    // Dipanggil saat user tekan "Rute ke Lokasi"
    // Mulai sesi navigasi
    // -------------------------------------------------------
    public function mulaiNavigasi(Request $request, int $idLokasi): JsonResponse
    {
        $lokasi = Lokasi::disetujui()->findOrFail($idLokasi);
        $user = $request->user();

        // Cek apakah ada sesi navigasi yang sedang berjalan ke lokasi ini
        $existing = RiwayatKunjungan::where('id_user', $user->id)
            ->where('id_lokasi', $idLokasi)
            ->where('status', 'navigating')
            ->latest('dikunjungi_pada')
            ->first();

        // Kalau sudah ada sesi navigating ke lokasi ini, return sesi yang ada
        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => 'Sesi navigasi sudah berjalan',
                'data' => $existing->load('lokasi'),
                'is_new' => false,
            ]);
        }

        // Buat sesi navigasi baru
        $riwayat = RiwayatKunjungan::create([
            'id_user' => $user->id,
            'id_lokasi' => $idLokasi,
            'status' => 'navigating',
            'mulai_navigasi' => now(),
            'dikunjungi_pada' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Navigasi dimulai, selamat jalan!',
            'data' => [
                'id_riwayat' => $riwayat->id_riwayat,
                'id_lokasi' => $lokasi->id_lokasi,
                'nama_lokasi' => $lokasi->nama_tempat,
                'latitude' => $lokasi->latitude,
                'longitude' => $lokasi->longitude,
                'status' => $riwayat->status,
                'mulai_navigasi' => $riwayat->mulai_navigasi,
                'radius_tiba' => self::RADIUS_TIBA,
            ],
            'is_new' => true,
        ], 201);
    }

    // -------------------------------------------------------
    // POST /api/riwayat/{id}/tiba
    // Dipanggil mobile saat GPS user masuk radius 100m dari lokasi
    // Body: { "lat": -0.9492, "lng": 100.3543 }
    // -------------------------------------------------------
    public function tandaiTiba(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
        ]);

        // Pastikan riwayat milik user yang login dan masih navigating
        $riwayat = RiwayatKunjungan::where('id_riwayat', $id)
            ->where('id_user', $request->user()->id)
            ->where('status', 'navigating')
            ->firstOrFail();

        $lokasi = $riwayat->lokasi;
        $latUser = (float) $request->lat;
        $lngUser = (float) $request->lng;

        // Hitung jarak user ke lokasi tujuan (Haversine)
        $jarak = $this->hitungJarak(
            $latUser,
            $lngUser,
            (float) $lokasi->latitude,
            (float) $lokasi->longitude
        );

        // Validasi: user harus benar-benar dekat lokasi
        if ($jarak > self::RADIUS_TIBA) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum cukup dekat dengan lokasi tujuan.',
                'jarak_saat_ini' => round($jarak) . ' m',
                'radius_tiba' => self::RADIUS_TIBA . ' m',
            ], 422);
        }

        // Hitung jarak tempuh total (dari mulai navigasi)
        $jarakTempuh = $this->hitungJarak(
            (float) $riwayat->latitude_arrived ?? $latUser,
            (float) $riwayat->longitude_arrived ?? $lngUser,
            (float) $lokasi->latitude,
            (float) $lokasi->longitude
        );

        // Update riwayat → status arrived
        $riwayat->update([
            'status' => 'arrived',
            'latitude_arrived' => $latUser,
            'longitude_arrived' => $lngUser,
            'jarak_tempuh' => $jarak,
            'waktu_tiba' => now(),
        ]);

        // Increment jumlah kunjungan di tabel lokasi
        $lokasi->increment('jumlah_kunjungan');

        // Hitung durasi perjalanan
        $durasi = null;
        if ($riwayat->mulai_navigasi) {
            $durasi = $riwayat->mulai_navigasi
                ? (int) abs(now()->diffInMinutes($riwayat->mulai_navigasi))
                : 0;
        }

        return response()->json([
            'success' => true,
            'message' => 'Selamat datang di ' . $lokasi->nama_tempat . '!',
            'data' => [
                'id_riwayat' => $riwayat->id_riwayat,
                'nama_lokasi' => $lokasi->nama_tempat,
                'jarak_tiba' => round($jarak) . ' m',
                'durasi_menit' => $durasi,
                'waktu_tiba' => now()->format('H:i'),
                'jumlah_kunjungan' => $lokasi->jumlah_kunjungan,
                // Trigger mobile untuk tampilkan prompt review
                'prompt_review' => true,
            ],
        ]);
    }

    // -------------------------------------------------------
    // DELETE /api/riwayat/{id}/batal
    // Dipanggil jika user batalkan navigasi sebelum tiba
    // -------------------------------------------------------
    public function batalNavigasi(Request $request, int $id): JsonResponse
    {
        $riwayat = RiwayatKunjungan::where('id_riwayat', $id)
            ->where('id_user', $request->user()->id)
            ->where('status', 'navigating')
            ->firstOrFail();

        // Hapus sesi navigasi yang dibatalkan
        // Tidak disimpan ke riwayat karena belum sampai
        $riwayat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Navigasi dibatalkan',
        ]);
    }

    // -------------------------------------------------------
    // GET /api/riwayat/aktif
    // Cek apakah user punya sesi navigasi yang sedang berjalan
    // Berguna saat mobile app dibuka ulang di tengah navigasi
    // -------------------------------------------------------
    public function cekAktif(Request $request): JsonResponse
    {
        $aktif = RiwayatKunjungan::where('id_user', $request->user()->id)
            ->where('status', 'navigating')
            ->with(['lokasi:id_lokasi,nama_tempat,latitude,longitude,alamat'])
            ->latest('dikunjungi_pada')
            ->first();

        if (!$aktif) {
            return response()->json([
                'success' => true,
                'aktif' => false,
                'data' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'aktif' => true,
            'data' => [
                'id_riwayat' => $aktif->id_riwayat,
                'lokasi' => $aktif->lokasi,
                'mulai_navigasi' => $aktif->mulai_navigasi,
                'radius_tiba' => self::RADIUS_TIBA,
            ],
        ]);
    }

    // -------------------------------------------------------
    // Helper: Haversine formula hitung jarak dua koordinat (meter)
    // -------------------------------------------------------
    private function hitungJarak(
        float $lat1,
        float $lng1,
        float $lat2,
        float $lng2
    ): float {
        $earthRadius = 6371000;

        $latDiff = deg2rad($lat2 - $lat1);
        $lngDiff = deg2rad($lng2 - $lng1);

        $a = sin($latDiff / 2) ** 2
            + cos(deg2rad($lat1))
            * cos(deg2rad($lat2))
            * sin($lngDiff / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}