@extends('layouts.frontend')

@section('title', 'Program Akademik - SMP Islam Baabussalaam')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                @if($page)
                <h1 class="display-5 fw-bold mb-4">{{ $page->title }}</h1>
                <div class="content">
                    {!! $page->content !!}
                </div>
                @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>Halaman akademik belum tersedia.
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection