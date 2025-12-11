@extends('layouts.frontend')

@section('title', $news->title . ' - SMP Islam Baabussalaam')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <article>
                    <span class="badge bg-{{ $news->category == 'academic' ? 'primary' : ($news->category == 'event' ? 'success' : ($news->category == 'achievement' ? 'warning' : 'info')) }} mb-3">
                        {{ ucfirst($news->category) }}
                    </span>
                    <h1 class="display-5 fw-bold mb-3">{{ $news->title }}</h1>

                    <div class="d-flex align-items-center text-muted mb-4">
                        <i class="far fa-user me-2"></i>
                        <span class="me-3">{{ $news->author->fullname ?? 'Admin' }}</span>
                        <i class="far fa-calendar me-2"></i>
                        <span>{{ $news->created_at->format('d F Y') }}</span>
                    </div>

                    @if($news->image)
                    <img src="{{ asset('assets/img/news/' . $news->image) }}" class="img-fluid rounded mb-4" alt="{{ $news->title }}" onerror="this.src='https://via.placeholder.com/800x400?text=No+Image'">
                    @endif

                    <div class="content">
                        {!! nl2br(e($news->content)) !!}
                    </div>
                </article>

                <!-- Related News -->
                @if($relatedNews->count() > 0)
                <div class="mt-5">
                    <h3 class="fw-bold mb-4">Berita Terkait</h3>
                    <div class="row g-3">
                        @foreach($relatedNews as $related)
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                @if($related->image)
                                <img src="{{ asset('assets/img/news/' . $related->image) }}" class="card-img-top" alt="{{ $related->title }}" style="height: 150px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/400x150?text=No+Image'">
                                @endif
                                <div class="card-body">
                                    <h6 class="card-title">{{ $related->title }}</h6>
                                    <a href="{{ url('/berita/' . $related->id) }}" class="btn btn-sm btn-outline-primary">Baca</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Kategori Berita</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Academic
                                <span class="badge bg-primary rounded-pill">{{ \App\Models\News::where('category', 'academic')->published()->count() }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Event
                                <span class="badge bg-success rounded-pill">{{ \App\Models\News::where('category', 'event')->published()->count() }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Achievement
                                <span class="badge bg-warning rounded-pill">{{ \App\Models\News::where('category', 'achievement')->published()->count() }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Announcement
                                <span class="badge bg-info rounded-pill">{{ \App\Models\News::where('category', 'announcement')->published()->count() }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Berita Terbaru</h5>
                        <ul class="list-unstyled">
                            @foreach(\App\Models\News::published()->latest()->take(5)->get() as $latest)
                            <li class="mb-3">
                                <a href="{{ url('/berita/' . $latest->id) }}" class="text-decoration-none">
                                    <small class="text-muted d-block">{{ $latest->created_at->format('d M Y') }}</small>
                                    {{ Str::limit($latest->title, 50) }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection