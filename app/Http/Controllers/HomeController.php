<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Teacher;
use App\Models\Facility;
use App\Models\Gallery;
use App\Models\Page;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $latestNews = News::published()->with('author')->orderBy('created_at', 'desc')->take(6)->get();
        $gallery = Gallery::orderBy('created_at', 'desc')->take(8)->get();

        return view('frontend.index', compact('latestNews', 'gallery'));
    }

    public function about()
    {
        return view('frontend.tentang');
    }

    public function academic()
    {
        return view('frontend.akademik');
    }

    public function news()
    {
        $news = News::published()->with('author')->orderBy('created_at', 'desc')->paginate(12);
        return view('frontend.news', compact('news'));
    }

    public function newsDetail($id)
    {
        $news = News::published()->with('author')->findOrFail($id);
        $relatedNews = News::published()
            ->where('category', $news->category)
            ->where('id', '!=', $id)
            ->take(3)
            ->get();

        return view('frontend.news-detail', compact('news', 'relatedNews'));
    }

    public function gallery()
    {
        $gallery = Gallery::orderBy('created_at', 'desc')->get();
        return view('frontend.galeri', compact('gallery'));
    }

    public function facilities()
    {
        $facilities = Facility::orderBy('created_at', 'desc')->get();
        return view('frontend.fasilitas', compact('facilities'));
    }

    public function teachers()
    {
        $principals = Teacher::principal()->get();
        $teachers = Teacher::teachers()->get();
        $staff = Teacher::staff()->get();

        return view('frontend.guru-staff', compact('principals', 'teachers', 'staff'));
    }
}
