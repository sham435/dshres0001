@extends('layouts.frontend')
@section('title', 'Contact Us')
@section('heading', 'Contact Us')
@section('content')
<section class="section"><div class="container"><div class="row g-5">
  <div class="col-lg-5">
    <h3 class="fw-bold">Get In Touch</h3>
    <p class="text-muted">Questions about admission, results or fees? Send us a message.</p>
    <p class="mb-1"><i class="fa fa-phone" aria-hidden="true"></i> {{ config('school.phone') }}</p>
    <p class="mb-1"><i class="fa fa-envelope" aria-hidden="true"></i> {{ config('school.email') }}</p>
    <p><i class="fa fa-map-marker-alt" aria-hidden="true"></i> {{ config('school.address') }}</p>
  </div>
  <div class="col-lg-7">
    <form method="POST" action="{{ route('contact.store') }}" class="row g-3">
      @csrf
      @foreach (['name' => 'Name', 'email' => 'Email', 'subject' => 'Subject'] as $f => $label)
      <div class="{{ $f === 'subject' ? 'col-12' : 'col-md-6' }}">
        <label class="form-label">{{ $label }} *</label>
        <input name="{{ $f }}" type="{{ $f === 'email' ? 'email' : 'text' }}" value="{{ old($f) }}" class="form-control @error($f) is-invalid @enderror" required>
        @error($f)<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      @endforeach
      <div class="col-12">
        <label class="form-label">Message *</label>
        <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
        @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-12"><button class="btn btn-brand px-4">Send Message</button></div>
    </form>
  </div>
</div></div></section>
@endsection
