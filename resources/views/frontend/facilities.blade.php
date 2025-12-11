@extends('layouts.frontend')

@section('title', 'Fasilitas - SMP Islam Baabussalaam')

@section('content')
<section class="py-5">
    <div class="container">
        <h1 class="display-5 fw-bold mb-4">Fasilitas Sekolah</h1>

        <div class="row g-4">
            @forelse($facilities as $facility)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    @if($facility->image)
                    <img src="{{ asset('assets/img/facilities/' . $facility->image) }}" class="card-img-top" alt="{{ $facility->name }}" style="height: 200px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/400x200?text=No+Image'">
                    @else
                    <img src="https://via.placeholder.com/400x200?text=No+Image" class="card-img-top" alt="{{ $facility->name }}" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <span class="badge bg-{{ $facility->category == 'class' ? 'primary' : ($facility->category == 'lab' ? 'success' : ($facility->category == 'sport' ? 'warning' : 'info')) }} mb-2">
                            {{ ucfirst($facility->category) }}
                        </span>
                        <h5 class="card-title">{{ $facility->name }}</h5>
                        <p class="card-text text-muted">{{ $facility->description }}</p>

                        @if($facility->features)
                        <div class="mt-3">
                            <strong>Fitur:</strong>
                            <ul class="small">
                                @foreach(explode(',', $facility->features) as $feature)
                                <li>{{ trim($feature) }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Informasi fasilitas belum tersedia.
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection