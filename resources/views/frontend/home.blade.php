@extends('layouts.frontend')
@section('title', config('school.name'))
@section('content')
<div id="hero" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    @foreach ([1,2,3] as $i)
    <div class="carousel-item {{ $i === 1 ? 'active' : '' }}">
      <div class="hero hero-{{ $i }}">
        <div class="container">
          <h1 class="display-5 fw-bold">Let’s Build Your Future With {{ config('school.name') }}</h1>
          <p class="lead col-lg-7">A wonderful environment where children learn and grow — knowledge and essential skills for every student.</p>
          <a href="{{ route('about') }}" class="btn btn-light me-2">Read More</a>
          <a href="{{ route('contact') }}" class="btn btn-outline-light">Contact Us</a>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  <button class="carousel-control-prev" data-bs-target="#hero" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
  <button class="carousel-control-next" data-bs-target="#hero" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
</div>

<section class="section-alt py-5">
  <div class="container"><div class="row g-4">
    @foreach ($stats as $label => $n)
      <div class="col-6 col-lg-3"><div class="stat"><h3>{{ number_format($n) }}+</h3><div class="text-muted">{{ $label }}</div></div></div>
    @endforeach
  </div></div>
</section>

<section class="section">
  <div class="container"><div class="row align-items-center g-5">
    <div class="col-lg-6">
      <h2 class="fw-bold">Study at {{ config('school.name') }}</h2>
      <p class="text-muted">“We educate knowledge &amp; essential skills” — a phrase that emphasizes the importance of both theoretical knowledge and practical learning.</p>
      <a href="{{ route('admission') }}" class="btn btn-brand">Apply Now</a>
    </div>
    <div class="col-lg-6"><div class="thumb rounded" style="height:280px"><i class="fa fa-school" aria-hidden="true"></i></div></div>
  </div></div>
</section>

<section class="section section-alt">
  <div class="container">
    <h2 class="fw-bold mb-4">What’s Coming Up?</h2>
    <div class="row g-4">
      @forelse ($events as $e)
      <div class="col-md-4"><div class="card card-hover h-100"><div class="card-body d-flex gap-3">
        <div class="date-badge"><div class="fs-4 fw-bold">{{ $e->event_date->format('d') }}</div><small>{{ $e->event_date->format('M') }}</small></div>
        <div><h5 class="mb-1">{{ $e->title }}</h5><small class="text-muted"><i class="fa fa-map-marker-alt" aria-hidden="true"></i> {{ $e->location }}</small></div>
      </div></div></div>
      @empty <p class="text-muted">No upcoming events.</p>
      @endforelse
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-4">
      <h2 class="fw-bold mb-0">Latest From Our Blog</h2><a href="{{ route('news') }}">View all →</a>
    </div>
    <div class="row g-4">
      @foreach ($news as $n)
      <div class="col-md-6 col-lg-3"><div class="card card-hover h-100 overflow-hidden">
        <div class="thumb"><i class="fa fa-newspaper" aria-hidden="true"></i></div>
        <div class="card-body">
          <small class="text-muted">{{ $n->published_at->format('d M Y') }}</small>
          <h6 class="mt-1"><a class="text-decoration-none text-dark" href="{{ route('news.show', $n) }}">{{ $n->title }}</a></h6>
          <p class="small text-muted mb-0">{{ \Illuminate\Support\Str::limit($n->excerpt, 90) }}</p>
        </div>
      </div></div>
      @endforeach
    </div>
  </div>
</section>
@endsection
