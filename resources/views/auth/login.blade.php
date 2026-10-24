<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login — {{ config('school.name') }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <style>
    body{min-height:100vh;display:flex;align-items:center;background:linear-gradient(120deg,#0f3a96,#1e5bd8 60%,#4a86f0);font-family:system-ui,'Noto Sans',sans-serif}
    .login-card{max-width:440px;width:100%;border:0;border-radius:16px;box-shadow:0 20px 50px rgba(0,0,0,.25)}
    .btn-brand{background:#1e5bd8;color:#fff;border:0}.btn-brand:hover{background:#0f3a96;color:#fff}
  </style>
</head>
<body>
<div class="container d-flex justify-content-center py-4">
  <div class="card login-card"><div class="card-body p-4 p-md-5">
    <a href="{{ route('site.home') }}" class="d-block text-center text-decoration-none fw-bold fs-4 mb-3"><i class="fa fa-graduation-cap" aria-hidden="true"></i> {{ config('school.name') }}</a>
    <h1 class="h4 text-center mb-1">Admin, Teacher, Student or Guardian Login</h1>
    <p class="text-center text-muted small mb-4">Welcome back. Please login to your account.</p>

    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Mobile or Email *</label>
        <input id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus autocomplete="username">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Password *</label>
        <div class="input-group">
          <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
          <button type="button" class="btn btn-outline-secondary" id="toggle">Show</button>
        </div>
        @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
      </div>
      <div class="d-flex justify-content-between mb-3">
        <div class="form-check"><input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}><label class="form-check-label" for="remember">Remember Me</label></div>
        @if (Route::has('password.request'))<a href="{{ route('password.request') }}" class="small">Forgot Password</a>@endif
      </div>
      <button class="btn btn-brand w-100 py-2">Login</button>
    </form>

    @if (config('app.debug'))
    <hr class="my-4">
    <p class="text-center small text-muted mb-2">Login as demo — credentials auto-filled (debug mode only)</p>
    <div class="d-grid gap-2">
      @foreach (config('school.demo_accounts') as $label => [$mail, $pass])
        <button type="button" class="btn btn-outline-primary btn-sm demo" data-e="{{ $mail }}" data-p="{{ $pass }}"><i class="fa fa-bolt" aria-hidden="true"></i> Login As {{ $label }} Demo</button>
      @endforeach
    </div>
    @endif
  </div></div>
</div>
<script>
  const $=id=>document.getElementById(id);
  $('toggle').onclick=()=>{const p=$('password');p.type=p.type==='password'?'text':'password';$('toggle').textContent=p.type==='password'?'Show':'Hide'};
  document.querySelectorAll('.demo').forEach(b=>b.onclick=()=>{$('email').value=b.dataset.e;$('password').value=b.dataset.p;b.closest('div.card-body').querySelector('form').submit()});
</script>
</body>
</html>
