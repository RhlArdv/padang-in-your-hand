<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Favorit;
use App\Models\Lokasi;
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
        $totalFavorit   = Favorit::count();
        $totalKunjungan = RiwayatKunjungan::where('status', 'arrived')->count();

        // -------------------------------------------------------
        // Verifikasi kontributor terbaru (5 pending/revisi)
        // -------------------------------------------------------
        $pendingLokasi = Lokasi::whereIn('status_verifikasi', ['pending', 'revisi'])
            ->with(['kontributor:id,name', 'kategori'])
            ->latest()
            ->take(5)
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
            'totalFavorit',
            'totalKunjungan',
            'pendingLokasi',
            'kunjunganPerBulan',
        ));
    }
}
