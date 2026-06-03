<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class NavigasiController extends Controller
{
    // Mode transportasi yang didukung
    private const MODES = [
        'mobil'   => 'driving-car',
        'jalan'   => 'foot-walking',
        'sepeda'  => 'cycling-regular',
    ];

    // Base URL OpenRouteService
    private const ORS_URL = 'https://api.openrouteservice.org/v2/directions';

    // -------------------------------------------------------
    // GET /api/navigasi/{idLokasi}
    // ?lat=-0.9492&lng=100.3543&mode=mobil
    //
    // Ambil rute dari posisi user ke lokasi tujuan
    // -------------------------------------------------------
    public function rute(Request $request, int $idLokasi): JsonResponse
    {
        $request->validate([
            'lat'  => ['required', 'numeric', 'between:-90,90'],
            'lng'  => ['required', 'numeric', 'between:-180,180'],
            'mode' => ['nullable', 'string', 'in:mobil,jalan,sepeda'],
        ]);

        // Ambil lokasi tujuan
        $lokasi = Lokasi::disetujui()->findOrFail($idLokasi);

        $mode    = $request->get('mode', 'mobil');
        $orsMode = self::MODES[$mode];

        // Koordinat: [longitude, latitude] — format ORS
        $asal   = [(float) $request->lng, (float) $request->lat];
        $tujuan = [(float) $lokasi->longitude, (float) $lokasi->latitude];

        // Hit OpenRouteService API
        $response = Http::withHeaders([
            'Authorization' => config('services.openrouteservice.key'),
            'Content-Type'  => 'application/json',
        ])->post(self::ORS_URL . "/{$orsMode}/json", [
            'coordinates'       => [$asal, $tujuan],
            'language'          => 'id',        // instruksi Bahasa Indonesia
            'units'             => 'm',          // jarak dalam meter
            'instructions'      => true,         // aktifkan instruksi navigasi
            'geometry'          => true,         // aktifkan polyline koordinat jalan
            'geometry_simplify' => false,
        ]);

        // Tangani error dari ORS
        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil rute. Silakan coba lagi.',
                'detail'  => $response->json('error.message') ?? 'Unknown error',
            ], 502);
        }

        $data  = $response->json();
        $route = $data['routes'][0] ?? null;

        if (! $route) {
            return response()->json([
                'success' => false,
                'message' => 'Rute tidak ditemukan untuk lokasi ini.',
            ], 404);
        }

        // Format response yang rapi untuk mobile
        return response()->json([
            'success' => true,
            'data'    => [
                'tujuan'  => [
                    'id_lokasi'   => $lokasi->id_lokasi,
                    'nama'        => $lokasi->nama_tempat,
                    'alamat'      => $lokasi->alamat,
                    'latitude'    => $lokasi->latitude,
                    'longitude'   => $lokasi->longitude,
                ],
                'mode'    => $mode,
                'ringkasan' => [
                    'jarak_meter'       => $route['summary']['distance'],
                    'jarak_teks'        => $this->formatJarak($route['summary']['distance']),
                    'durasi_detik'      => $route['summary']['duration'],
                    'durasi_teks'       => $this->formatDurasi($route['summary']['duration']),
                ],
                // Polyline: array koordinat [lng, lat] sepanjang jalan
                // Mobile pakai ini untuk gambar garis rute di peta
                'geometry' => $route['geometry'],

                // Langkah-langkah navigasi (belok kanan, belok kiri, dll)
                'langkah' => $this->formatLangkah($route['segments'][0]['steps'] ?? []),
            ],
        ]);
    }

    // -------------------------------------------------------
    // GET /api/navigasi/{idLokasi}/semua-mode
    // Ambil estimasi jarak & waktu untuk semua mode sekaligus
    // Berguna untuk mobile tampilkan pilihan mode transportasi
    // -------------------------------------------------------
    public function semuaMode(Request $request, int $idLokasi): JsonResponse
    {
        $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $lokasi = Lokasi::disetujui()->findOrFail($idLokasi);
        $asal   = [(float) $request->lng, (float) $request->lat];
        $tujuan = [(float) $lokasi->longitude, (float) $lokasi->latitude];
        $hasil  = [];

        foreach (self::MODES as $namaMode => $orsMode) {
            $response = Http::withHeaders([
                'Authorization' => config('services.openrouteservice.key'),
                'Content-Type'  => 'application/json',
            ])->post(self::ORS_URL . "/{$orsMode}/json", [
                'coordinates' => [$asal, $tujuan],
                'language'    => 'id',
                'units'       => 'm',
                'instructions'=> false, // tidak perlu instruksi, cukup summary
                'geometry'    => false,
            ]);

            if ($response->successful()) {
                $route = $response->json('routes.0');
                if ($route) {
                    $hasil[$namaMode] = [
                        'jarak_meter'  => $route['summary']['distance'],
                        'jarak_teks'   => $this->formatJarak($route['summary']['distance']),
                        'durasi_detik' => $route['summary']['duration'],
                        'durasi_teks'  => $this->formatDurasi($route['summary']['duration']),
                    ];
                }
            } else {
                $hasil[$namaMode] = null; // mode tidak tersedia untuk rute ini
            }
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'tujuan' => [
                    'id_lokasi' => $lokasi->id_lokasi,
                    'nama'      => $lokasi->nama_tempat,
                    'alamat'    => $lokasi->alamat,
                    'latitude'  => $lokasi->latitude,
                    'longitude' => $lokasi->longitude,
                ],
                'estimasi' => $hasil,
            ],
        ]);
    }

    // -------------------------------------------------------
    // Helper: format jarak meter → teks
    // -------------------------------------------------------
    private function formatJarak(float $meter): string
    {
        if ($meter < 1000) {
            return round($meter) . ' m';
        }
        return number_format($meter / 1000, 1) . ' km';
    }

    // -------------------------------------------------------
    // Helper: format durasi detik → teks
    // -------------------------------------------------------
    private function formatDurasi(float $detik): string
    {
        $menit = (int) ceil($detik / 60);

        if ($menit < 60) {
            return $menit . ' menit';
        }

        $jam    = intdiv($menit, 60);
        $sisa   = $menit % 60;

        return $sisa > 0
            ? $jam . ' jam ' . $sisa . ' menit'
            : $jam . ' jam';
    }

    // -------------------------------------------------------
    // Helper: format langkah navigasi dari ORS ke format rapi
    // -------------------------------------------------------
    private function formatLangkah(array $steps): array
    {
        return collect($steps)->map(function ($step) {
            return [
                // Instruksi teks: "Belok kanan", "Lurus", dll
                'instruksi'    => $step['instruction'] ?? '',

                // Nama jalan
                'nama_jalan'   => $step['name'] ?? '',

                // Jarak langkah ini dalam meter
                'jarak_meter'  => $step['distance'] ?? 0,
                'jarak_teks'   => $this->formatJarak($step['distance'] ?? 0),

                // Durasi langkah ini
                'durasi_detik' => $step['duration'] ?? 0,
                'durasi_teks'  => $this->formatDurasi($step['duration'] ?? 0),

                // Tipe manuver (0=lurus, 1=kanan, 2=kiri, dll)
                // Mobile bisa pakai ini untuk tampilkan icon arah
                'tipe'         => $step['type'] ?? 0,
            ];
        })->values()->toArray();
    }
}