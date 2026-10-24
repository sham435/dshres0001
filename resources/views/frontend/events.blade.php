@extends('layouts.frontend')
@section('title', 'Events')
@section('heading', 'Events')
@section('content')
<section class="section"><div class="container">
  @foreach (['Upcoming Events' => $upcoming, 'Past Events' => $past] as $heading => $list)
    <h3 class="fw-bold mb-3 {{ $loop->last ? 'mt-5' : '' }}">{{ $heading }}</h3>
    <div class="row g-4">
      @forelse ($list as $e)
      <div class="col-md-6"><div class="card card-hover h-100"><div class="card-body d-flex gap-3">
        <div class="date-badge"><div class="fs-4 fw-bold">{{ $e->event_date->format('d') }}</div><small>{{ $e->event_date->format('M Y') }}</small></div>
        <div>
          <h5 class="mb-1">{{ $e->title }}</h5>
          <p class="small text-muted mb-1">{{ $e->description }}</p>
          <small class="text-muted"><i class="fa fa-map-marker-alt" aria-hidden="true"></i> {{ $e->location }} @if($e->start_time) · <i class="fa fa-clock" aria-hidden="true"></i> {{ \Carbon\Carbon::parse($e->start_time)->format('g:i A') }} @endif</small>
        </div>
      </div></div></div>
      @empty <p class="text-muted">Nothing here.</p>
      @endforelse
    </div>
  @endforeach
</div></section>
@endsection
