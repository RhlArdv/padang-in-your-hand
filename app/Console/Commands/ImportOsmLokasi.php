<?php

namespace App\Console\Commands;

use App\Models\Kategori;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Lokasi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Import lokasi dari OpenStreetMap (Overpass API) ke database.
 *
 * Mengambil data POI berdasarkan kategori yang ada di sistem,
 * mapping tag OSM ke kategori lokal, lalu menyimpan ke tabel lokasi.
 */
class ImportOsmLokasi extends Command
{
    protected $signature = 'lokasi:import-osm
                            {--kategori= : Nama kategori spesifik (contoh: "Sekolah")}
                            {--all : Import semua kategori sekaligus}
                            {--dry-run : Tampilkan data tanpa menyimpan ke database}
                            {--user-id=1 : ID user sebagai created_by (default: 1)}';

    protected $description = 'Import data lokasi dari OpenStreetMap (Overpass API) ke database per kategori';

    // Bounding box Kota Padang
    private const BBOX = '-1.08,100.28,-0.78,100.55';
    private const OVERPASS_URLS = [
        'https://overpass-api.de/api/interpreter',
        'https://overpass.kumi.systems/api/interpreter',
        'https://maps.mail.ru/osm/tools/overpass/api/interpreter',
    ];
    private const NOMINATIM_URL = 'https://nominatim.openstreetmap.org/reverse';

    // Jarak minimum (meter) untuk dianggap duplikat
    private const DUPLICATE_THRESHOLD_METERS = 50;

    /**
     * Mapping kategori di DB → tag query Overpass.
     *
     * Setiap kategori berisi array query fragments yang akan digabung
     * dalam union statement Overpass QL.
     */
    private function getOsmTagMapping(): array
    {
        return [
            'Apotek' => [
                'node["amenity"="pharmacy"](__BBOX__)',
                'way["amenity"="pharmacy"](__BBOX__)',
            ],
            'ATM/Bank' => [
                'node["amenity"="atm"](__BBOX__)',
                'node["amenity"="bank"](__BBOX__)',
                'way["amenity"="bank"](__BBOX__)',
            ],
            'Cafe' => [
                'node["amenity"="cafe"](__BBOX__)',
                'way["amenity"="cafe"](__BBOX__)',
            ],
            'Hotel/Penginapan' => [
                'node["tourism"="hotel"](__BBOX__)',
                'way["tourism"="hotel"](__BBOX__)',
                'node["tourism"="guest_house"](__BBOX__)',
                'way["tourism"="guest_house"](__BBOX__)',
                'node["tourism"="hostel"](__BBOX__)',
                'way["tourism"="hostel"](__BBOX__)',
            ],
            'Kampus' => [
                'node["amenity"="university"](__BBOX__)',
                'way["amenity"="university"](__BBOX__)',
                'node["amenity"="college"](__BBOX__)',
                'way["amenity"="college"](__BBOX__)',
            ],
            'Kantor Pemerintah' => [
                'node["office"="government"](__BBOX__)',
                'way["office"="government"](__BBOX__)',
            ],
            'Kantor Polisi' => [
                'node["amenity"="police"](__BBOX__)',
                'way["amenity"="police"](__BBOX__)',
            ],
            'Kantor TNI' => [
                'node["military"](__BBOX__)',
                'way["military"](__BBOX__)',
            ],
            'Pantai' => [
                'node["natural"="beach"](__BBOX__)',
                'way["natural"="beach"](__BBOX__)',
            ],
            'Pelabuhan' => [
                'node["amenity"="ferry_terminal"](__BBOX__)',
                'way["amenity"="ferry_terminal"](__BBOX__)',
                'node["harbour"](__BBOX__)',
                'way["harbour"](__BBOX__)',
            ],
            'Puskesmas' => [
                'node["amenity"="clinic"](__BBOX__)',
                'way["amenity"="clinic"](__BBOX__)',
                'node["healthcare"="clinic"](__BBOX__)',
                'way["healthcare"="clinic"](__BBOX__)',
            ],
            'Rumah Makan' => [
                'node["amenity"="restaurant"](__BBOX__)',
                'way["amenity"="restaurant"](__BBOX__)',
            ],
            'Rumah Sakit' => [
                'node["amenity"="hospital"](__BBOX__)',
                'way["amenity"="hospital"](__BBOX__)',
            ],
            'Sarana Olahraga' => [
                'node["leisure"="sports_centre"](__BBOX__)',
                'way["leisure"="sports_centre"](__BBOX__)',
                'node["leisure"="stadium"](__BBOX__)',
                'way["leisure"="stadium"](__BBOX__)',
                'node["leisure"="pitch"](__BBOX__)',
                'way["leisure"="pitch"](__BBOX__)',
            ],
            'Sekolah' => [
                'node["amenity"="school"](__BBOX__)',
                'way["amenity"="school"](__BBOX__)',
            ],
            'SPBU' => [
                'node["amenity"="fuel"](__BBOX__)',
                'way["amenity"="fuel"](__BBOX__)',
            ],
            'Tempat Ibadah' => [
                'node["amenity"="place_of_worship"](__BBOX__)',
                'way["amenity"="place_of_worship"](__BBOX__)',
            ],
            'Terminal' => [
                'node["amenity"="bus_station"](__BBOX__)',
                'way["amenity"="bus_station"](__BBOX__)',
            ],
            'Wisata' => [
                'node["tourism"="attraction"](__BBOX__)',
                'way["tourism"="attraction"](__BBOX__)',
                'node["tourism"="museum"](__BBOX__)',
                'way["tourism"="museum"](__BBOX__)',
                'node["tourism"="viewpoint"](__BBOX__)',
                'way["tourism"="viewpoint"](__BBOX__)',
            ],
        ];
    }

