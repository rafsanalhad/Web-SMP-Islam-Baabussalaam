<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::orderBy('id', 'desc')->get();
        return view('admin.fasilitas.index', compact('facilities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'category' => 'required|in:class,lab,sport,other',
            'description' => 'nullable',
            'features' => 'nullable',
            'image' => 'nullable|image|max:2048'
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/img/facilities'), $imageName);
        }

        Facility::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'features' => $validated['features'],
            'image' => $imageName
        ]);

        return redirect()->route('admin.fasilitas.index')->with('success', 'Fasilitas berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $facility = Facility::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|max:100',
            'category' => 'required|in:class,lab,sport,other',
            'description' => 'nullable',
            'features' => 'nullable',
            'image' => 'nullable|image|max:2048'
        ]);

        $imageName = $facility->image;
        if ($request->hasFile('image')) {
            if ($facility->image && file_exists(public_path('assets/img/facilities/' . $facility->image))) {
                unlink(public_path('assets/img/facilities/' . $facility->image));
            }
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/img/facilities'), $imageName);
        }

        $facility->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'features' => $validated['features'],
            'image' => $imageName
        ]);

        return redirect()->route('admin.fasilitas.index')->with('success', 'Fasilitas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $facility = Facility::findOrFail($id);

        if ($facility->image && file_exists(public_path('assets/img/facilities/' . $facility->image))) {
            unlink(public_path('assets/img/facilities/' . $facility->image));
        }

        $facility->delete();

        return redirect()->route('admin.fasilitas.index')->with('success', 'Fasilitas berhasil dihapus!');
    }
}
