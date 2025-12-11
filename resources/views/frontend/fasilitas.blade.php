@extends('layouts.frontend')

@section('title', 'Fasilitas Sekolah - SMP Islam Baabussalaam')

@section('styles')
<style>
    :root{--primary:#2a9d8f;--primary-light:rgba(42,157,143,0.1);--secondary:#264653;--accent:#e9c46a}
    .fade-in{opacity:0;transform:translateY(20px);transition:all 0.6s ease-out}.fade-in.visible{opacity:1;transform:translateY(0)}
    .delay-1{transition-delay:0.2s}.delay-2{transition-delay:0.4s}.delay-3{transition-delay:0.6s}
    .breadcrumb-section{background:var(--light);position:relative}
    .breadcrumb{background:transparent;padding:0}
    .breadcrumb-item a{color:var(--primary);text-decoration:none}
    .section-header{position:relative;margin-bottom:3rem}
    .section-header h2:after{content:'';position:absolute;width:50%;height:4px;background:linear-gradient(90deg,var(--primary),var(--accent));bottom:-10px;left:50%;transform:translateX(-50%);border-radius:2px}
    .facility-card{transition:all 0.3s;border-radius:10px;overflow:hidden;border:none;box-shadow:0 5px 15px rgba(0,0,0,0.05);height:100%}
    .facility-card:hover{transform:translateY(-10px);box-shadow:0 15px 30px rgba(0,0,0,0.1)!important}
    .facility-img-container{height:200px;overflow:hidden;position:relative}
    .facility-img-container img{width:100%;height:100%;object-fit:cover;transition:transform 0.5s}
    .facility-card:hover .facility-img-container img{transform:scale(1.1)}
    .facility-overlay{position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top,rgba(0,0,0,0.7),transparent);padding:15px;color:white}
    .gallery-nav{display:flex;justify-content:center;flex-wrap:wrap;margin-bottom:30px;gap:10px}
    .gallery-nav button{border:none;background:#f8f9fa;padding:8px 20px;border-radius:50px;transition:all 0.3s;font-weight:500}
    .gallery-nav button.active,.gallery-nav button:hover{background-color:var(--primary);color:white;transform:translateY(-2px)}
</style>
@endsection

@section('content')
<section class="breadcrumb-section py-3 fade-in">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home me-2"></i>Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Fasilitas</li>
            </ol>
        </nav>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="section-header text-center mb-5 fade-in">
            <h2 class="fw-bold">Fasilitas Sekolah</h2>
            <p class="lead text-muted">Sarana dan Prasarana Unggulan SMP Baabussalaam</p>
        </div>

        @if($facilities->count() > 0)
        @php
            $categories = $facilities->pluck('category')->unique()->filter();
        @endphp

        @if($categories->count() > 0)
        <div class="gallery-nav fade-in delay-1">
            <button class="active" data-filter="all">Semua</button>
            @foreach($categories as $cat)
            <button data-filter="{{ $cat }}">{{ ucfirst($cat) }}</button>
            @endforeach
        </div>
        @endif

        <div class="row g-4">
            @foreach($facilities as $index => $facility)
            <div class="col-md-6 col-lg-4 fade-in delay-{{ $index % 3 }}" data-category="{{ $facility->category }}">
                <div class="card facility-card">
                    <div class="facility-img-container">
                        <img src="{{ asset('assets/img/facilities/'.$facility->image) }}" alt="{{ $facility->name }}">
                        <div class="facility-overlay">
                            <h5 class="mb-0">{{ $facility->name }}</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <p>{{ $facility->description }}</p>
                        @if($facility->features)
                        <ul class="list-unstyled">
                            @foreach(explode(',', $facility->features) as $feature)
                            <li><i class="fas fa-check-circle text-success me-2"></i> {{ trim($feature) }}</li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-building fa-3x text-muted mb-3"></i>
            <p class="text-muted">Belum ada data fasilitas.</p>
        </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){
    const animateOnScroll=()=>{
        document.querySelectorAll('.fade-in').forEach(el=>{
            const pos=el.getBoundingClientRect().top;
            if(pos<window.innerHeight/1.2)el.classList.add('visible');
        });
    };
    animateOnScroll();
    window.addEventListener('scroll',animateOnScroll);
    
    const btns=document.querySelectorAll('.gallery-nav button');
    const cards=document.querySelectorAll('[data-category]');
    btns.forEach(button=>{
        button.addEventListener('click',function(){
            btns.forEach(btn=>btn.classList.remove('active'));
            this.classList.add('active');
            const filter=this.getAttribute('data-filter');
            cards.forEach(card=>{
                card.style.display=(filter==='all'||card.getAttribute('data-category')===filter)?'block':'none';
            });
        });
    });
});
</script>
@endsection