    public function handle(): int
    {
        $kategoriName = $this->option('kategori');
        $importAll    = $this->option('all');
        $dryRun       = $this->option('dry-run');
        $userId       = (int) $this->option('user-id');

        if (! $kategoriName && ! $importAll) {
            $this->error('Pilih salah satu: --kategori="Nama Kategori" atau --all');
            $this->line('');
            $this->info('Kategori yang tersedia:');
            foreach (array_keys($this->getOsmTagMapping()) as $name) {
                $this->line("  - {$name}");
            }
            return self::FAILURE;
        }

        $mapping = $this->getOsmTagMapping();

        if ($importAll) {
            $categoriesToImport = array_keys($mapping);
        } else {
            if (! isset($mapping[$kategoriName])) {
                $this->error("Kategori '{$kategoriName}' tidak ditemukan di mapping OSM.");
                $this->info('Kategori yang tersedia:');
                foreach (array_keys($mapping) as $name) {
                    $this->line("  - {$name}");
                }
                return self::FAILURE;
            }
            $categoriesToImport = [$kategoriName];
        }

        if ($dryRun) {
            $this->warn('🔍 MODE DRY-RUN: Data hanya ditampilkan, tidak disimpan.');
        }

        $totalImported = 0;
        $totalSkipped  = 0;

        foreach ($categoriesToImport as $catName) {
            $result = $this->importCategory($catName, $mapping[$catName], $userId, $dryRun);
            $totalImported += $result['imported'];
            $totalSkipped  += $result['skipped'];

            // Rate limiting antar kategori
            if ($importAll && $catName !== end($categoriesToImport)) {
                sleep(2);
            }
        }

        $this->newLine();
        $this->info("======================================");
        $this->info("✅ Selesai! Imported: {$totalImported} | Skipped: {$totalSkipped}");
        $this->info("======================================");

        Log::info('lokasi:import-osm completed', [
            'operation'      => 'import_osm_lokasi',
            'total_imported' => $totalImported,
            'total_skipped'  => $totalSkipped,
            'dry_run'        => $dryRun,
        ]);

        return self::SUCCESS;
    }

