<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $kategori = [
            // Pendidikan
            ['nama_kategori' => 'Sekolah SD',           'icon' => 'school',         'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Sekolah SMP',          'icon' => 'school',         'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Sekolah SMA/SMK',      'icon' => 'school',         'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Kampus/Universitas',   'icon' => 'university',     'created_at' => $now, 'updated_at' => $now],

            // Wisata & Hiburan
            ['nama_kategori' => 'Wisata Alam',          'icon' => 'mountain',       'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Wisata Budaya',        'icon' => 'landmark',       'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Pantai',               'icon' => 'beach',          'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Taman Kota',           'icon' => 'tree',           'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Museum',               'icon' => 'museum',         'created_at' => $now, 'updated_at' => $now],

            // Penginapan
            ['nama_kategori' => 'Hotel',                'icon' => 'hotel',          'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Guest House',          'icon' => 'house',          'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Homestay',             'icon' => 'home',           'created_at' => $now, 'updated_at' => $now],

            // Kuliner
            ['nama_kategori' => 'Rumah Makan',          'icon' => 'utensils',       'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Cafe',                 'icon' => 'coffee',         'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Warung',               'icon' => 'store',          'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Restoran',             'icon' => 'restaurant',     'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Bakery & Kue',         'icon' => 'cake',           'created_at' => $now, 'updated_at' => $now],

            // Kesehatan
            ['nama_kategori' => 'Rumah Sakit',          'icon' => 'hospital',       'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Puskesmas',            'icon' => 'clinic',         'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Apotek',               'icon' => 'pharmacy',       'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Klinik',               'icon' => 'stethoscope',    'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Dokter Gigi',          'icon' => 'tooth',          'created_at' => $now, 'updated_at' => $now],

            // Tempat Ibadah
            ['nama_kategori' => 'Masjid',               'icon' => 'mosque',         'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Musholla',             'icon' => 'mosque',         'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Gereja',               'icon' => 'church',         'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Pura',                 'icon' => 'temple',         'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Vihara',               'icon' => 'temple',         'created_at' => $now, 'updated_at' => $now],

            // Keuangan
            ['nama_kategori' => 'ATM',                  'icon' => 'atm',            'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Bank',                 'icon' => 'bank',           'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Koperasi',             'icon' => 'handshake',      'created_at' => $now, 'updated_at' => $now],

            // Transportasi & Infrastruktur
            ['nama_kategori' => 'Terminal Bus',         'icon' => 'bus',            'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Pelabuhan',            'icon' => 'anchor',         'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Bandara',              'icon' => 'plane',          'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'SPBU',                 'icon' => 'gas-pump',       'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Parkir',               'icon' => 'parking',        'created_at' => $now, 'updated_at' => $now],

            // Olahraga
            ['nama_kategori' => 'Stadion',              'icon' => 'stadium',        'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Lapangan Olahraga',    'icon' => 'sport',          'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Gym & Fitness',        'icon' => 'dumbbell',       'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Kolam Renang',         'icon' => 'swimming',       'created_at' => $now, 'updated_at' => $now],

            // Pemerintahan
            ['nama_kategori' => 'Kantor Pemerintah',    'icon' => 'government',     'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Kantor Kecamatan',     'icon' => 'office',         'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Kantor Kelurahan',     'icon' => 'office',         'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'BUMN/BUMD',            'icon' => 'building',       'created_at' => $now, 'updated_at' => $now],

            // Keamanan
            ['nama_kategori' => 'Kantor Polisi',        'icon' => 'shield',         'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Kantor TNI',           'icon' => 'shield',         'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Pemadam Kebakaran',    'icon' => 'fire-truck',     'created_at' => $now, 'updated_at' => $now],

            // UMKM & Belanja
            ['nama_kategori' => 'Pasar Tradisional',    'icon' => 'market',         'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Mall/Swalayan',        'icon' => 'shopping-mall',  'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'UMKM',                 'icon' => 'shop',           'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Toko Oleh-oleh',       'icon' => 'gift',           'created_at' => $now, 'updated_at' => $now],

            // Smart City (khas Padang)
            ['nama_kategori' => 'Destinasi Smart City', 'icon' => 'smart-city',     'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Titik WiFi Publik',    'icon' => 'wifi',           'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'CCTV Publik',          'icon' => 'cctv',           'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('kategori')->insert($kategori);
    }
}