<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApprovalLog;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        $query = Lokasi::with([
            'kategori', 
            'kontributor:id,name,email',
            'kecamatan',
            'kelurahan',
            'fotos',
            'approvalLogs.admin', 
        ])->whereHas('kontributor', function ($q) {
            $q->where('role', 'kontributor');
        });

        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }

        $lokasi = $query->orderByRaw("CASE WHEN status_verifikasi = 'pending' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.approval.index', compact('lokasi'));
    }

    public function show(int $id)
    {
        $lokasi = Lokasi::with(['kategori', 'kecamatan', 'kelurahan', 'fotos', 'kontributor', 'approvalLogs.admin'])
            ->whereHas('kontributor', function ($q) {
                $q->where('role', 'kontributor');
            })
            ->findOrFail($id);
        return view('admin.approval.show', compact('lokasi'));
    }

    public function approve(Request $request, int $id)
    {
        $lokasi = Lokasi::findOrFail($id);

        $lokasi->update(['status_verifikasi' => 'disetujui']);

        ApprovalLog::create([
            'id_lokasi' => $lokasi->id_lokasi,
            'id_admin'  => $request->user()->id,
            'status'    => 'disetujui',
            'catatan'   => $request->catatan,
        ]);

        return redirect()->route('admin.approval.index')
            ->with('success', 'Lokasi "' . $lokasi->nama_tempat . '" berhasil disetujui');
    }

    public function reject(Request $request, int $id)
    {
        $request->validate(['catatan' => ['required', 'string', 'max:1000']]);

        $lokasi = Lokasi::findOrFail($id);

        $lokasi->update(['status_verifikasi' => 'ditolak']);

        ApprovalLog::create([
            'id_lokasi' => $lokasi->id_lokasi,
            'id_admin'  => $request->user()->id,
            'status'    => 'ditolak',
            'catatan'   => $request->catatan,
        ]);

        return redirect()->route('admin.approval.index')
            ->with('success', 'Lokasi "' . $lokasi->nama_tempat . '" telah ditolak');
    }

    public function revision(Request $request, int $id)
    {
        $request->validate(['catatan' => ['required', 'string', 'max:1000']]);

        $lokasi = Lokasi::findOrFail($id);

        $lokasi->update(['status_verifikasi' => 'revisi']);

        ApprovalLog::create([
            'id_lokasi' => $lokasi->id_lokasi,
            'id_admin'  => $request->user()->id,
            'status'    => 'revisi',
            'catatan'   => $request->catatan,
        ]);

        return redirect()->route('admin.approval.index')
            ->with('success', 'Lokasi "' . $lokasi->nama_tempat . '" diminta revisi');
    }
}
