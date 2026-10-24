<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Sinhala:wght@300;400;500;600;700&family=Noto+Sans+Tamil:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .lang-switcher .dropdown-item {
            cursor: pointer;
        }
        .lang-switcher .dropdown-item.active {
            font-weight: 600;
        }
        .skip-link { position: absolute; left: -9999px; top: 0; background: #fff; color: #0f172a; padding: .5rem 1rem; z-index: 2000; border-radius: 0 0 8px 0; font-weight: 700; }
        .skip-link:focus { left: 0; }
        a:focus-visible, button:focus-visible, input:focus-visible, select:focus-visible, textarea:focus-visible {
            outline: 3px solid rgba(51, 65, 85, .45); outline-offset: 2px;
        }
        a, button { touch-action: manipulation; }
        a.btn, button.btn, .nav-link { cursor: pointer; }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .01ms !important; transition-duration: .01ms !important; }
        }
        .alert .icon { margin-right: 10px; }
        .alert .close { color: #000; opacity: .2; }
        .alert .close:hover { opacity: .5; }
        .alert a { color: #fff; text-decoration: underline; }
        .alert-success { color: #fff; background-color: #28a745; border-color: #218838; }
        .alert-danger { color: #fff; background-color: #dc3545; border-color: #c82333; }
        .alert-warning { color: #212529; background-color: #ffc107; border-color: #e0a800; }
        .alert-info { color: #fff; background-color: #17a2b8; border-color: #138496; }
        .callout { background-color: #fff; border: 1px solid #dee2e6; border-left: 5px solid #dee2e6; border-radius: .25rem; margin-bottom: 1rem; padding: 1rem; }
        .callout p:last-child { margin-bottom: 0; }
        .callout-danger { border-left-color: #bd2130; }
        .callout-warning { border-left-color: #d39e00; }
        .callout-info { border-left-color: #117a8b; }
        .callout-success { border-left-color: #1e7e34; }
        .btn.disabled, .btn:disabled { cursor: not-allowed; }
        .btn-flat { border-radius: 0; border-width: 1px; box-shadow: none; }
        .btn-xs { padding: .125rem .25rem; font-size: .75rem; line-height: 1.5; border-radius: .15rem; }
        .tpl-card { background: #fff; border: 1px solid var(--x-border, #e6e8ea); border-radius: 12px; box-shadow: 0 1px 2px rgba(15,23,42,.06), 0 4px 16px rgba(15,23,42,.08); }
        .tpl-card-head { padding: .9rem 1.25rem; border-bottom: 1px solid var(--x-border, #e6e8ea); }
        .tpl-card-title { font-size: 1rem; font-weight: 800; margin: 0; display: flex; align-items: center; gap: .5rem; }
        .tpl-card-title i { color: var(--x-primary, #334155); }
        .tpl-card-body { padding: 1.25rem; }
        .tpl-card-foot { padding: .9rem 1.25rem; border-top: 1px solid var(--x-border, #e6e8ea); }
        .tpl-ico { width: 34px; height: 34px; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; color: #fff; flex-shrink: 0; }
        .tpl-ico.is-violet { background: linear-gradient(135deg,#8b5cf6,#5b21b6); }
        @keyframes x-shake { 0%,100% { transform: translateX(0); } 10%,30%,50%,70%,90% { transform: translateX(-6px); } 20%,40%,60%,80% { transform: translateX(6px); } }
        @keyframes x-wobble { 0%,100% { transform: rotate(0); } 25% { transform: rotate(-4deg); } 75% { transform: rotate(4deg); } }
        .animation__shake { animation: x-shake 1500ms; }
        .animation__wobble { animation: x-wobble 1500ms; }
        .btn-tool { background-color: transparent; color: #6c757d; font-size: .875rem; padding: .25rem .5rem; }
        .btn-tool:hover { color: #495057; }
        @media (prefers-color-scheme: dark) {
            .alert-success { background-color: #1e7e34; border-color: #1c7430; }
            .alert-danger { background-color: #bd2130; border-color: #b21f2d; }
            .alert-warning { background-color: #d39e00; border-color: #c69500; color: #212529; }
            .alert-info { background-color: #117a8b; border-color: #10707f; }
            .callout { background-color: #3a4048; border-color: #6c757d; }
        }
    </style>
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Language Switcher -->
                        <li class="nav-item dropdown lang-switcher">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ __('marks.language_switcher') }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">{{ __('marks.english') }}</a>
                                <a class="dropdown-item" href="{{ route('lang.switch', 'ta') }}">{{ __('marks.tamil') }}</a>
                                <a class="dropdown-item" href="{{ route('lang.switch', 'si') }}">{{ __('marks.sinhala') }}</a>
                            </div>
                        </li>
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ auth()->user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4" id="main-content" tabindex="-1">
            @yield('content')
        </main>
    </div>
</body>
</html>
