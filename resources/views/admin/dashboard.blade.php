@extends('layouts.admin')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('styles')
<style>
    .card {
        border-radius: .5rem !important;
    }

    .card-body {
        padding: .75rem 1rem !important;
    }

    .text-xs {
        font-size: .75rem !important;
    }

    .chart-pie {
        height: 210px !important;
        position: relative;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.8s ease;
    }

    .chart-pie.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .keterangan-chart span {
        display: inline-block;
        margin-bottom: 6px;
        font-size: .85rem;
    }

    .keterangan-chart i {
        margin-right: 4px;
    }

    .btn-circle.btn-lg {
        width: 42px;
        height: 42px;
        font-size: .9rem;
    }

    .border-left-primary {
        border-left: .25rem solid #4e73df !important;
    }

    .border-left-success {
        border-left: .25rem solid #1cc88a !important;
    }

    .border-left-info {
        border-left: .25rem solid #36b9cc !important;
    }

    .border-left-warning {
        border-left: .25rem solid #f6c23e !important;
    }
</style>
@endsection

@section('content')
<!-- Kartu Statistik -->
<div class="row">
    @php
    $cards = [
    ['title'=>'Berita','count'=>$news_count,'color'=>'primary','icon'=>'fa-newspaper','link'=>route('admin.berita.index')],
    ['title'=>'Guru & Staff','count'=>$teachers_count,'color'=>'success','icon'=>'fa-chalkboard-teacher','link'=>route('admin.guru.index')],
    ['title'=>'Fasilitas','count'=>$facilities_count,'color'=>'info','icon'=>'fa-building','link'=>route('admin.fasilitas.index')],
    ['title'=>'Galeri','count'=>$gallery_count,'color'=>'warning','icon'=>'fa-images','link'=>route('admin.galeri.index')]
    ];
    $total_all = max($news_count + $teachers_count + $facilities_count + $gallery_count, 1);
    @endphp

    @foreach ($cards as $c)
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-{{ $c['color'] }} shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-{{ $c['color'] }} text-uppercase mb-1">{{ $c['title'] }}</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $c['count'] }}</div>
                    </div>
                    <div class="col-auto"><i class="fas {{ $c['icon'] }} fa-lg text-gray-300"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent text-end small">
                <a href="{{ $c['link'] }}" class="text-{{ $c['color'] }} text-decoration-none">Detail →</a>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Aktivitas & Statistik -->
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">Aktivitas Terbaru</h6>
            </div>
            <div class="card-body">
                @if ($recent_activities->isEmpty())
                <div class="text-center py-3 text-muted small">Belum ada aktivitas</div>
                @else
                <div class="list-group list-group-flush">
                    @foreach ($recent_activities as $a)
                    @php
                    $icons = ['create'=>'fa-plus text-success','update'=>'fa-edit text-primary','delete'=>'fa-trash text-danger'];
                    $icon = $icons[$a->activity_type] ?? 'fa-circle text-secondary';
                    @endphp
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div><i class="fas {{ $icon }} me-2"></i>{{ $a->description }}<br>
                            <small class="text-muted">oleh <strong>{{ $a->user->fullname ?? $a->user->username }}</strong> • {{ $a->created_at->format('d M H:i') }}</small>
                        </div>
                        <small class="text-muted">{{ $a->created_at->diffForHumans() }}</small>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Statistik Pie Chart -->
    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header py-2">
                <h6 class="m-0 fw-bold text-primary">Statistik Konten</h6>
            </div>
            <div class="card-body text-center">
                <div class="chart-pie" id="chartContainer"><canvas id="myPieChart"></canvas></div>
                <div class="keterangan-chart text-start mt-3">
                    <span><i class="fas fa-circle text-primary"></i> Berita ({{ round(($news_count / $total_all) * 100, 1) }}%)</span><br>
                    <span><i class="fas fa-circle text-success"></i> Guru & Staff ({{ round(($teachers_count / $total_all) * 100, 1) }}%)</span><br>
                    <span><i class="fas fa-circle text-info"></i> Fasilitas ({{ round(($facilities_count / $total_all) * 100, 1) }}%)</span><br>
                    <span><i class="fas fa-circle text-warning"></i> Galeri ({{ round(($gallery_count / $total_all) * 100, 1) }}%)</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card shadow-sm mb-4">
    <div class="card-header py-2">
        <h6 class="m-0 fw-bold text-primary">Aksi Cepat</h6>
    </div>
    <div class="card-body py-3">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-2">
                <a href="{{ route('admin.berita.index') }}" class="btn btn-sm btn-primary btn-circle btn-lg"><i class="fas fa-plus"></i></a>
                <p class="small mt-1 mb-0">Tambah Berita</p>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <a href="{{ route('admin.guru.index') }}" class="btn btn-sm btn-success btn-circle btn-lg"><i class="fas fa-user-plus"></i></a>
                <p class="small mt-1 mb-0">Tambah Guru</p>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <a href="{{ route('admin.galeri.index') }}" class="btn btn-sm btn-info btn-circle btn-lg"><i class="fas fa-upload"></i></a>
                <p class="small mt-1 mb-0">Upload Gambar</p>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-warning btn-circle btn-lg"><i class="fas fa-eye"></i></a>
                <p class="small mt-1 mb-0">Lihat Website</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Script Chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartContainer = document.getElementById('chartContainer');
        setTimeout(() => chartContainer.classList.add('visible'), 300);

        new Chart(document.getElementById("myPieChart"), {
            type: 'doughnut',
            data: {
                labels: ["Berita", "Guru & Staff", "Fasilitas", "Galeri"],
                datasets: [{
                    data: [{
                        {
                            $news_count
                        }
                    }, {
                        {
                            $teachers_count
                        }
                    }, {
                        {
                            $facilities_count
                        }
                    }, {
                        {
                            $gallery_count
                        }
                    }],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%',
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 1500
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endsection