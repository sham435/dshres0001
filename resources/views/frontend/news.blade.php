@extends('layouts.frontend')
@section('title', 'News')
@section('heading', 'News')
@section('content')
<section class="section"><div class="container">
  <div class="row g-4">
    @forelse ($items as $n)
    <div class="col-md-6 col-lg-4"><div class="card card-hover h-100 overflow-hidden">
      <div class="thumb"><i class="fa fa-newspaper" aria-hidden="true"></i></div>
      <div class="card-body">
        <small class="text-muted">{{ $n->published_at->format('d M Y') }}</small>
        <h5 class="mt-1"><a class="text-decoration-none text-dark" href="{{ route('news.show', $n) }}">{{ $n->title }}</a></h5>
        <p class="text-muted small">{{ \Illuminate\Support\Str::limit($n->excerpt, 110) }}</p>
        <a href="{{ route('news.show', $n) }}">Read More →</a>
      </div>
    </div></div>
    @empty <p class="text-muted">No news yet.</p>
    @endforelse
  </div>
  <div class="mt-4">{{ $items->links('pagination::bootstrap-4') }}</div>
</div></section>
@endsection
