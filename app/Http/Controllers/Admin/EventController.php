<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('lokasi');

        if ($request->filled('search')) {
            $query->where('nama_event', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('jenis_event')) {
            $query->where('jenis_event', $request->jenis_event);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $events = $query->latest()->paginate(15)->withQueryString();

        return view('admin.events.index', compact('events'));
    }

    public function show(int $id)
    {
        $event = Event::with(['lokasi', 'pembuat'])->findOrFail($id);
        return view('admin.events.show', compact('event'));
    }

    public function create()
    {
        $lokasis = Lokasi::disetujui()->orderBy('nama_tempat')->get(['id_lokasi', 'nama_tempat']);

        return view('admin.events.create', compact('lokasis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_event'          => ['required', 'string', 'max:255'],
            'lokasi_event'        => ['required', 'string', 'max:255'],
            'id_lokasi'           => ['nullable', 'exists:lokasi,id_lokasi'],
            'jenis_event'         => ['required', 'in:festival,wisata,olahraga,budaya,lainnya'],
            'jenis_event_lainnya' => ['required_if:jenis_event,lainnya', 'nullable', 'string', 'max:100'],
            'deskripsi'           => ['nullable', 'string'],
            'banner'              => ['nullable', 'image', 'max:2048'],
            'tanggal_mulai'       => ['required', 'date'],
            'tanggal_selesai'     => ['required', 'date', 'after_or_equal:tanggal_mulai'],
        ]);

        $data = $request->only([
            'nama_event', 'lokasi_event', 'id_lokasi', 'jenis_event',
            'deskripsi', 'tanggal_mulai', 'tanggal_selesai',
        ]);

        // Handle jenis_event lainnya
        if ($request->jenis_event === 'lainnya' && $request->filled('jenis_event_lainnya')) {
            $data['deskripsi'] = 'Jenis Event: ' . $request->jenis_event_lainnya . "\n\n" . ($data['deskripsi'] ?? '');
        }

        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('event-banner', 'public');
        }

        $data['status']     = 'aktif';
        $data['created_by'] = $request->user()->id;

        Event::create($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil ditambahkan');
    }

    public function edit(int $id)
    {
        $event   = Event::findOrFail($id);
        $lokasis = Lokasi::disetujui()->orderBy('nama_tempat')->get(['id_lokasi', 'nama_tempat']);

        // Extract jenis_event_lainnya from deskripsi if exists
        $jenisEventLainnya = null;
        if ($event->jenis_event === 'lainnya' && $event->deskripsi) {
            if (preg_match('/^Jenis Event:\s*([^\n]+)(?:\n\n|$)/', $event->deskripsi, $matches)) {
                $jenisEventLainnya = trim($matches[1]);
                $event->deskripsi = trim(str_replace($matches[0], '', $event->deskripsi));
            }
        }

        return view('admin.events.edit', compact('event', 'lokasis', 'jenisEventLainnya'));
    }

    public function update(Request $request, int $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'nama_event'          => ['required', 'string', 'max:255'],
            'lokasi_event'        => ['required', 'string', 'max:255'],
            'id_lokasi'           => ['nullable', 'exists:lokasi,id_lokasi'],
            'jenis_event'         => ['required', 'in:festival,wisata,olahraga,budaya,lainnya'],
            'jenis_event_lainnya' => ['required_if:jenis_event,lainnya', 'nullable', 'string', 'max:100'],
            'deskripsi'           => ['nullable', 'string'],
            'banner'              => ['nullable', 'image', 'max:2048'],
            'tanggal_mulai'       => ['required', 'date'],
            'tanggal_selesai'     => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'status'              => ['required', 'in:aktif,nonaktif'],
        ]);

        $data = $request->only([
            'nama_event', 'lokasi_event', 'id_lokasi', 'jenis_event',
            'deskripsi', 'tanggal_mulai', 'tanggal_selesai', 'status',
        ]);

        // Handle jenis_event lainnya
        if ($request->jenis_event === 'lainnya' && $request->filled('jenis_event_lainnya')) {
            $data['deskripsi'] = 'Jenis Event: ' . $request->jenis_event_lainnya . "\n\n" . ($data['deskripsi'] ?? '');
        }

        if ($request->hasFile('banner')) {
            // Hapus banner lama
            if ($event->banner) {
                Storage::disk('public')->delete($event->banner);
            }
            $data['banner'] = $request->file('banner')->store('event-banner', 'public');
        }

        $event->update($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil diperbarui');
    }

    public function destroy(int $id)
    {
        $event = Event::findOrFail($id);

        if ($event->banner) {
            Storage::disk('public')->delete($event->banner);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus');
    }
}
