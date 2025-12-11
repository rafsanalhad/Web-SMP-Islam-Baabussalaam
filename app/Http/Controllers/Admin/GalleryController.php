<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $gallery = Gallery::orderBy('created_at', 'desc')->get();
        return view('admin.galeri.index', compact('gallery'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:200',
            'description' => 'nullable',
            'category' => 'required|in:event,facility,achievement,activity',
            'image' => 'required|image|max:2048'
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/img/gallery'), $imageName);
        }

        Gallery::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'image' => $imageName
        ]);

        return redirect()->route('admin.galeri.index')->with('success', 'Gambar berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|max:200',
            'description' => 'nullable',
            'category' => 'required|in:event,facility,achievement,activity',
            'image' => 'nullable|image|max:2048'
        ]);

        $imageName = $gallery->image;
        if ($request->hasFile('image')) {
            if ($gallery->image && file_exists(public_path('assets/img/gallery/' . $gallery->image))) {
                unlink(public_path('assets/img/gallery/' . $gallery->image));
            }
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/img/gallery'), $imageName);
        }

        $gallery->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'image' => $imageName
        ]);

        return redirect()->route('admin.galeri.index')->with('success', 'Gambar berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        if ($gallery->image && file_exists(public_path('assets/img/gallery/' . $gallery->image))) {
            unlink(public_path('assets/img/gallery/' . $gallery->image));
        }

        $gallery->delete();

        return redirect()->route('admin.galeri.index')->with('success', 'Gambar berhasil dihapus!');
    }
}