    /**
     * Import satu kategori dari OSM.
     *
     * @return array{imported: int, skipped: int}
     */
    private function importCategory(string $catName, array $queryFragments, int $userId, bool $dryRun): array
    {
        $this->newLine();
        $this->info("📍 Mengimport: {$catName}");
        $this->line(str_repeat('-', 40));

        // Cari kategori di DB
        $kategori = Kategori::where('nama_kategori', $catName)->first();
        if (! $kategori) {
            $this->warn("  ⚠️ Kategori '{$catName}' tidak ditemukan di database. Dilewati.");
            return ['imported' => 0, 'skipped' => 0];
        }

        // Query Overpass API
        $elements = $this->queryOverpass($queryFragments);
        if ($elements === null) {
            $this->error("  ❌ Gagal mengambil data dari Overpass API.");
            return ['imported' => 0, 'skipped' => 0];
        }

        $this->info("  📦 Ditemukan: " . count($elements) . " lokasi di OSM");

        $imported = 0;
        $skipped  = 0;
        $bar = $this->output->createProgressBar(count($elements));
        $bar->start();

        foreach ($elements as $element) {
            $result = $this->processElement($element, $kategori, $userId, $dryRun);

            if ($result === 'imported') {
                $imported++;
            } else {
                $skipped++;
            }

            $bar->advance();

            // Nominatim rate limit: max 1 req/second
            if (! $dryRun) {
                usleep(1100000); // 1.1 detik
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info("  ✅ Imported: {$imported} | ⏭️ Skipped (duplikat/tanpa nama): {$skipped}");

        return ['imported' => $imported, 'skipped' => $skipped];
    }

    /**
     * Query Overpass API untuk mendapatkan elemen POI.
     *
     * @return array|null
     */
    private function queryOverpass(array $queryFragments): ?array
    {
        $fragments = implode(';', array_map(
            fn (string $f) => str_replace('__BBOX__', self::BBOX, $f),
            $queryFragments
        ));

        $query = "[out:json][timeout:120];({$fragments};);out center;";

        foreach (self::OVERPASS_URLS as $url) {
            try {
                $this->line("  🌐 Mencoba: {$url}");

                $response = Http::withHeaders(['User-Agent' => 'PadangInYourHand/1.0 (Contact: admin@padang.go.id)'])
                    ->connectTimeout(30)
                    ->timeout(300)
                    ->asForm()
                    ->post($url, ['data' => $query]);

                if ($response->successful()) {
                    return $response->json('elements', []);
                }

                $this->warn("  ⚠️ Server merespons status {$response->status()}, mencoba mirror lain...");
            } catch (\Exception $e) {
                $this->warn("  ⚠️ Timeout/error: {$e->getMessage()}, mencoba mirror lain...");
                Log::warning('Overpass API mirror failed', [
                    'operation' => 'import_osm_lokasi',
                    'url'       => $url,
                    'error'     => $e->getMessage(),
                ]);
            }
        }

        Log::error('All Overpass API mirrors failed', ['operation' => 'import_osm_lokasi']);
        return null;
    }

    /**
     * Proses satu elemen OSM: validasi, cek duplikat, resolve wilayah, simpan.
     */
    private function processElement(array $element, Kategori $kategori, int $userId, bool $dryRun): string
    {
        $tags = $element['tags'] ?? [];
        $name = $tags['name'] ?? $tags['name:id'] ?? null;

        // Skip elemen tanpa nama
        if (! $name || strlen(trim($name)) < 2) {
            return 'skipped';
        }

        // Ambil koordinat (node punya lat/lon langsung, way punya center)
        $lat = $element['lat'] ?? ($element['center']['lat'] ?? null);
        $lon = $element['lon'] ?? ($element['center']['lon'] ?? null);

        if (! $lat || ! $lon) {
            return 'skipped';
        }

        // Cek duplikat: nama mirip + koordinat dekat
        $isDuplicate = Lokasi::where('id_kategori', $kategori->id_kategori)
            ->where('nama_tempat', $name)
            ->get()
            ->contains(function (Lokasi $existing) use ($lat, $lon) {
                return $this->calculateDistance(
                    $existing->latitude,
                    $existing->longitude,
                    $lat,
                    $lon
                ) < self::DUPLICATE_THRESHOLD_METERS;
            });

        if ($isDuplicate) {
            return 'skipped';
        }

        // Build alamat dari tag OSM
        $alamat = $this->buildAlamat($tags);

        if ($dryRun) {
            $this->newLine();
            $this->line("    📌 {$name} ({$lat}, {$lon}) — {$alamat}");
            return 'imported';
        }

        // Reverse geocode untuk kecamatan/kelurahan
        $wilayah = $this->resolveWilayah($lat, $lon);

        // Simpan ke database
        Lokasi::create([
            'nama_tempat'       => $name,
            'id_kategori'       => $kategori->id_kategori,
            'alamat'            => $alamat ?: "Kota Padang",
            'id_kecamatan'      => $wilayah['id_kecamatan'],
            'id_kelurahan'      => $wilayah['id_kelurahan'],
            'latitude'          => round($lat, 8),
            'longitude'         => round($lon, 8),
            'deskripsi'         => $this->buildDeskripsi($tags),
            'jam_operasional'   => $tags['opening_hours'] ?? null,
            'kontak'            => $tags['phone'] ?? $tags['contact:phone'] ?? null,
            'website'           => $tags['website'] ?? $tags['contact:website'] ?? null,
            'status_verifikasi' => 'disetujui',
            'created_by'        => $userId,
        ]);

        return 'imported';
    }

    /**
     * Bangun alamat dari tag-tag OSM yang tersedia.
     */
    private function buildAlamat(array $tags): string
    {
        $parts = array_filter([
            $tags['addr:street'] ?? null,
            $tags['addr:housenumber'] ?? null,
            $tags['addr:suburb'] ?? $tags['addr:subdistrict'] ?? null,
            $tags['addr:city'] ?? null,
        ]);

        return $parts ? implode(', ', $parts) : ($tags['address'] ?? '');
    }

    /**
     * Bangun deskripsi dari tag OSM.
     */
    private function buildDeskripsi(array $tags): ?string
    {
        $desc = $tags['description'] ?? $tags['description:id'] ?? null;

        if (! $desc && isset($tags['operator'])) {
            $desc = "Dikelola oleh: {$tags['operator']}";
        }

        return $desc;
    }

    /**
     * Resolve kecamatan & kelurahan dari koordinat via Nominatim.
     *
     * Menggunakan fuzzy matching terhadap data kecamatan/kelurahan di DB.
     *
     * @return array{id_kecamatan: int, id_kelurahan: int}
     */
    private function resolveWilayah(float $lat, float $lon): array
    {
        // Default: kecamatan & kelurahan pertama di DB
        $defaultKec = Kecamatan::first();
        $defaultKel = Kelurahan::first();
        $fallback = [
            'id_kecamatan' => $defaultKec->id_kecamatan,
            'id_kelurahan' => $defaultKel->id_kelurahan,
        ];

        try {
            $response = Http::timeout(10)
                ->withHeaders(['User-Agent' => 'PadangInYourHand/1.0'])
                ->get(self::NOMINATIM_URL, [
                    'lat'            => $lat,
                    'lon'            => $lon,
                    'format'         => 'json',
                    'addressdetails' => 1,
                    'zoom'           => 18,
                ]);

            if (! $response->successful()) {
                return $fallback;
            }

            $address = $response->json('address', []);

            // Coba match kecamatan
            $subdistrict = $address['suburb']
                ?? $address['city_district']
                ?? $address['neighbourhood']
                ?? null;

            $village = $address['village']
                ?? $address['hamlet']
                ?? $address['neighbourhood']
                ?? null;

            $kecamatan = null;
            $kelurahan = null;

            if ($subdistrict) {
                $kecamatan = Kecamatan::whereRaw(
                    'LOWER(nama_kecamatan) LIKE ?',
                    ['%' . mb_strtolower(trim($subdistrict)) . '%']
                )->first();
            }

            if (! $kecamatan && $village) {
                // Coba cari kelurahan dulu, lalu ambil kecamatan-nya
                $kelurahan = Kelurahan::whereRaw(
                    'LOWER(nama_kelurahan) LIKE ?',
                    ['%' . mb_strtolower(trim($village)) . '%']
                )->first();

                if ($kelurahan) {
                    $kecamatan = $kelurahan->kecamatan;
                }
            }

            if (! $kecamatan) {
                return $fallback;
            }

            // Cari kelurahan di dalam kecamatan yang cocok
            if (! $kelurahan && $village) {
                $kelurahan = Kelurahan::where('id_kecamatan', $kecamatan->id_kecamatan)
                    ->whereRaw(
                        'LOWER(nama_kelurahan) LIKE ?',
                        ['%' . mb_strtolower(trim($village)) . '%']
                    )->first();
            }

            // Fallback ke kelurahan pertama di kecamatan tsb
            if (! $kelurahan) {
                $kelurahan = Kelurahan::where('id_kecamatan', $kecamatan->id_kecamatan)->first();
            }

            return [
                'id_kecamatan' => $kecamatan->id_kecamatan,
                'id_kelurahan' => $kelurahan->id_kelurahan,
            ];
        } catch (\Exception $e) {
            Log::warning('Nominatim reverse geocoding failed', [
                'operation' => 'import_osm_lokasi',
                'lat'       => $lat,
                'lon'       => $lon,
                'error'     => $e->getMessage(),
            ]);
            return $fallback;
        }
    }

    /**
     * Hitung jarak antara 2 koordinat (Haversine formula, dalam meter).
     */
    private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000;

        $latDiff = deg2rad($lat2 - $lat1);
        $lonDiff = deg2rad($lon2 - $lon1);

        $a = sin($latDiff / 2) ** 2
            + cos(deg2rad($lat1))
            * cos(deg2rad($lat2))
            * sin($lonDiff / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
