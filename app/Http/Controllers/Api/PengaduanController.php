<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    // -------------------------------------------------------
    // GET /api/pengaduan
    // List pengaduan milik user yang login
    // -------------------------------------------------------
    public function index(Request $request): JsonResponse
    {
        $pengaduan = Pengaduan::where('id_user', $request->user()->id)
            ->with(['lokasi:id_lokasi,nama_tempat'])
            ->latest()
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $pengaduan,
        ]);
    }

    // -------------------------------------------------------
    // POST /api/pengaduan
    // Buat pengaduan baru + upload foto bukti
    // -------------------------------------------------------
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'id_lokasi'       => ['nullable', 'exists:lokasi,id_lokasi'],
            'jenis_pengaduan' => ['required', 'in:lokasi_salah,foto_tidak_pantas,informasi_salah,tempat_ramai,fasilitas_rusak,lainnya'],
            'isi_pengaduan'   => ['required', 'string', 'max:2000'],
            'foto_bukti'      => ['nullable', 'image', 'max:2048'],
        ]);

        $data = $request->only('id_lokasi', 'jenis_pengaduan', 'isi_pengaduan');
        $data['id_user'] = $request->user()->id;
        $data['status']  = 'pending';

        if ($request->hasFile('foto_bukti')) {
            $data['foto_bukti'] = $request->file('foto_bukti')->store('pengaduan', 'public');
        }

        $pengaduan = Pengaduan::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil dikirim',
            'data'    => $pengaduan->load('lokasi:id_lokasi,nama_tempat'),
        ], 201);
    }

    // -------------------------------------------------------
    // GET /api/pengaduan/{id}
    // Detail pengaduan (milik sendiri atau admin)
    // -------------------------------------------------------
    public function show(Request $request, int $id): JsonResponse
    {
        $pengaduan = Pengaduan::with(['lokasi', 'user:id,name'])->findOrFail($id);

        if (! $request->user()->canApprove() && $pengaduan->id_user !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk melihat pengaduan ini.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data'    => $pengaduan,
        ]);
    }

    // -------------------------------------------------------
    // PUT /api/admin/pengaduan/{id}
    // Admin tindak lanjut pengaduan
    // -------------------------------------------------------
    public function update(Request $request, int $id): JsonResponse
    {
        $pengaduan = Pengaduan::findOrFail($id);

        $request->validate([
            'status'        => ['required', 'in:pending,diproses,selesai,ditolak'],
            'catatan_admin' => ['nullable', 'string', 'max:2000'],
        ]);

        $pengaduan->update($request->only('status', 'catatan_admin'));

        return response()->json([
            'success' => true,
            'message' => 'Status pengaduan berhasil diperbarui',
            'data'    => $pengaduan->load(['lokasi:id_lokasi,nama_tempat', 'user:id,name']),
        ]);
    }
}
