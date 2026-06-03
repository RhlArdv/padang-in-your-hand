<?php

namespace Tests\Feature\Api;

use App\Models\Kategori;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Lokasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class NavigasiApiTest extends TestCase
{
    use RefreshDatabase;

    private $kategori;
    private $kecamatan;
    private $kelurahan;
    private $user;
    private $lokasi;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->kategori = Kategori::create([
            'nama_kategori' => 'Wisata Alam',
            'icon' => 'nature',
        ]);

        $this->kecamatan = Kecamatan::create([
            'nama_kecamatan' => 'Padang Barat',
        ]);

        $this->kelurahan = Kelurahan::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'nama_kelurahan' => 'Belakang Tangsi',
        ]);

        $this->lokasi = Lokasi::create([
            'nama_tempat' => 'Pantai Padang',
            'id_kategori' => $this->kategori->id_kategori,
            'alamat' => 'Jl. Samudera',
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'latitude' => -0.94924,
            'longitude' => 100.35427,
            'status_verifikasi' => 'disetujui',
            'created_by' => $this->user->id,
        ]);
    }

    public function test_navigasi_rute_endpoint_returns_valid_route_data(): void
    {
        // Fake OpenRouteService API
        Http::fake([
            'https://api.openrouteservice.org/v2/directions/*' => Http::response([
                'routes' => [
                    [
                        'summary' => [
                            'distance' => 1500,
                            'duration' => 240,
                        ],
                        'geometry' => 'coords_polyline_data',
                        'segments' => [
                            [
                                'steps' => [
                                    [
                                        'instruction' => 'Lurus terus di Jl. Samudera',
                                        'name' => 'Jl. Samudera',
                                        'distance' => 1500,
                                        'duration' => 240,
                                        'type' => 0,
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = $this->getJson("/api/navigasi/{$this->lokasi->id_lokasi}?lat=-0.9500&lng=100.3600&mode=mobil");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'tujuan' => [
                    'id_lokasi' => $this->lokasi->id_lokasi,
                    'nama' => 'Pantai Padang',
                    'alamat' => 'Jl. Samudera',
                    'latitude' => -0.94924,
                    'longitude' => 100.35427,
                ],
                'mode' => 'mobil',
                'ringkasan' => [
                    'jarak_meter' => 1500,
                    'jarak_teks' => '1.5 km',
                    'durasi_detik' => 240,
                    'durasi_teks' => '4 menit',
                ],
                'geometry' => 'coords_polyline_data',
                'langkah' => [
                    [
                        'instruksi' => 'Lurus terus di Jl. Samudera',
                        'nama_jalan' => 'Jl. Samudera',
                        'jarak_meter' => 1500,
                        'jarak_teks' => '1.5 km',
                        'durasi_detik' => 240,
                        'durasi_teks' => '4 menit',
                        'tipe' => 0,
                    ]
                ],
            ]
        ]);
    }

    public function test_navigasi_semua_mode_endpoint_returns_estimations(): void
    {
        // Fake OpenRouteService API for multiple modes (driving-car, foot-walking, cycling-regular)
        Http::fake([
            'https://api.openrouteservice.org/v2/directions/driving-car/*' => Http::response([
                'routes' => [['summary' => ['distance' => 1500, 'duration' => 240]]]
            ], 200),
            'https://api.openrouteservice.org/v2/directions/foot-walking/*' => Http::response([
                'routes' => [['summary' => ['distance' => 1400, 'duration' => 960]]]
            ], 200),
            'https://api.openrouteservice.org/v2/directions/cycling-regular/*' => Http::response([
                'routes' => [['summary' => ['distance' => 1450, 'duration' => 450]]]
            ], 200),
        ]);

        $response = $this->getJson("/api/navigasi/{$this->lokasi->id_lokasi}/semua-mode?lat=-0.9500&lng=100.3600");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'tujuan' => [
                    'id_lokasi' => $this->lokasi->id_lokasi,
                    'nama' => 'Pantai Padang',
                ],
                'estimasi' => [
                    'mobil' => [
                        'jarak_meter' => 1500,
                        'jarak_teks' => '1.5 km',
                        'durasi_detik' => 240,
                        'durasi_teks' => '4 menit',
                    ],
                    'jalan' => [
                        'jarak_meter' => 1400,
                        'jarak_teks' => '1.4 km',
                        'durasi_detik' => 960,
                        'durasi_teks' => '16 menit',
                    ],
                    'sepeda' => [
                        'jarak_meter' => 1450,
                        'jarak_teks' => '1.5 km',
                        'durasi_detik' => 450,
                        'durasi_teks' => '8 menit',
                    ],
                ]
            ]
        ]);
    }
}
