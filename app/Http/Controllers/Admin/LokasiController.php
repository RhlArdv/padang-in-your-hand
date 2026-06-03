<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Lokasi;
use App\Models\FotoLokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LokasiController extends Controller
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
        ]);

        // Search
        if ($request->filled('search')) {
            $query->where('nama_tempat', 'like', '%' . $request->search . '%');
        }

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        } else {
            $query->where('status_verifikasi', 'disetujui');
        }

        $lokasi    = $query->latest()->paginate(15)->withQueryString();
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('admin.lokasi.index', compact('lokasi', 'kategoris'));
    }

    public function show(int $id)
    {
        $lokasi = Lokasi::with(['kategori', 'kecamatan', 'kelurahan', 'fotos', 'kontributor', 'approvalLogs.admin'])->findOrFail($id);
        return view('admin.lokasi.show', compact('lokasi'));
    }

    public function create()
    {
        $kategoris  = Kategori::orderBy('nama_kategori')->get();
        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();

        return view('admin.lokasi.create', compact('kategoris', 'kecamatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tempat'  => ['required', 'string', 'max:255'],
            'id_kategori'  => ['required', 'exists:kategori,id_kategori'],
            'alamat'       => ['required', 'string'],
            'id_kecamatan' => ['required', 'exists:kecamatan,id_kecamatan'],
            'id_kelurahan' => ['required', 'exists:kelurahan,id_kelurahan'],
            'latitude'     => ['required', 'numeric', 'between:-90,90'],
            'longitude'    => ['required', 'numeric', 'between:-180,180'],
            'deskripsi'    => ['nullable', 'string'],
            'jam_operasional' => ['nullable', 'string', 'max:255'],
            'kontak'       => ['nullable', 'string', 'max:50'],
            'website'      => ['nullable', 'string', 'max:255'],
            'foto.*'       => ['nullable', 'image', 'max:2048'],
        ]);

        $data = $request->only([
            'nama_tempat', 'id_kategori', 'alamat',
            'id_kecamatan', 'id_kelurahan', 'latitude', 'longitude',
            'deskripsi', 'jam_operasional', 'kontak', 'website',
        ]);

        $data['created_by']        = $request->user()->id;
        $data['status_verifikasi'] = 'disetujui'; // admin langsung disetujui

        $lokasi = Lokasi::create($data);

        // Upload foto
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('lokasi-foto', 'public');
                FotoLokasi::create([
                    'id_lokasi' => $lokasi->id_lokasi,
                    'file_foto' => $path,
                ]);
            }
        }

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Lokasi berhasil ditambahkan');
    }

    public function edit(int $id)
    {
        $lokasi     = Lokasi::with('fotos')->findOrFail($id);
        $kategoris  = Kategori::orderBy('nama_kategori')->get();
        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();
        $kelurahans = Kelurahan::where('id_kecamatan', $lokasi->id_kecamatan)
            ->orderBy('nama_kelurahan')->get();

        return view('admin.lokasi.edit', compact('lokasi', 'kategoris', 'kecamatans', 'kelurahans'));
    }

    public function update(Request $request, int $id)
    {
        $lokasi = Lokasi::findOrFail($id);

        $request->validate([
            'nama_tempat'  => ['required', 'string', 'max:255'],
            'id_kategori'  => ['required', 'exists:kategori,id_kategori'],
            'alamat'       => ['required', 'string'],
            'id_kecamatan' => ['required', 'exists:kecamatan,id_kecamatan'],
            'id_kelurahan' => ['required', 'exists:kelurahan,id_kelurahan'],
            'latitude'     => ['required', 'numeric', 'between:-90,90'],
            'longitude'    => ['required', 'numeric', 'between:-180,180'],
            'deskripsi'    => ['nullable', 'string'],
            'jam_operasional' => ['nullable', 'string', 'max:255'],
            'kontak'       => ['nullable', 'string', 'max:50'],
            'website'      => ['nullable', 'string', 'max:255'],
            'foto.*'       => ['nullable', 'image', 'max:2048'],
            'hapus_foto.*' => ['nullable', 'exists:foto_lokasi,id_foto'],
        ]);

        $lokasi->update($request->only([
            'nama_tempat', 'id_kategori', 'alamat',
            'id_kecamatan', 'id_kelurahan', 'latitude', 'longitude',
            'deskripsi', 'jam_operasional', 'kontak', 'website',
        ]));

        // Hapus foto yang ditandai
        if ($request->filled('hapus_foto')) {
            $fotosToDelete = FotoLokasi::whereIn('id_foto', $request->hapus_foto)
                ->where('id_lokasi', $lokasi->id_lokasi)
                ->get();

            foreach ($fotosToDelete as $foto) {
                Storage::disk('public')->delete($foto->file_foto);
                $foto->delete();
            }
        }

        // Upload foto baru
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('lokasi-foto', 'public');
                FotoLokasi::create([
                    'id_lokasi' => $lokasi->id_lokasi,
                    'file_foto' => $path,
                ]);
            }
        }

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Lokasi berhasil diperbarui');
    }

    public function destroy(int $id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->delete();

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Lokasi berhasil dihapus');
    }
}
