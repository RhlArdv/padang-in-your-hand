<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Banner::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $status = $request->status === 'aktif' ? 1 : 0;
            $query->where('is_active', $status);
        }

        $banners = $query->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'     => ['required', 'string', 'max:255'],
            'image'     => ['required', 'image', 'max:2048'],
            'link'      => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'in:on,1,0'],
            'order'     => ['required', 'integer', 'min:0'],
        ]);

        $data = $request->only(['title', 'link', 'order']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        Banner::create($data);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'title'     => ['required', 'string', 'max:255'],
            'image'     => ['nullable', 'image', 'max:2048'],
            'link'      => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'in:on,1,0'],
            'order'     => ['required', 'integer', 'min:0'],
        ]);

        $data = $request->only(['title', 'link', 'order']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $banner = Banner::findOrFail($id);

        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner berhasil dihapus');
    }
}
