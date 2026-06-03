<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Support\Facades\Cache;

class MapController extends Controller
{
    public function index()
    {
        // Cache data lokasi peta selama 1 jam (3600 detik) sebagai plain array
        // agar tidak terkena error PHP __PHP_Incomplete_Class saat deserialisasi
        $lokasi = Cache::remember('map_lokasi_disetujui', 3600, function () {
            return Lokasi::disetujui()
                ->with(['kategori', 'fotoUtama'])
                ->get(['id_lokasi', 'nama_tempat', 'id_kategori', 'alamat', 'latitude', 'longitude', 'rating_avg'])
                ->toArray();
        });

        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('admin.map.index', compact('lokasi', 'kategoris'));
    }
}
