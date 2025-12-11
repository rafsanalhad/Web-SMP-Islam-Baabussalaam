<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Teacher;
use App\Models\Facility;
use App\Models\Gallery;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung semua data untuk statistik
        $news_count = News::count();
        $teachers_count = Teacher::count();
        $facilities_count = Facility::count();
        $gallery_count = Gallery::count();

        // Ambil aktivitas terbaru dengan relasi user
        $recent_activities = ActivityLog::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'news_count',
            'teachers_count',
            'facilities_count',
            'gallery_count',
            'recent_activities'
        ));
    }
}
