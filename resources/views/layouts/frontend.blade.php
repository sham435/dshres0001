<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', config('school.name'))</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;600;700&family=Noto+Sans+Tamil&family=Noto+Sans+Sinhala&display=swap" rel="stylesheet">
  <style>
    :root{--brand:#1e5bd8;--brand-dark:#0f3a96;--accent:#f5a623}
    body{font-family:'Noto Sans','Noto Sans Tamil','Noto Sans Sinhala',sans-serif;color:#2b2f38}
    .topbar{background:var(--brand-dark);color:#dfe8ff;font-size:.85rem}
    .topbar a{color:#dfe8ff;text-decoration:none}
    .navbar-brand{font-weight:700;color:var(--brand)!important}
    .nav-link.active{color:var(--brand)!important;font-weight:600}
    .btn-brand{background:var(--brand);color:#fff;border:0}.btn-brand:hover{background:var(--brand-dark);color:#fff}
    .btn-accent{background:var(--accent);color:#1b1b1b;border:0;font-weight:600}
    .hero{min-height:480px;color:#fff;display:flex;align-items:center}
    .hero-1{background:linear-gradient(120deg,#0f3a96,#1e5bd8)}
    .hero-2{background:linear-gradient(120deg,#0b6b5b,#17a589)}
    .hero-3{background:linear-gradient(120deg,#6a1b9a,#8e44ad)}
    .page-header{background:linear-gradient(120deg,#0f3a96,#1e5bd8);color:#fff;padding:56px 0}
    .page-header .breadcrumb-item a,.page-header .breadcrumb-item.active,.breadcrumb-item+.breadcrumb-item::before{color:#dfe8ff}
    .section{padding:64px 0}.section-alt{background:#f4f7fc}
    .stat{text-align:center;padding:28px;border-radius:12px;background:#fff;box-shadow:0 4px 18px rgba(20,40,90,.08)}
    .stat h3{color:var(--brand);font-weight:700;margin:0}
    .card-hover{transition:.2s;border:0;box-shadow:0 4px 18px rgba(20,40,90,.08)}
    .card-hover:hover{transform:translateY(-4px)}
    .thumb{height:180px;background:linear-gradient(135deg,#dbe6ff,#f0f4ff);display:flex;align-items:center;justify-content:center;font-size:2.5rem;color:#8aa4e8;object-fit:cover;width:100%}
    .date-badge{background:var(--brand);color:#fff;border-radius:10px;text-align:center;padding:8px 14px;min-width:68px}
    footer{background:#0c1b3d;color:#b9c6e6}footer a{color:#b9c6e6;text-decoration:none}footer a:hover{color:#fff}
  </style>
</head>
<body>
<div class="topbar py-2">
  <div class="container d-flex justify-content-between flex-wrap gap-2">
    <span><i class="fa fa-phone" aria-hidden="true"></i> {{ config('school.phone') }} &nbsp; <i class="fa fa-envelope" aria-hidden="true"></i> {{ config('school.email') }}</span>
    <span>
      @foreach (['en' => 'English','ta' => 'தமிழ்','si' => 'සිංහල'] as $code => $label)
        <a href="{{ url('lang/'.$code) }}" class="me-2">{{ $label }}</a>
      @endforeach
      <a href="{{ route('login') }}" class="fw-semibold ms-2">Login</a>
    </span>
  </div>
</div>

<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand" href="{{ route('site.home') }}"><i class="fa fa-graduation-cap" aria-hidden="true"></i> {{ config('school.name') }}</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        @foreach ([
          'home' => 'Home', 'about' => 'About', 'news' => 'News', 'events' => 'Events',
          'notices' => 'Notices', 'result' => 'Result', 'contact' => 'Contact Us',
        ] as $r => $label)
          <li class="nav-item"><a class="nav-link {{ request()->routeIs($r.'*') ? 'active' : '' }}" href="{{ route($r) }}">{{ $label }}</a></li>
        @endforeach
        <li class="nav-item ms-lg-3"><a class="btn btn-accent btn-sm px-3" href="{{ route('admission') }}">Online Admission</a></li>
      </ul>
    </div>
  </div>
</nav>

@if (!request()->routeIs('home'))
<div class="page-header">
  <div class="container">
    <h1 class="h2 fw-bold mb-2">@yield('heading')</h1>
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="{{ route('site.home') }}">Home</a></li>
      <li class="breadcrumb-item active">@yield('heading')</li>
    </ol>
  </div>
</div>
@endif

@if (session('success'))
  <div class="container mt-4"><div class="alert alert-success mb-0">{{ session('success') }}</div></div>
@endif

@yield('content')

<footer class="pt-5">
  <div class="container">
    <div class="row g-4 pb-4">
      <div class="col-md-5">
        <h5 class="text-white"><i class="fa fa-graduation-cap" aria-hidden="true"></i> {{ config('school.name') }}</h5>
        <p class="small">Student records, attendance, exams, fees and communication with parents — in one place, in English, Tamil and Sinhala.</p>
        <p class="small mb-0">{{ config('school.address') }}</p>
      </div>
      <div class="col-6 col-md-3">
        <h6 class="text-white">Menus</h6>
        <ul class="list-unstyled small">
          <li><a href="{{ route('news') }}">News</a></li>
          <li><a href="{{ route('events') }}">Events</a></li>
          <li><a href="{{ route('result') }}">Result</a></li>
          <li><a href="{{ route('login') }}">Student Login</a></li>
          <li><a href="{{ route('login') }}">Guardian Login</a></li>
        </ul>
      </div>
      <div class="col-6 col-md-4">
        <h6 class="text-white">Follow</h6>
        <p class="small">
          @foreach (config('school.social') as $name => $url)
            <a href="{{ $url }}" class="me-2 text-capitalize">{{ $name }}</a>
          @endforeach
        </p>
      </div>
    </div>
    <div class="border-top border-secondary py-3 small text-center">© {{ date('Y') }} {{ config('school.name') }}. All rights reserved.</div>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
