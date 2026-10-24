@extends('layouts.frontend')
@section('title', $item->title)
@section('heading', 'News Detail')
@section('content')
<section class="section"><div class="container"><div class="row g-5">
  <div class="col-lg-8">
    <h2 class="fw-bold">{{ $item->title }}</h2>
    <p class="text-muted">{{ $item->published_at->format('d M Y') }}</p>
    <div class="thumb rounded mb-4" style="height:300px"><i class="fa fa-newspaper" aria-hidden="true"></i></div>
    <div style="white-space:pre-line">{{ $item->body }}</div>
  </div>
  <div class="col-lg-4">
    <h5>Recent News</h5>
    <ul class="list-unstyled">
      @foreach ($recent as $r)
        <li class="mb-3"><a class="text-decoration-none" href="{{ route('news.show', $r) }}">{{ $r->title }}</a><br><small class="text-muted">{{ $r->published_at->format('d M Y') }}</small></li>
      @endforeach
    </ul>
  </div>
</div></div></section>
@endsection
