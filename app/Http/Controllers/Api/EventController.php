<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    // -------------------------------------------------------
    // GET /api/event
    // List event aktif (publik)
    // Query params: jenis_event, status, per_page
    // -------------------------------------------------------
    public function index(Request $request): JsonResponse
    {
        $query = Event::with(['lokasi:id_lokasi,nama_tempat,latitude,longitude', 'pembuat:id,name']);

        // Filter by jenis event
        if ($request->filled('jenis_event')) {
            $query->where('jenis_event', $request->jenis_event);
        }

        // Default: tampilkan event aktif saja
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->aktif();
        }

        $events = $query->orderBy('tanggal_mulai')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $events,
        ]);
    }

    // -------------------------------------------------------
    // GET /api/event/{id}
    // Detail event lengkap (publik)
    // -------------------------------------------------------
    public function show(int $id): JsonResponse
    {
        $event = Event::with(['lokasi', 'pembuat:id,name'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $event,
        ]);
    }

    // -------------------------------------------------------
    // POST /api/admin/event
    // Buat event baru + upload banner (operator, admin, super_admin)
    // -------------------------------------------------------
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nama_event'          => ['required', 'string', 'max:255'],
            'id_lokasi'           => ['nullable', 'exists:lokasi,id_lokasi'],
            'lokasi_event'        => ['nullable', 'string', 'max:255'],
            'jenis_event'         => ['required', 'in:festival,wisata,olahraga,budaya,lainnya'],
            'jenis_event_lainnya' => ['required_if:jenis_event,lainnya', 'nullable', 'string', 'max:100'],
            'deskripsi'           => ['nullable', 'string'],
            'banner'              => ['nullable', 'image', 'max:2048'],
            'tanggal_mulai'       => ['required', 'date'],
            'tanggal_selesai'     => ['required', 'date', 'after_or_equal:tanggal_mulai'],
        ]);

        $data = $request->only([
            'nama_event', 'id_lokasi', 'lokasi_event',
            'jenis_event', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai',
        ]);

        // Handle jenis_event lainnya
        if ($request->jenis_event === 'lainnya' && $request->filled('jenis_event_lainnya')) {
            $data['deskripsi'] = 'Jenis Event: ' . $request->jenis_event_lainnya . "\n\n" . ($data['deskripsi'] ?? '');
        }

        // Upload banner jika ada
        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('event-banners', 'public');
        }

        $data['created_by'] = $request->user()->id;
        $data['status']     = 'aktif';

        $event = Event::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Event berhasil dibuat',
            'data'    => $event->load('lokasi'),
        ], 201);
    }

    // -------------------------------------------------------
    // PUT /api/admin/event/{id}
    // Update event (operator, admin, super_admin)
    // -------------------------------------------------------
    public function update(Request $request, int $id): JsonResponse
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'nama_event'          => ['sometimes', 'string', 'max:255'],
            'id_lokasi'           => ['sometimes', 'nullable', 'exists:lokasi,id_lokasi'],
            'lokasi_event'        => ['sometimes', 'nullable', 'string', 'max:255'],
            'jenis_event'         => ['sometimes', 'in:festival,wisata,olahraga,budaya,lainnya'],
            'jenis_event_lainnya' => ['required_if:jenis_event,lainnya', 'nullable', 'string', 'max:100'],
            'deskripsi'           => ['sometimes', 'nullable', 'string'],
            'banner'              => ['sometimes', 'nullable', 'image', 'max:2048'],
            'tanggal_mulai'       => ['sometimes', 'date'],
            'tanggal_selesai'     => ['sometimes', 'date', 'after_or_equal:tanggal_mulai'],
            'status'              => ['sometimes', 'in:aktif,selesai,dibatalkan'],
        ]);

        $data = $request->only([
            'nama_event', 'id_lokasi', 'lokasi_event',
            'jenis_event', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai', 'status',
        ]);

        // Handle jenis_event lainnya
        if ($request->has('jenis_event') && $request->jenis_event === 'lainnya' && $request->filled('jenis_event_lainnya')) {
            $data['deskripsi'] = 'Jenis Event: ' . $request->jenis_event_lainnya . "\n\n" . ($data['deskripsi'] ?? '');
        }

        // Upload banner baru jika ada
        if ($request->hasFile('banner')) {
            // Hapus banner lama
            if ($event->banner) {
                Storage::disk('public')->delete($event->banner);
            }
            $data['banner'] = $request->file('banner')->store('event-banners', 'public');
        }

        $event->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Event berhasil diperbarui',
            'data'    => $event->load('lokasi'),
        ]);
    }

    // -------------------------------------------------------
    // DELETE /api/admin/event/{id}
    // Hapus event (operator, admin, super_admin)
    // -------------------------------------------------------
    public function destroy(int $id): JsonResponse
    {
        $event = Event::findOrFail($id);

        // Hapus banner dari storage
        if ($event->banner) {
            Storage::disk('public')->delete($event->banner);
        }

        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event berhasil dihapus',
        ]);
    }
}
