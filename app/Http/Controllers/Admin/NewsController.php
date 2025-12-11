<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with('author')->orderBy('created_at', 'desc')->get();

        $countAcademic = News::where('category', 'academic')->count();
        $countEvent = News::where('category', 'event')->count();
        $countAchievement = News::where('category', 'achievement')->count();
        $countAnnouncement = News::where('category', 'announcement')->count();

        return view('admin.berita.index', compact('news', 'countAcademic', 'countEvent', 'countAchievement', 'countAnnouncement'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'excerpt' => 'nullable',
            'category' => 'required|in:academic,event,achievement,announcement',
            'status' => 'required|in:published,draft',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['author_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/img/news'), $filename);
            $validated['image'] = $filename;
        }

        $news = News::create($validated);

        ActivityLog::logActivity(
            Auth::id(),
            'create',
            'Menambahkan berita baru: ' . $news->title,
            'news',
            $news->id
        );

        return redirect()->route('admin.news')->with('success', 'Berita berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'excerpt' => 'nullable',
            'category' => 'required|in:academic,event,achievement,announcement',
            'status' => 'required|in:published,draft',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($news->image && file_exists(public_path('assets/img/news/' . $news->image))) {
                unlink(public_path('assets/img/news/' . $news->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/img/news'), $filename);
            $validated['image'] = $filename;
        }

        $news->update($validated);

        ActivityLog::logActivity(
            Auth::id(),
            'update',
            'Memperbarui berita: ' . $news->title,
            'news',
            $news->id
        );

        return redirect()->route('admin.news')->with('success', 'Berita berhasil diperbarui');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);

        // Delete image
        if ($news->image && file_exists(public_path('assets/img/news/' . $news->image))) {
            unlink(public_path('assets/img/news/' . $news->image));
        }

        $title = $news->title;
        $news->delete();

        ActivityLog::logActivity(
            Auth::id(),
            'delete',
            'Menghapus berita: ' . $title,
            'news',
            $id
        );

        return redirect()->route('admin.news')->with('success', 'Berita berhasil dihapus');
    }

    public function toggleStatus($id)
    {
        $news = News::findOrFail($id);
        $newStatus = $news->status === 'published' ? 'draft' : 'published';
        $news->update(['status' => $newStatus]);

        ActivityLog::logActivity(
            Auth::id(),
            'update',
            'Mengubah status berita "' . $news->title . '" menjadi ' . $newStatus,
            'news',
            $news->id
        );

        return redirect()->route('admin.news')->with('success', 'Status berita berhasil diubah');
    }
}
