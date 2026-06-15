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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
        $correlationId = (string) Str::uuid();
        $userId = $request->user()?->id;

        Log::info('admin_lokasi_store started', [
            'correlationId' => $correlationId,
            'userId' => $userId,
            'nama_tempat' => $request->nama_tempat,
        ]);

        $startTime = microtime(true);

        try {
            $request->validate([
                'nama_tempat'  => ['required', 'string', 'max:255'],
                'id_kategori'  => ['required'],
                'kategori_baru' => ['required_if:id_kategori,lainnya', 'nullable', 'string', 'max:100'],
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

            $kategoriId = $request->id_kategori;
            if ($kategoriId === 'lainnya') {
                $kategori = Kategori::firstOrCreate([
                    'nama_kategori' => trim($request->kategori_baru),
                ]);
                $kategoriId = $kategori->id_kategori;
                Log::info('new_category_created_on_the_fly', [
                    'correlationId' => $correlationId,
                    'userId' => $userId,
                    'id_kategori' => $kategoriId,
                    'nama_kategori' => $kategori->nama_kategori,
                ]);
            } else {
                if (!Kategori::where('id_kategori', $kategoriId)->exists()) {
                    return back()->withErrors(['id_kategori' => 'Kategori yang dipilih tidak valid.'])->withInput();
                }
            }

            $data = $request->only([
                'nama_tempat', 'alamat',
                'id_kecamatan', 'id_kelurahan', 'latitude', 'longitude',
                'deskripsi', 'jam_operasional', 'kontak', 'website',
            ]);
            $data['id_kategori'] = $kategoriId;
            $data['created_by']  = $userId;
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

            $duration = round((microtime(true) - $startTime) * 1000);
            Log::info('admin_lokasi_store success', [
                'correlationId' => $correlationId,
                'userId' => $userId,
                'id_lokasi' => $lokasi->id_lokasi,
                'duration' => $duration,
            ]);

            return redirect()->route('admin.lokasi.index')
                ->with('success', 'Lokasi berhasil ditambahkan');

        } catch (\Illuminate\Validation\ValidationException $e) {
            $duration = round((microtime(true) - $startTime) * 1000);
            Log::warning('admin_lokasi_store validation_failed', [
                'correlationId' => $correlationId,
                'userId' => $userId,
                'errors' => json_encode($e->errors()),
                'duration' => $duration,
            ]);
            throw $e;
        } catch (\Exception $e) {
            $duration = round((microtime(true) - $startTime) * 1000);
            Log::error('admin_lokasi_store failed', [
                'correlationId' => $correlationId,
                'userId' => $userId,
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
                'duration' => $duration,
            ]);
            throw $e;
        }
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
        $correlationId = (string) Str::uuid();
        $userId = $request->user()?->id;

        Log::info('admin_lokasi_update started', [
            'correlationId' => $correlationId,
            'userId' => $userId,
            'id_lokasi' => $id,
        ]);

        $startTime = microtime(true);

        try {
            $lokasi = Lokasi::findOrFail($id);

            $request->validate([
                'nama_tempat'  => ['required', 'string', 'max:255'],
                'id_kategori'  => ['required'],
                'kategori_baru' => ['required_if:id_kategori,lainnya', 'nullable', 'string', 'max:100'],
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

            $kategoriId = $request->id_kategori;
            if ($kategoriId === 'lainnya') {
                $kategori = Kategori::firstOrCreate([
                    'nama_kategori' => trim($request->kategori_baru),
                ]);
                $kategoriId = $kategori->id_kategori;
                Log::info('new_category_created_on_the_fly_update', [
                    'correlationId' => $correlationId,
                    'userId' => $userId,
                    'id_kategori' => $kategoriId,
                    'nama_kategori' => $kategori->nama_kategori,
                ]);
            } else {
                if (!Kategori::where('id_kategori', $kategoriId)->exists()) {
                    return back()->withErrors(['id_kategori' => 'Kategori yang dipilih tidak valid.'])->withInput();
                }
            }

            $updateData = $request->only([
                'nama_tempat', 'alamat',
                'id_kecamatan', 'id_kelurahan', 'latitude', 'longitude',
                'deskripsi', 'jam_operasional', 'kontak', 'website',
            ]);
            $updateData['id_kategori'] = $kategoriId;

            $lokasi->update($updateData);

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

            $duration = round((microtime(true) - $startTime) * 1000);
            Log::info('admin_lokasi_update success', [
                'correlationId' => $correlationId,
                'userId' => $userId,
                'id_lokasi' => $lokasi->id_lokasi,
                'duration' => $duration,
            ]);

            return redirect()->route('admin.lokasi.index')
                ->with('success', 'Lokasi berhasil diperbarui');

        } catch (\Illuminate\Validation\ValidationException $e) {
            $duration = round((microtime(true) - $startTime) * 1000);
            Log::warning('admin_lokasi_update validation_failed', [
                'correlationId' => $correlationId,
                'userId' => $userId,
                'errors' => json_encode($e->errors()),
                'duration' => $duration,
            ]);
            throw $e;
        } catch (\Exception $e) {
            $duration = round((microtime(true) - $startTime) * 1000);
            Log::error('admin_lokasi_update failed', [
                'correlationId' => $correlationId,
                'userId' => $userId,
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
                'duration' => $duration,
            ]);
            throw $e;
        }
    }

    public function destroy(int $id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->delete();

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Lokasi berhasil dihapus');
    }
}
