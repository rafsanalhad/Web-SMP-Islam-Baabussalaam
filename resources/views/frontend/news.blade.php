@extends('layouts.frontend')

@section('title', 'Berita - SMP Islam Baabussalaam')

@section('content')
<section class="py-5">
    <div class="container">
        <h1 class="display-5 fw-bold mb-4">Berita Sekolah</h1>

        <div class="row g-4">
            @forelse($news as $item)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    @if($item->image)
                    <img src="{{ asset('assets/img/news/' . $item->image) }}" class="card-img-top" alt="{{ $item->title }}" style="height: 200px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/400x200?text=No+Image'">
                    @else
                    <img src="https://via.placeholder.com/400x200?text=No+Image" class="card-img-top" alt="{{ $item->title }}" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <span class="badge bg-{{ $item->category == 'academic' ? 'primary' : ($item->category == 'event' ? 'success' : ($item->category == 'achievement' ? 'warning' : 'info')) }} mb-2">
                            {{ ucfirst($item->category) }}
                        </span>
                        <h5 class="card-title">{{ $item->title }}</h5>
                        <p class="card-text text-muted">{{ Str::limit(strip_tags($item->content), 120) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                <i class="far fa-calendar me-1"></i>{{ $item->created_at->format('d M Y') }}
                            </small>
                            <a href="{{ url('/berita/' . $item->id) }}" class="btn btn-sm btn-outline-primary">Baca</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Belum ada berita yang dipublikasikan.
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $news->links() }}
        </div>
    </div>
</section>
@endsection