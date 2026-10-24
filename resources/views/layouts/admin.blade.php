<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel')) - {{ __('marks.admin_panel') }}</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Sinhala:wght@300;400;500;600;700&family=Noto+Sans+Tamil:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            line-height: 1.7;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        table, .table, td, th {
            font-size: 0.9rem;
            line-height: 1.8;
        }
        .sidebar-header h4 {
            font-size: 1rem;
            line-height: 1.4;
        }
        .nav-link {
            font-size: 0.88rem;
        }
        #sidebar .nav-link { border-radius: 8px; margin-bottom: 1px; transition: var(--x-transition); }
        #sidebar .nav-link:hover { background: rgba(255,255,255,.08); }
        #sidebar .nav-link.active { background: rgba(255,255,255,.14); font-weight: 700; }
        #sidebar .sb-ico { width: 22px; text-align: center; margin-right: .5rem; }
        #side-menu-search::placeholder { color: rgba(255,255,255,.55); }
        /* ---- AdminLTE-pattern components (alert / callout / button / brand / animation / cards / carousel) ---- */
        .alert .icon { margin-right: 10px; }
        .alert .close { color: #000; opacity: .2; }
        .alert .close:hover { opacity: .5; }
        .alert a { color: #fff; text-decoration: underline; }
        .alert-success { color: #fff; background-color: #28a745; border-color: #218838; }
        .alert-danger { color: #fff; background-color: #dc3545; border-color: #c82333; }
        .alert-warning { color: #212529; background-color: #ffc107; border-color: #e0a800; }
        .alert-info { color: #fff; background-color: #17a2b8; border-color: #138496; }
        .alert-primary { color: #fff; background-color: #007bff; border-color: #0069d9; }
        .alert-secondary { color: #fff; background-color: #6c757d; border-color: #5a6268; }
        .callout { background-color: #fff; border: 1px solid #dee2e6; border-left: 5px solid #dee2e6; border-radius: .25rem; margin-bottom: 1rem; padding: 1rem; box-shadow: 0 1px 2px rgba(15,23,42,.06); }
        .callout a { color: #495057; text-decoration: underline; }
        .callout p:last-child { margin-bottom: 0; }
        .callout-danger { border-left-color: #bd2130; }
        .callout-warning { border-left-color: #d39e00; }
        .callout-info { border-left-color: #117a8b; }
        .callout-success { border-left-color: #1e7e34; }
        .btn.disabled, .btn:disabled { cursor: not-allowed; }
        .btn-flat { border-radius: 0; border-width: 1px; box-shadow: none; }
        .btn-file { overflow: hidden; position: relative; }
        .btn-file > input[type="file"] { background-color: #fff; cursor: inherit; display: block; font-size: 100px; min-height: 100%; min-width: 100%; opacity: 0; outline: none; position: absolute; right: 0; text-align: right; top: 0; }
        .btn-app { border-radius: 3px; background-color: #f8f9fa; border: 1px solid #ddd; color: #6c757d; font-size: 12px; height: 60px; margin: 0 0 10px 10px; min-width: 80px; padding: 15px 5px; position: relative; text-align: center; }
        .btn-app > .fa, .btn-app > .fas, .btn-app > .far { display: block; font-size: 20px; }
        .btn-app:hover { background-color: #f8f9fa; border-color: #b8bfc6; }
        .btn-app:active, .btn-app:focus { box-shadow: inset 0 3px 5px rgba(0,0,0,.125); }
        .btn-app > .badge { font-size: 10px; font-weight: 400; position: absolute; right: -10px; top: -3px; }
        .btn-xs { padding: .125rem .25rem; font-size: .75rem; line-height: 1.5; border-radius: .15rem; }
        .brand-link { display: block; line-height: 1.5; padding: .8125rem .5rem; transition: width .3s ease-in-out; white-space: nowrap; }
        .brand-link:hover { color: #fff; text-decoration: none; }
        .brand-image { float: left; line-height: .8; margin-left: .8rem; margin-right: .5rem; margin-top: -3px; max-height: 33px; width: auto; }
        @keyframes x-shake { 0%,100% { transform: translateX(0); } 10%,30%,50%,70%,90% { transform: translateX(-6px); } 20%,40%,60%,80% { transform: translateX(6px); } }
        @keyframes x-wobble { 0%,100% { transform: rotate(0); } 25% { transform: rotate(-4deg); } 75% { transform: rotate(4deg); } }
        .animation__shake { animation: x-shake 1500ms; }
        .animation__wobble { animation: x-wobble 1500ms; }
        .card.maximized-card { height: 100% !important; left: 0; max-height: 100% !important; max-width: 100% !important; position: fixed; top: 0; width: 100% !important; z-index: 1040; }
        .card.maximized-card .card-body { overflow: auto; }
        .card.collapsed-card .card-body, .card.collapsed-card .card-footer { display: none; }
        .btn-tool { background-color: transparent; color: #6c757d; font-size: .875rem; padding: .25rem .5rem; }
        .btn-tool:hover { color: #495057; }
        .btn-tool:focus { box-shadow: none !important; }
        .carousel-control-custom-icon > .fa, .carousel-control-custom-icon > .fas { display: inline-block; font-size: 40px; margin-top: -20px; position: absolute; top: 50%; z-index: 5; }
        .carousel-control-prev .carousel-control-custom-icon { margin-left: -20px; }
        .carousel-control-next .carousel-control-custom-icon { margin-right: 20px; }
        @media (prefers-color-scheme: dark) {
            .alert-success { background-color: #1e7e34; border-color: #1c7430; }
            .alert-danger { background-color: #bd2130; border-color: #b21f2d; }
            .alert-warning { background-color: #d39e00; border-color: #c69500; color: #212529; }
            .alert-info { background-color: #117a8b; border-color: #10707f; }
            .callout { background-color: #3a4048; border-color: #6c757d; }
            .callout-danger { border-left-color: #e4606d; }
            .callout-warning { border-left-color: #ffce3a; }
            .callout-info { border-left-color: #4cd3e6; }
            .callout-success { border-left-color: #48b461; }
            .btn-app { background-color: #3a4048; color: #fff; border-color: #6c757d; }
        }
        h1, h2, h3, h4, h5, h6 {
            letter-spacing: 0.02em;
        }
        .badge {
            font-size: 0.8rem;
        }
        /* ---- Exclusive design system (ui-ux-pro-max): operations console ---- */
        :root {
            --x-primary: #334155;
            --x-primary-dark: #1e293b;
            --x-accent: #059669;
            --x-accent-dark: #047857;
            --x-bg: #f8fafc;
            --x-fg: #0f172a;
            --x-muted: #f2f3f4;
            --x-border: #e6e8ea;
            --x-danger: #dc2626;
            --x-radius: 12px;
            --x-shadow: 0 1px 2px rgba(15, 23, 42, .06), 0 4px 16px rgba(15, 23, 42, .08);
            --x-transition: all .2s ease-out;
        }
        body { background-color: var(--x-bg); color: var(--x-fg); }
        .x-page-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: .75rem; }
        .x-page-head h4 { margin: 0; font-weight: 700; letter-spacing: .01em; }
        .x-page-head .actions { display: flex; gap: .5rem; flex-wrap: wrap; }
        .x-card { border: 1px solid var(--x-border); border-radius: var(--x-radius); box-shadow: var(--x-shadow); background: #fff; }
        .x-stat { border-left: 4px solid var(--x-primary); border-radius: var(--x-radius); box-shadow: var(--x-shadow); background: #fff; transition: var(--x-transition); }
        .x-stat:hover { transform: translateY(-2px); }
        .x-stat .x-stat-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #64748b; }
        .x-stat .x-stat-value { font-size: 1.6rem; font-weight: 700; font-variant-numeric: tabular-nums; color: var(--x-fg); }
        .x-stat .x-stat-icon { font-size: 1.9rem; color: #cbd5e1; }
        .x-pill { display: inline-flex; align-items: center; gap: .3rem; font-size: .78rem; font-weight: 600; padding: .2rem .6rem; border-radius: 999px; border: 1px solid transparent; white-space: nowrap; }
        .x-pill i { font-size: .72rem; }
        .x-pill-green { background: #ecfdf5; color: #047857; border-color: #a7f3d0; }
        .x-pill-red { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }
        .x-pill-amber { background: #fffbeb; color: #b45309; border-color: #fde68a; }
        .x-pill-blue { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
        .x-pill-slate { background: #f1f5f9; color: #475569; border-color: #e2e8f0; }
        .btn { transition: var(--x-transition); }
        .btn-primary { background-color: var(--x-primary); border-color: var(--x-primary); }
        .btn-primary:hover, .btn-primary:focus { background-color: var(--x-primary-dark); border-color: var(--x-primary-dark); }
        .btn-success { background-color: var(--x-accent); border-color: var(--x-accent); }
        .btn-success:hover, .btn-success:focus { background-color: var(--x-accent-dark); border-color: var(--x-accent-dark); }
        .btn.is-loading { pointer-events: none; opacity: .65; }
        .btn.is-loading::after { content: ""; display: inline-block; width: .9rem; height: .9rem; margin-left: .5rem; vertical-align: text-bottom; border: 2px solid currentColor; border-right-color: transparent; border-radius: 50%; animation: x-spin .6s linear infinite; }
        @keyframes x-spin { to { transform: rotate(360deg); } }
        a:focus-visible, button:focus-visible, input:focus-visible, select:focus-visible, textarea:focus-visible {
            outline: 3px solid rgba(51, 65, 85, .45); outline-offset: 2px;
        }
        .skip-link { position: absolute; left: -9999px; top: 0; background: #fff; color: #0f172a; padding: .5rem 1rem; z-index: 2000; border-radius: 0 0 8px 0; font-weight: 700; }
        .skip-link:focus { left: 0; }
        a, button { touch-action: manipulation; }
        a.btn, button.btn, .nav-link, .cc-tile { cursor: pointer; }
        .table thead th { background: var(--x-primary); color: #fff; border-color: var(--x-primary); font-size: .78rem; text-transform: uppercase; letter-spacing: .05em; white-space: nowrap; }
        .table td { vertical-align: middle; }
        .table-numeric { font-variant-numeric: tabular-nums; text-align: right; }
        .x-empty { text-align: center; padding: 2.5rem 1rem; color: #64748b; }
        .x-empty i { font-size: 2.2rem; display: block; margin-bottom: .75rem; color: #cbd5e1; }
        .x-spinner { display: inline-block; width: 1rem; height: 1rem; border: 2px solid #cbd5e1; border-top-color: var(--x-primary); border-radius: 50%; animation: x-spin .6s linear infinite; vertical-align: text-bottom; }
        @media (max-width: 576px) {
            .x-page-head h4 { font-size: 1.1rem; }
            .x-stat .x-stat-value { font-size: 1.3rem; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .01ms !important; transition-duration: .01ms !important; }
            .x-stat:hover { transform: none; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>
    <div id="app">
        <div class="wrapper d-flex">
            <nav id="sidebar" class="bg-dark text-white" style="min-width: 250px; min-height: 100vh;">
                <div class="sidebar-header p-3 border-bottom border-secondary">
                    <h4 class="mb-0">{{ config('app.name', 'Laravel') }}</h4>
                    <small class="text-muted">{{ __('marks.admin_panel') }}</small>
                </div>
                <ul class="nav flex-column p-3" id="admin-side-menu">
                    <li class="nav-item mb-2">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend"><span class="input-group-text bg-secondary border-secondary text-white"><i class="fa fa-search" aria-hidden="true"></i></span></div>
                            <input type="text" id="side-menu-search" class="form-control bg-secondary border-secondary text-white" placeholder="Search menu..." aria-label="Search menu" autocomplete="off">
                        </div>
                    </li>
                    <li class="nav-item mt-1 side-head">
                        <span class="text-uppercase text-muted" style="font-size: 0.7rem;">{{ __('marks.mod_main') }}</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-tachometer-alt sb-ico" aria-hidden="true"></i>{{ __('marks.dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('site.home') }}" target="_blank">
                            <i class="fa fa-external-link-alt sb-ico" aria-hidden="true"></i>{{ __('marks.view_public_site') }}
                        </a>
                    </li>
                    <li class="nav-item mt-2 side-head">
                        <span class="text-uppercase text-muted" style="font-size: 0.7rem;">{{ __('marks.mod_academic') }}</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.classes.*') ? 'active' : '' }}" href="{{ route('admin.classes.index') }}">
                            <i class="fa fa-school sb-ico" aria-hidden="true"></i>{{ __('marks.classes') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.subjects.*') ? 'active' : '' }}" href="{{ route('admin.subjects.index') }}">
                            <i class="fa fa-book sb-ico" aria-hidden="true"></i>{{ __('marks.subjects') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.exams.*') ? 'active' : '' }}" href="{{ route('admin.exams.index') }}">
                            <i class="fa fa-file-alt sb-ico" aria-hidden="true"></i>{{ __('marks.exams') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.marks.*') ? 'active' : '' }}" href="{{ route('admin.marks.index') }}">
                            <i class="fa fa-chart-bar sb-ico" aria-hidden="true"></i>{{ __('marks.marks_grades') }}
                        </a>
                    </li>
                    @php /** @var \App\Models\User $authUser */ $authUser = auth()->user(); @endphp
                    @if($authUser && ($authUser->isAdmin() || $authUser->isSuperAdmin()))
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.student-marks.*') ? 'active' : '' }}" href="{{ route('admin.student-marks.index') }}">
                            <i class="fa fa-table sb-ico" aria-hidden="true"></i>{{ __('marks.student_marks') }}
                        </a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.timetables.*') ? 'active' : '' }}" href="{{ route('admin.timetables.index') }}">
                            <i class="fa fa-clock sb-ico" aria-hidden="true"></i>{{ __('marks.timetable') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.attendance.*') ? 'active' : '' }}" href="{{ route('admin.attendance.index') }}">
                            <i class="fa fa-calendar-check sb-ico" aria-hidden="true"></i>{{ __('marks.attendance') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.homework.*') ? 'active' : '' }}" href="{{ route('admin.homework.index') }}">
                            <i class="fa fa-book-open sb-ico" aria-hidden="true"></i>{{ __('marks.hw_homework') }}
                        </a>
                    </li>
                    <li class="nav-item mt-2 side-head">
                        <span class="text-uppercase text-muted" style="font-size: 0.7rem;">{{ __('marks.mod_library') }}</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.books.*') ? 'active' : '' }}" href="{{ route('admin.books.index') }}">
                            <i class="fa fa-book-reader sb-ico" aria-hidden="true"></i>{{ __('marks.books') }}
                        </a>
                    </li>
                    <li class="nav-item mt-2 side-head">
                        <span class="text-uppercase text-muted" style="font-size: 0.7rem;">{{ __('marks.mod_finance') }}</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.payments.*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
                            <i class="fa fa-credit-card sb-ico" aria-hidden="true"></i>{{ __('marks.payments') }}
                        </a>
                    </li>
                    <li class="nav-item mt-2 side-head">
                        <span class="text-uppercase text-muted" style="font-size: 0.7rem;">{{ __('marks.mod_content') }}</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.notices.*') ? 'active' : '' }}" href="{{ route('admin.notices.index') }}">
                            <i class="fa fa-bullhorn sb-ico" aria-hidden="true"></i>{{ __('marks.notices') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.study-materials.*') ? 'active' : '' }}" href="{{ route('admin.study-materials.index') }}">
                            <i class="fa fa-folder-open sb-ico" aria-hidden="true"></i>{{ __('marks.study_materials') }}
                        </a>
                    </li>
                    <li class="nav-item mt-2 side-head">
                        <span class="text-uppercase text-muted" style="font-size: 0.7rem;">{{ __('marks.mod_admissions') }}</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.admissions.*') ? 'active' : '' }}" href="{{ route('admin.admissions.applications') }}">
                            <i class="fa fa-file-signature sb-ico" aria-hidden="true"></i>{{ __('marks.applications') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.admissions.*') ? 'active' : '' }}" href="{{ route('admin.admissions.inbox') }}">
                            <i class="fa fa-inbox sb-ico" aria-hidden="true"></i>{{ __('marks.contact_inbox') }}
                        </a>
                    </li>
                    <li class="nav-item mt-2 side-head">
                        <span class="text-uppercase text-muted" style="font-size: 0.7rem;">{{ __('marks.mod_office') }}</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.front-office.*') ? 'active' : '' }}" href="{{ route('admin.front-office.enquiries') }}">
                            <i class="fa fa-question-circle sb-ico" aria-hidden="true"></i>{{ __('marks.fo_enquiries') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.front-office.*') ? 'active' : '' }}" href="{{ route('admin.front-office.visitors') }}">
                            <i class="fa fa-walking sb-ico" aria-hidden="true"></i>{{ __('marks.fo_visitors') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.front-office.*') ? 'active' : '' }}" href="{{ route('admin.front-office.complaints') }}">
                            <i class="fa fa-exclamation-circle sb-ico" aria-hidden="true"></i>{{ __('marks.fo_complaints') }}
                        </a>
                    </li>
                    <li class="nav-item mt-2 side-head">
                        <span class="text-uppercase text-muted" style="font-size: 0.7rem;">{{ __('marks.mod_leave') }}</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.leaves.*') ? 'active' : '' }}" href="{{ route('admin.leaves.index') }}">
                            <i class="fa fa-calendar-times sb-ico" aria-hidden="true"></i>{{ __('marks.lv_title') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.leave-types.*') ? 'active' : '' }}" href="{{ route('admin.leave-types.index') }}">
                            <i class="fa fa-list-alt sb-ico" aria-hidden="true"></i>{{ __('marks.lv_types') }}
                        </a>
                    </li>
                    <li class="nav-item mt-2 side-head">
                        <span class="text-uppercase text-muted" style="font-size: 0.7rem;">{{ __('marks.mod_inventory') }}</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.inventory.dashboard') ? 'active' : '' }}" href="{{ route('admin.inventory.dashboard') }}">
                            <i class="fa fa-boxes sb-ico" aria-hidden="true"></i>{{ __('marks.inventory_dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.inventory.items.*') ? 'active' : '' }}" href="{{ route('admin.inventory.items.index') }}">
                            <i class="fa fa-cubes sb-ico" aria-hidden="true"></i>{{ __('marks.inv_items') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.inventory.items.map') ? 'active' : '' }}" href="{{ route('admin.inventory.items.map') }}">
                            <i class="fa fa-th-large sb-ico" aria-hidden="true"></i>{{ __('marks.section_map') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.inventory.sections.*') ? 'active' : '' }}" href="{{ route('admin.inventory.sections.index') }}">
                            <i class="fa fa-users sb-ico" aria-hidden="true"></i>{{ __('marks.sections_officers') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.inventory.checks.*') ? 'active' : '' }}" href="{{ route('admin.inventory.checks.index') }}">
                            <i class="fa fa-clipboard-check sb-ico" aria-hidden="true"></i>{{ __('marks.stock_checks') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.inventory.removals.*') ? 'active' : '' }}" href="{{ route('admin.inventory.removals.index') }}">
                            <i class="fa fa-trash-alt sb-ico" aria-hidden="true"></i>{{ __('marks.removals') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.inventory.qr*') ? 'active' : '' }}" href="{{ route('admin.inventory.qr') }}">
                            <i class="fa fa-qrcode sb-ico" aria-hidden="true"></i>{{ __('marks.qr_labels') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.inventory.scanner*') ? 'active' : '' }}" href="{{ route('admin.inventory.scanner') }}">
                            <i class="fa fa-camera sb-ico" aria-hidden="true"></i>{{ __('marks.qr_scanner') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.inventory.bulk*') ? 'active' : '' }}" href="{{ route('admin.inventory.bulk') }}">
                            <i class="fa fa-file-upload sb-ico" aria-hidden="true"></i>{{ __('marks.bulk_import') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.inventory.reports.*') ? 'active' : '' }}" href="{{ route('admin.inventory.reports.index') }}">
                            <i class="fa fa-chart-pie sb-ico" aria-hidden="true"></i>{{ __('marks.reports') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.inventory.school.*') ? 'active' : '' }}" href="{{ route('admin.inventory.school.edit') }}">
                            <i class="fa fa-school sb-ico" aria-hidden="true"></i>{{ __('marks.school_profile') }}
                        </a>
                    </li>
                    <li class="nav-item mt-2 side-head">
                        <span class="text-uppercase text-muted" style="font-size: 0.7rem;">{{ __('marks.mod_system') }}</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="fa fa-users-cog sb-ico" aria-hidden="true"></i>{{ __('marks.users_management') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Route::is('admin.settings') ? 'active' : '' }}" href="{{ route('admin.settings') }}">
                            <i class="fa fa-cog sb-ico" aria-hidden="true"></i>{{ __('marks.settings') }}
                        </a>
                    </li>
                    <li class="nav-item" id="side-no-results" style="display:none;">
                        <span class="text-muted small px-2">{{ __('marks.dash_no_match') }}</span>
                    </li>
                </ul>
            </nav>
            <div id="content" class="flex-grow-1">
                <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarContent">
                            <ul class="navbar-nav ms-auto">
                                @auth
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                            {{ __('marks.language_switcher') }}
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">{{ __('marks.english') }}</a>
                                            <a class="dropdown-item" href="{{ route('lang.switch', 'ta') }}">{{ __('marks.tamil') }}</a>
                                            <a class="dropdown-item" href="{{ route('lang.switch', 'si') }}">{{ __('marks.sinhala') }}</a>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                            {{ auth()->user()->name }}
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                 {{ __('marks.logout') }}
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </div>
                                    </li>
                                @endauth
                            </ul>
                        </div>
                    </div>
                </nav>
                <main class="py-4" id="main-content" tabindex="-1">
                    <div class="container-fluid">
                        @include('partials.flash-messages')
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    </div>
    @stack('scripts')
    <script>
    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (form.method.toUpperCase() !== 'POST' || form.dataset.noLoading !== undefined) return;
        var btn = form.querySelector('button[type="submit"]:not([disabled])');
        if (btn) { btn.classList.add('is-loading'); btn.disabled = true; }
    });
    (function () {
        var box = document.getElementById('side-menu-search');
        if (!box) return;
        box.addEventListener('input', function () {
            var q = box.value.trim().toLowerCase(), shown = 0;
            document.querySelectorAll('#admin-side-menu > li.nav-item').forEach(function (li) {
                if (li.querySelector('#side-menu-search') || li.id === 'side-no-results' || li.classList.contains('side-head')) return;
                var hit = !q || li.textContent.toLowerCase().indexOf(q) !== -1;
                li.style.display = hit ? '' : 'none';
                if (hit) shown++;
            });
            document.querySelectorAll('#admin-side-menu > li.side-head').forEach(function (h) {
                var vis = false, el = h.nextElementSibling;
                while (el && !el.classList.contains('side-head') && el.id !== 'side-no-results') {
                    if (el.style.display !== 'none' && el.querySelector && el.querySelector('.nav-link')) { vis = true; break; }
                    el = el.nextElementSibling;
                }
                h.style.display = vis || !q ? '' : 'none';
            });
            document.getElementById('side-no-results').style.display = (shown === 0 && q) ? '' : 'none';
        });
    })();
    </script>
</body>
</html>