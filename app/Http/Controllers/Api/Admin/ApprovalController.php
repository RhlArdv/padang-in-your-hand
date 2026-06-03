<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApprovalLog;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApprovalController extends Controller
{
    // -------------------------------------------------------
    // GET /api/admin/approval
    // List lokasi yang perlu diproses (pending/revisi)
    // Query params: status, per_page
    // -------------------------------------------------------
    public function index(Request $request): JsonResponse
    {
        $query = Lokasi::with([
            'kategori',
            'fotoUtama',
            'kecamatan',
            'kelurahan',
            'kontributor:id,name,email',
        ]);

        // Filter by status verifikasi
        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        } else {
            // Default: tampilkan pending & revisi
            $query->whereIn('status_verifikasi', ['pending', 'revisi']);
        }

        $lokasi = $query->latest()->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $lokasi,
        ]);
    }

    // -------------------------------------------------------
    // POST /api/admin/approval/{id}/approve
    // Setujui lokasi kontributor
    // -------------------------------------------------------
    public function approve(Request $request, int $id): JsonResponse
    {
        $lokasi = Lokasi::findOrFail($id);

        $request->validate([
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $lokasi->update(['status_verifikasi' => 'disetujui']);

        ApprovalLog::create([
            'id_lokasi' => $lokasi->id_lokasi,
            'id_admin'  => $request->user()->id,
            'status'    => 'disetujui',
            'catatan'   => $request->catatan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lokasi "' . $lokasi->nama_tempat . '" berhasil disetujui',
            'data'    => $lokasi,
        ]);
    }

    // -------------------------------------------------------
    // POST /api/admin/approval/{id}/tolak
    // Tolak lokasi kontributor
    // -------------------------------------------------------
    public function tolak(Request $request, int $id): JsonResponse
    {
        $lokasi = Lokasi::findOrFail($id);

        $request->validate([
            'catatan' => ['required', 'string', 'max:1000'],
        ]);

        $lokasi->update(['status_verifikasi' => 'ditolak']);

        ApprovalLog::create([
            'id_lokasi' => $lokasi->id_lokasi,
            'id_admin'  => $request->user()->id,
            'status'    => 'ditolak',
            'catatan'   => $request->catatan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lokasi "' . $lokasi->nama_tempat . '" telah ditolak',
            'data'    => $lokasi,
        ]);
    }

    // -------------------------------------------------------
    // POST /api/admin/approval/{id}/revisi
    // Minta revisi ke kontributor
    // -------------------------------------------------------
    public function revisi(Request $request, int $id): JsonResponse
    {
        $lokasi = Lokasi::findOrFail($id);

        $request->validate([
            'catatan' => ['required', 'string', 'max:1000'],
        ]);

        $lokasi->update(['status_verifikasi' => 'revisi']);

        ApprovalLog::create([
            'id_lokasi' => $lokasi->id_lokasi,
            'id_admin'  => $request->user()->id,
            'status'    => 'revisi',
            'catatan'   => $request->catatan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lokasi "' . $lokasi->nama_tempat . '" diminta revisi',
            'data'    => $lokasi,
        ]);
    }
}
