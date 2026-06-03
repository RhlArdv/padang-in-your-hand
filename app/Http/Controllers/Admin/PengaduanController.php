<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengaduan::with(['user:id,name', 'lokasi:id_lokasi,nama_tempat']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengaduan = $query->latest()->paginate(15)->withQueryString();

        return view('admin.pengaduan.index', compact('pengaduan'));
    }

    public function show(int $id)
    {
        $pengaduan = Pengaduan::with(['user', 'lokasi'])->findOrFail($id);

        return view('admin.pengaduan.show', compact('pengaduan'));
    }

    public function update(Request $request, int $id)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        $request->validate([
            'status'        => ['required', 'in:menunggu,diproses,selesai'],
            'catatan_admin' => ['nullable', 'string', 'max:1000'],
        ]);

        $pengaduan->update($request->only('status', 'catatan_admin'));

        return redirect()->route('admin.pengaduan.show', $id)
            ->with('success', 'Status pengaduan berhasil diperbarui');
    }
}
