@extends('layouts.frontend')

@section('title', 'Galeri Kegiatan - SMP Islam Baabussalaam')

@section('styles')
<style>
    :root{--primary:#2a9d8f;--secondary:#264653;--accent:#e9c46a}
    .breadcrumb-section{background:#f8f9fa}
    .breadcrumb{background:transparent;padding:0}
    .breadcrumb-item a{color:var(--primary);text-decoration:none}
    .section-header{position:relative;margin-bottom:3rem}
    .section-header h2:after{content:'';position:absolute;width:50%;height:4px;background:linear-gradient(90deg,var(--primary),var(--accent));bottom:-10px;left:50%;transform:translateX(-50%);border-radius:2px}
    .gallery-item{opacity:0;transform:scale(0.8) rotate(-5deg);transition:all 0.6s cubic-bezier(0.175,0.885,0.32,1.275);border-radius:12px;overflow:hidden;box-shadow:0 10px 20px rgba(0,0,0,0.1);margin-bottom:1.5rem}
    .gallery-item.visible{opacity:1;transform:scale(1) rotate(0)}
    .gallery-item:hover{transform:scale(1.05)!important;box-shadow:0 15px 30px rgba(0,0,0,0.2);z-index:10}
    .gallery-img-container{height:250px;overflow:hidden;position:relative;cursor:pointer}
    .gallery-img-container img{width:100%;height:100%;object-fit:cover;transition:transform 0.5s}
    .gallery-item:hover .gallery-img-container img{transform:scale(1.1)}
    .gallery-overlay{position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);opacity:0;display:flex;align-items:center;justify-content:center;transition:all 0.3s}
    .gallery-item:hover .gallery-overlay{opacity:1}
    .gallery-caption{padding:15px;background:white}
    .gallery-caption h5{margin-bottom:0.5rem;font-weight:600}
    .gallery-caption small{color:#6c757d}
    .gallery-nav{display:flex;justify-content:center;flex-wrap:wrap;margin-bottom:30px;gap:10px}
    .gallery-nav button{border:none;background:#f8f9fa;padding:8px 20px;border-radius:50px;transition:all 0.3s;font-weight:500}
    .gallery-nav button.active,.gallery-nav button:hover{background-color:var(--primary);color:white;transform:translateY(-3px);box-shadow:0 5px 15px rgba(0,0,0,0.1)}
    .modal-gallery .modal-content{background:transparent;border:none}
    .modal-gallery .modal-body{padding:0}
    .modal-gallery img{border-radius:10px;max-height:80vh}
</style>
@endsection

@section('content')
<section class="breadcrumb-section py-3">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home me-2"></i>Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Galeri</li>
            </ol>
        </nav>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="fw-bold">Galeri Kegiatan</h2>
            <p class="lead text-muted">Momen Berharga di SMP Baabussalaam</p>
        </div>

        @if($gallery->count() > 0)
        @php
            $categories = $gallery->pluck('category')->unique()->filter();
        @endphp

        @if($categories->count() > 0)
        <div class="gallery-nav">
            <button class="active" data-filter="all">Semua</button>
            @foreach($categories as $cat)
            <button data-filter="{{ $cat }}">{{ ucfirst($cat) }}</button>
            @endforeach
        </div>
        @endif

        <div class="row gallery-grid">
            @foreach($gallery as $index => $item)
            <div class="col-md-6 col-lg-4" data-category="{{ $item->category }}">
                <div class="gallery-item">
                    <div class="gallery-img-container" data-bs-toggle="modal" data-bs-target="#galleryModal" data-bs-img="{{ asset('assets/img/gallery/'.$item->image) }}" data-bs-caption="{{ $item->title }}">
                        <img src="{{ asset('assets/img/gallery/'.$item->image) }}" alt="{{ $item->title }}">
                        <div class="gallery-overlay">
                            <button class="btn btn-primary btn-lg rounded-pill">
                                <i class="fas fa-expand me-2"></i> Lihat
                            </button>
                        </div>
                    </div>
                    <div class="gallery-caption">
                        <h5 class="mb-0">{{ $item->title }}</h5>
                        @if($item->description)
                        <small>{{ $item->description }}</small>
                        @endif
                        <small class="d-block text-muted mt-1">
                            <i class="fas fa-calendar me-1"></i>{{ $item->created_at->format('d M Y') }}
                        </small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-images fa-3x text-muted mb-3"></i>
            <p class="text-muted">Belum ada galeri kegiatan.</p>
        </div>
        @endif
    </div>
</section>

<div class="modal fade modal-gallery" id="galleryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="" id="modalImage" class="img-fluid" alt="">
                <p class="text-white mt-3" id="modalCaption"></p>
            </div>
            <div class="modal-footer justify-content-center border-0">
                <button type="button" class="btn btn-outline-light rounded-pill" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){
    const animateGallery=()=>{
        const items=document.querySelectorAll('.gallery-item');
        const h=window.innerHeight;
        items.forEach((item,i)=>{
            const pos=item.getBoundingClientRect().top;
            const delay=i*0.1;
            if(pos<h-100)setTimeout(()=>item.classList.add('visible'),delay*1000);
        });
    };
    animateGallery();
    window.addEventListener('scroll',animateGallery);
    
    const btns=document.querySelectorAll('.gallery-nav button');
    const items=document.querySelectorAll('.gallery-item');
    btns.forEach(button=>{
        button.addEventListener('click',function(){
            btns.forEach(btn=>btn.classList.remove('active'));
            this.classList.add('active');
            const filter=this.getAttribute('data-filter');
            items.forEach(item=>{
                item.parentElement.style.display=(filter==='all'||item.parentElement.getAttribute('data-category')===filter)?'block':'none';
            });
        });
    });
    
    const modal=document.getElementById('galleryModal');
    if(modal){
        modal.addEventListener('show.bs.modal',function(e){
            const trigger=e.relatedTarget;
            document.getElementById('modalImage').src=trigger.getAttribute('data-bs-img');
            document.getElementById('modalCaption').textContent=trigger.getAttribute('data-bs-caption');
        });
    }
});
</script>
@endsection
