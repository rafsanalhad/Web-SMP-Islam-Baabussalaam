@extends('layouts.frontend')

@section('title', 'Guru & Staff - SMP Islam Baabussalaam')

@section('content')
<section class="py-5">
    <div class="container">
        <h1 class="display-5 fw-bold mb-4">Guru & Staff</h1>

        <!-- Kepala Sekolah -->
        @if($principals->count() > 0)
        <div class="mb-5">
            <h3 class="fw-bold mb-4">Kepala Sekolah</h3>
            <div class="row g-4">
                @foreach($principals as $principal)
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm text-center">
                        @if($principal->photo)
                        <img src="{{ asset('assets/img/teachers/' . $principal->photo) }}" class="card-img-top rounded-circle mx-auto mt-4" alt="{{ $principal->name }}" style="width: 150px; height: 150px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/150?text=No+Photo'">
                        @else
                        <img src="https://via.placeholder.com/150?text=No+Photo" class="card-img-top rounded-circle mx-auto mt-4" alt="{{ $principal->name }}" style="width: 150px; height: 150px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $principal->name }}</h5>
                            <p class="text-primary fw-semibold">{{ $principal->position }}</p>
                            @if($principal->qualifications)
                            <p class="text-muted small mb-2"><i class="fas fa-graduation-cap me-2"></i>{{ $principal->qualifications }}</p>
                            @endif
                            @if($principal->email)
                            <p class="text-muted small mb-1"><i class="fas fa-envelope me-2"></i>{{ $principal->email }}</p>
                            @endif
                            @if($principal->phone)
                            <p class="text-muted small"><i class="fas fa-phone me-2"></i>{{ $principal->phone }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Guru -->
        @if($teachers->count() > 0)
        <div class="mb-5">
            <h3 class="fw-bold mb-4">Dewan Guru</h3>
            <div class="row g-4">
                @foreach($teachers as $teacher)
                <div class="col-md-6 col-lg-3">
                    <div class="card shadow-sm text-center h-100">
                        @if($teacher->photo)
                        <img src="{{ asset('assets/img/teachers/' . $teacher->photo) }}" class="card-img-top rounded-circle mx-auto mt-3" alt="{{ $teacher->name }}" style="width: 120px; height: 120px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/120?text=No+Photo'">
                        @else
                        <img src="https://via.placeholder.com/120?text=No+Photo" class="card-img-top rounded-circle mx-auto mt-3" alt="{{ $teacher->name }}" style="width: 120px; height: 120px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h6 class="card-title fw-bold">{{ $teacher->name }}</h6>
                            <p class="text-muted small mb-2">{{ $teacher->position }}</p>
                            @if($teacher->qualifications)
                            <p class="text-muted small"><i class="fas fa-graduation-cap me-1"></i>{{ $teacher->qualifications }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Staff -->
        @if($staff->count() > 0)
        <div>
            <h3 class="fw-bold mb-4">Staff Administrasi</h3>
            <div class="row g-4">
                @foreach($staff as $member)
                <div class="col-md-6 col-lg-3">
                    <div class="card shadow-sm text-center h-100">
                        @if($member->photo)
                        <img src="{{ asset('assets/img/teachers/' . $member->photo) }}" class="card-img-top rounded-circle mx-auto mt-3" alt="{{ $member->name }}" style="width: 120px; height: 120px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/120?text=No+Photo'">
                        @else
                        <img src="https://via.placeholder.com/120?text=No+Photo" class="card-img-top rounded-circle mx-auto mt-3" alt="{{ $member->name }}" style="width: 120px; height: 120px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h6 class="card-title fw-bold">{{ $member->name }}</h6>
                            <p class="text-muted small">{{ $member->position }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($principals->count() == 0 && $teachers->count() == 0 && $staff->count() == 0)
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>Informasi guru dan staff belum tersedia.
        </div>
        @endif
    </div>
</section>
@endsection