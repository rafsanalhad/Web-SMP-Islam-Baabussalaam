@extends('layouts.frontend')

@section('title', 'Guru & Staff - SMP Islam Baabussalaam')

@section('styles')
<style>
    :root{--primary:#2a9d8f;--primary-light:rgba(42,157,143,0.1);--secondary:#264653;--accent:#e9c46a}
    .fade-in{opacity:0;transform:translateY(30px);transition:all 0.8s cubic-bezier(0.16,1,0.3,1)}.fade-in.visible{opacity:1;transform:translateY(0)}
    .delay-1{transition-delay:0.2s}.delay-2{transition-delay:0.4s}.delay-3{transition-delay:0.6s}
    .breadcrumb-section{background:var(--light);position:relative}
    .breadcrumb{background:transparent;padding:0}
    .breadcrumb-item a{color:var(--primary);text-decoration:none;transition:all 0.3s ease}
    .breadcrumb-item a:hover{color:var(--secondary)}
    .breadcrumb-item.active{color:var(--secondary);font-weight:500}
    .section-header{position:relative;margin-bottom:3rem}
    .section-header h2{position:relative;display:inline-block}
    .section-header h2:after{content:'';position:absolute;width:50%;height:4px;background:linear-gradient(90deg,var(--primary),var(--accent));bottom:-10px;left:0;border-radius:2px}
    .section-header.text-center h2:after{left:50%;transform:translateX(-50%)}
    .search-container{max-width:600px;margin:0 auto 3rem;position:relative}
    .search-container input{padding-right:50px;border-radius:50px;border:2px solid var(--primary);height:50px}
    .search-container input:focus{box-shadow:0 0 0 3px rgba(42,157,143,0.25);border-color:var(--primary)}
    .search-btn{position:absolute;right:5px;top:50%;transform:translateY(-50%);background:var(--primary);border:none;width:40px;height:40px;border-radius:50%;color:white;transition:all 0.3s}
    .search-btn:hover{background:var(--secondary);transform:translateY(-50%) scale(1.1)}
    .teacher-card{border:none;border-radius:15px;overflow:hidden;transition:all 0.5s cubic-bezier(0.25,0.8,0.25,1);height:100%;box-shadow:0 5px 15px rgba(0,0,0,0.08);position:relative}
    .teacher-card:hover{transform:translateY(-10px);box-shadow:0 15px 30px rgba(0,0,0,0.12)!important}
    .teacher-card:before{content:'';position:absolute;top:0;left:0;width:100%;height:100%;background:linear-gradient(135deg,rgba(42,157,143,0.1),rgba(233,196,106,0.1));opacity:0;transition:opacity 0.3s}
    .teacher-card:hover:before{opacity:1}
    .teacher-img-container{height:300px;overflow:hidden;position:relative}
    .teacher-img-container img{width:100%;height:100%;object-fit:cover;transition:transform 0.5s}
    .teacher-card:hover .teacher-img-container img{transform:scale(1.05)}
    .teacher-overlay{position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top,rgba(0,0,0,0.8),transparent);padding:1.5rem;color:white}
    .teacher-overlay h5{font-weight:600;margin-bottom:0.5rem}
    .teacher-overlay p{font-size:0.9rem;opacity:0.9;margin-bottom:0}
    .teacher-badge{position:absolute;top:15px;right:15px;background:var(--primary);color:white;padding:5px 15px;border-radius:20px;font-size:0.85rem;font-weight:600}
    .category-title{font-size:1.8rem;margin-bottom:2rem;color:var(--secondary)}
</style>
@endsection

