@extends('layouts.frontend')

@section('title', 'Galeri Foto - SMP Islam Baabussalaam')

@section('content')
<section class="py-5">
    <div class="container">
        <h1 class="display-5 fw-bold mb-4">Galeri Foto</h1>

        <div class="row g-4">
            @forelse($gallery as $item)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card shadow-sm h-100">
                    <img src="{{ asset('assets/img/gallery/' . $item->image) }}" class="card-img-top" alt="{{ $item->title }}" style="height: 200px; object-fit: cover; cursor: pointer;" onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'" data-bs-toggle="modal" data-bs-target="#imageModal{{ $item->id }}">
                    <div class="card-body p-2">
                        <span class="badge bg-{{ $item->category == 'academic' ? 'primary' : ($item->category == 'sport' ? 'success' : ($item->category == 'event' ? 'warning' : 'info')) }} mb-1">
                            {{ ucfirst($item->category) }}
                        </span>
                        <p class="card-text small mb-0">{{ $item->title }}</p>
                        @if($item->description)
                        <p class="card-text small text-muted">{{ Str::limit($item->description, 50) }}</p>
                        @endif
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="imageModal{{ $item->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ $item->title }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="{{ asset('assets/img/gallery/' . $item->image) }}" class="img-fluid" alt="{{ $item->title }}">
                                @if($item->description)
                                <p class="mt-3 text-muted">{{ $item->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Belum ada foto di galeri.
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $gallery->links() }}
        </div>
    </div>
</section>
@endsection