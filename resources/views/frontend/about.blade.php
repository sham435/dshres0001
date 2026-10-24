@extends('layouts.frontend')
@section('title', 'About')
@section('heading', 'About Us')
@section('content')
<section class="section"><div class="container"><div class="row g-5 align-items-center">
  <div class="col-lg-6"><div class="thumb rounded" style="height:320px"><i class="fa fa-school" aria-hidden="true"></i></div></div>
  <div class="col-lg-6">
    <h2 class="fw-bold">Statement of {{ config('school.name') }}</h2>
    <p class="text-muted">Edit this text to describe your school’s history, vision and mission. Our aim is to provide a safe, inclusive environment where every child can learn in the language they are most comfortable with — English, Tamil or Sinhala.</p>
    <ul class="text-muted">
      <li>Experienced, dedicated teachers</li>
      <li>Transparent exam results and fee tracking for parents</li>
      <li>Ministry of Education aligned curriculum</li>
    </ul>
    <a href="{{ route('admission') }}" class="btn btn-brand">Online Admission</a>
  </div>
</div></div></section>
@endsection
