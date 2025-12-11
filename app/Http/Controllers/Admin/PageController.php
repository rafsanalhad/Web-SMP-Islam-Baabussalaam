<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('updated_at', 'desc')->get();
        return view('admin.halaman.index', compact('pages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:200',
            'slug' => 'required|max:200|unique:tb_pages,slug',
            'content' => 'nullable',
            'status' => 'required|in:published,draft'
        ]);

        Page::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'content' => $validated['content'],
            'status' => $validated['status'],
            'is_system' => 0
        ]);

        return redirect()->route('admin.halaman.index')->with('success', 'Halaman berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|max:200',
            'slug' => 'required|max:200|unique:tb_pages,slug,' . $id,
            'content' => 'nullable',
            'status' => 'required|in:published,draft'
        ]);

        $page->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'content' => $validated['content'],
            'status' => $validated['status']
        ]);

        return redirect()->route('admin.halaman.index')->with('success', 'Halaman berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);

        if ($page->is_system == 1) {
            return redirect()->route('admin.halaman.index')->with('error', 'Halaman sistem tidak dapat dihapus!');
        }

        $page->delete();

        return redirect()->route('admin.halaman.index')->with('success', 'Halaman berhasil dihapus!');
    }
}
