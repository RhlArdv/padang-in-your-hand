<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Favorit;
use App\Models\Lokasi;
use App\Models\Pengaduan;
use App\Models\RiwayatKunjungan;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // -------------------------------------------------------
        // Stat cards
        // -------------------------------------------------------
        $totalLokasi    = Lokasi::where('status_verifikasi', 'disetujui')->count();
        $totalUser      = User::count();
        $totalKunjungan = RiwayatKunjungan::where('status', 'arrived')->count();
        $totalPengaduan = Pengaduan::whereIn('status', ['menunggu', 'diproses'])->count();
        $totalEvent     = Event::where('status', 'aktif')->count();

        // -------------------------------------------------------
        // Verifikasi kontributor terbaru (5 pending/revisi)
        // -------------------------------------------------------
        $pendingLokasi = Lokasi::whereIn('status_verifikasi', ['pending', 'revisi'])
            ->with(['kontributor:id,name', 'kategori'])
            ->latest()
            ->take(5)
            ->get();

        // -------------------------------------------------------
        // Pengaduan warga terbaru (5 status menunggu/diproses)
        // -------------------------------------------------------
        $recentPengaduan = Pengaduan::with(['user:id,name', 'lokasi:id_lokasi,nama_tempat'])
            ->whereIn('status', ['menunggu', 'diproses'])
            ->latest()
            ->take(5)
            ->get();

        // -------------------------------------------------------
        // Event kota terdekat (3 event aktif)
        // -------------------------------------------------------
        $upcomingEvents = Event::where('status', 'aktif')
            ->where('tanggal_selesai', '>=', now())
            ->orderBy('tanggal_mulai')
            ->take(3)
            ->get();

        // -------------------------------------------------------
        // Statistik kunjungan per bulan (6 bulan terakhir)
        // -------------------------------------------------------
        $kunjunganPerBulan = [];
        for ($i = 5; $i >= 0; $i--) {
            $date  = now()->subMonths($i);
            $label = $date->translatedFormat('M Y');
            $count = RiwayatKunjungan::where('status', 'arrived')
                ->whereYear('dikunjungi_pada', $date->year)
                ->whereMonth('dikunjungi_pada', $date->month)
                ->count();

            $kunjunganPerBulan[] = [
                'label' => $label,
                'count' => $count,
            ];
        }

        return view('dashboard', compact(
            'totalLokasi',
            'totalUser',
            'totalKunjungan',
            'totalPengaduan',
            'totalEvent',
            'pendingLokasi',
            'recentPengaduan',
            'upcomingEvents',
            'kunjunganPerBulan',
        ));
    }
}