@section('content')
<!-- Breadcrumb Navigation -->
<section class="breadcrumb-section py-3 fade-in">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home me-2"></i>Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Guru & Staff</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Guru & Staff Section -->
<section class="py-5">
    <div class="container">
        <div class="section-header text-center mb-4 fade-in">
            <h2 class="fw-bold">Guru & Staff</h2>
            <p class="lead text-muted">Tenaga Pendidik Profesional SMP Baabussalaam</p>
        </div>

        <!-- Search Filter -->
        <div class="search-container fade-in delay-1">
            <div class="position-relative">
                <input type="text" class="form-control" id="searchInput" placeholder="Cari guru atau staff...">
                <button class="search-btn" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        @if($principals->count() > 0)
        <!-- Kepala Sekolah & Wakil -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h4 class="category-title fw-bold fade-in">Kepala Sekolah & Wakil</h4>
            </div>
            
            @foreach($principals as $teacher)
            <div class="col-md-6 col-lg-4 {{ $loop->first ? '' : 'fade-in delay-1' }}">
                <div class="card teacher-card h-100 teacher-item" data-name="{{ strtolower($teacher->name) }}" data-position="{{ strtolower($teacher->position) }}" data-subject="{{ strtolower($teacher->subject ?? '') }}">
                    <div class="teacher-img-container">
                        <img src="{{ asset('assets/img/teachers/'.$teacher->photo) }}" class="img-fluid" alt="{{ $teacher->name }}">
                        <div class="teacher-overlay">
                            <h5>{{ $teacher->name }}</h5>
                            <p>{{ $teacher->position }}</p>
                        </div>
                        <div class="teacher-badge">{{ $teacher->position == 'Kepala Sekolah' ? 'Kepsek' : 'Wakasek' }}</div>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-graduation-cap text-primary me-2"></i> {{ $teacher->education }}</li>
                            @if($teacher->subject)
                            <li class="mb-2"><i class="fas fa-book text-primary me-2"></i> {{ $teacher->subject }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        @if($teachers->count() > 0)
        <!-- Guru Mata Pelajaran -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h4 class="category-title fw-bold fade-in">Guru Mata Pelajaran</h4>
            </div>
            
            @foreach($teachers as $teacher)
            <div class="col-md-6 col-lg-4 fade-in delay-{{ $loop->index % 3 + 1 }}">
                <div class="card teacher-card h-100 teacher-item" data-name="{{ strtolower($teacher->name) }}" data-position="{{ strtolower($teacher->position) }}" data-subject="{{ strtolower($teacher->subject ?? '') }}">
                    <div class="teacher-img-container">
                        <img src="{{ asset('assets/img/teachers/'.$teacher->photo) }}" class="img-fluid" alt="{{ $teacher->name }}">
                        <div class="teacher-overlay">
                            <h5>{{ $teacher->name }}</h5>
                            <p>{{ $teacher->position }}</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-graduation-cap text-primary me-2"></i> {{ $teacher->education }}</li>
                            @if($teacher->subject)
                            <li class="mb-2"><i class="fas fa-book text-primary me-2"></i> {{ $teacher->subject }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        @if($staff->count() > 0)
        <!-- Staff Administrasi -->
        <div class="row">
            <div class="col-12 text-center">
                <h4 class="category-title fw-bold fade-in">Staff Administrasi</h4>
            </div>
            
            @foreach($staff as $teacher)
            <div class="col-md-6 col-lg-4 fade-in delay-{{ $loop->index % 3 + 1 }}">
                <div class="card teacher-card h-100 teacher-item" data-name="{{ strtolower($teacher->name) }}" data-position="{{ strtolower($teacher->position) }}" data-subject="{{ strtolower($teacher->subject ?? '') }}">
                    <div class="teacher-img-container">
                        <img src="{{ asset('assets/img/teachers/'.$teacher->photo) }}" class="img-fluid" alt="{{ $teacher->name }}">
                        <div class="teacher-overlay">
                            <h5>{{ $teacher->name }}</h5>
                            <p>{{ $teacher->position }}</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-graduation-cap text-primary me-2"></i> {{ $teacher->education }}</li>
                            @if($teacher->subject)
                            <li class="mb-2"><i class="fas fa-briefcase text-primary me-2"></i> {{ $teacher->subject }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        @if($principals->count() == 0 && $teachers->count() == 0 && $staff->count() == 0)
        <div class="text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <p class="text-muted">Belum ada data guru dan staff.</p>
        </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){
    const observer=new IntersectionObserver((entries)=>{
        entries.forEach(entry=>{if(entry.isIntersecting)entry.target.classList.add('visible')})
    },{threshold:0.1});
    document.querySelectorAll('.fade-in').forEach(el=>observer.observe(el));

    // Search functionality
    const searchInput=document.getElementById('searchInput');
    const teacherItems=document.querySelectorAll('.teacher-item');
    
    searchInput.addEventListener('input',function(){
        const searchTerm=this.value.toLowerCase();
        
        teacherItems.forEach(item=>{
            const name=item.dataset.name;
            const position=item.dataset.position;
            const subject=item.dataset.subject;
            
            if(name.includes(searchTerm)||position.includes(searchTerm)||subject.includes(searchTerm)){
                item.closest('.col-md-6').style.display='block';
            }else{
                item.closest('.col-md-6').style.display='none';
            }
        });
    });
});
</script>
@endsection
