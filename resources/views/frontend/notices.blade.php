@extends('layouts.frontend')
@section('title', 'Notices')
@section('heading', 'Notices')
@section('content')
<section class="section"><div class="container">
  <div class="list-group shadow-sm">
    @forelse ($items as $n)
    <div class="list-group-item py-3">
      <div class="d-flex justify-content-between"><h5 class="mb-1">{{ $n->title }}</h5>
        <small class="text-muted">{{ \Carbon\Carbon::parse($n->notice_date)->format('d M Y') }}</small></div>
      <p class="mb-0 text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($n->content), 240) }}</p>
    </div>
    @empty <div class="list-group-item text-muted">No notices.</div>
    @endforelse
  </div>
  <div class="mt-4">{{ $items->links('pagination::bootstrap-4') }}</div>
</div></section>
@endsection
