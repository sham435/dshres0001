@extends('layouts.admin')

@section('title', __('marks.dashboard'))
@section('content')
@php
    $spark = function ($points, $color) {
        $max = max(1, max($points));
        $n = count($points);
        $d = [];
        foreach ($points as $i => $v) {
            $x = $n > 1 ? round($i / ($n - 1) * 200) : 200;
            $y = round(45 - ($v / $max) * 38);
            $d[] = ($i === 0 ? 'M' : 'L') . $x . ',' . $y;
        }
        $line = implode(' ', $d);
        $gid = 'g' . substr(md5($line . $color), 0, 8);
        return '<svg class="cc-spark" viewBox="0 0 200 50" preserveAspectRatio="none" aria-hidden="true"><path d="' . $line . '" fill="none" stroke="' . $color . '" stroke-width="2.5"/><path d="' . $line . ' V50 H0Z" fill="url(#' . $gid . ')"/><defs><linearGradient id="' . $gid . '" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="' . $color . '"/><stop offset="1" stop-color="transparent"/></linearGradient></defs></svg>';
    };
    $hubTiles = [
        ['marks.dashboard', 'admin.dashboard', 'fa-tachometer-alt', 'academic', 'var(--grad-sky)'],
        ['marks.classes', 'admin.classes.index', 'fa-school', 'academic', 'var(--grad-violet)'],
        ['marks.subjects', 'admin.subjects.index', 'fa-book', 'academic', 'var(--grad-teal)'],
        ['marks.exams', 'admin.exams.index', 'fa-file-alt', 'academic', 'var(--grad-amber)'],
        ['marks.marks_grades', 'admin.marks.index', 'fa-chart-bar', 'academic', 'var(--grad-emerald)'],
        ['marks.attendance', 'admin.attendance.index', 'fa-calendar-check', 'academic', 'var(--grad-copper)'],
        ['marks.hw_homework', 'admin.homework.index', 'fa-book-open', 'academic', 'var(--grad-pink)'],
        ['marks.timetable', 'admin.timetables.index', 'fa-clock', 'academic', 'var(--grad-slate)'],
        ['marks.books', 'admin.books.index', 'fa-book-reader', 'library', 'var(--grad-orange)'],
        ['marks.payments', 'admin.payments.index', 'fa-credit-card', 'finance', 'var(--grad-emerald)'],
        ['marks.notices', 'admin.notices.index', 'fa-bullhorn', 'content', 'var(--grad-rose)'],
        ['marks.study_materials', 'admin.study-materials.index', 'fa-folder-open', 'content', 'var(--grad-sky)'],
        ['marks.applications', 'admin.admissions.applications', 'fa-file-signature', 'admissions', 'var(--grad-violet)'],
        ['marks.contact_inbox', 'admin.admissions.inbox', 'fa-inbox', 'admissions', 'var(--grad-teal)'],
        ['marks.fo_enquiries', 'admin.front-office.enquiries', 'fa-question-circle', 'office', 'var(--grad-amber)'],
        ['marks.fo_visitors', 'admin.front-office.visitors', 'fa-walking', 'office', 'var(--grad-lime)'],
        ['marks.fo_complaints', 'admin.front-office.complaints', 'fa-exclamation-circle', 'office', 'var(--grad-rose)'],
        ['marks.lv_title', 'admin.leaves.index', 'fa-calendar-times', 'office', 'var(--grad-copper)'],
        ['marks.inventory_dashboard', 'admin.inventory.dashboard', 'fa-boxes', 'inventory', 'var(--grad-sky)'],
        ['marks.inv_items', 'admin.inventory.items.index', 'fa-cubes', 'inventory', 'var(--grad-emerald)'],
        ['marks.stock_checks', 'admin.inventory.checks.index', 'fa-clipboard-check', 'inventory', 'var(--grad-violet)'],
        ['marks.reports', 'admin.inventory.reports.index', 'fa-chart-pie', 'inventory', 'var(--grad-teal)'],
        ['marks.users_management', 'admin.users.index', 'fa-users', 'system', 'var(--grad-slate)'],
        ['marks.settings', 'admin.settings', 'fa-cog', 'system', 'var(--grad-pink)'],
    ];
    $hubCats = ['all' => 'All', 'academic' => 'marks.mod_academic', 'library' => 'marks.mod_library', 'finance' => 'marks.mod_finance', 'content' => 'marks.mod_content', 'admissions' => 'marks.mod_admissions', 'office' => 'marks.mod_office', 'inventory' => 'marks.mod_inventory', 'system' => 'marks.mod_system'];
@endphp
<div class="cc-wrap">
    <div class="cc-head">
        <div>
            <span class="cc-eyebrow"><i class="fa fa-satellite-dish" aria-hidden="true"></i> {{ __('marks.dash_eyebrow') }}</span>
            <h1>{{ $greeting ?? '' }}, {{ strtok($user->name ?? 'Admin', ' ') }}</h1>
            <p>{{ __('marks.dash_sub') }} — <span id="cc-clock">{{ date('l, d F Y') }}</span></p>
        </div>
        <div class="cc-ticker" aria-label="{{ __('marks.dash_live_feed') }}">
            <div class="cc-ticker-label"><i class="fa fa-satellite-dish" aria-hidden="true"></i> {{ __('marks.dash_live_feed') }}</div>
            <div class="cc-ticker-track"><div class="cc-ticker-move">
                @forelse($activity ?? [] as $ev)
                    <span class="cc-ticker-item"><i class="fa {{ $ev['icon'] }}" aria-hidden="true"></i>{{ $ev['text'] }} <span>({{ $ev['meta'] }})</span></span>
                @empty
                    <span class="cc-ticker-item">{{ __('marks.dash_no_activity') }}</span>
                @endforelse
            </div></div>
        </div>
    </div>

    <div class="cc-kpis">
        @php
            $kpis = [
                ['marks.dash_students', $totalStudents ?? 0, '', 'fa-users', 'var(--grad-sky)', collect($studentTrend ?? [])->pluck('count')->all()],
                ['marks.dash_teachers', $totalTeachers ?? 0, '', 'fa-chalkboard-teacher', 'var(--grad-emerald)', []],
                ['marks.dash_fees_collected', $feesCollected ?? 0, '', 'fa-money-bill-wave', 'var(--grad-amber)', collect($feeTrend ?? [])->pluck('amount')->all()],
                ['marks.dash_attendance_today', ($todayPct ?? 0) . '%', '', 'fa-calendar-check', 'var(--grad-violet)', collect($attTrend ?? [])->pluck('pct')->all()],
                ['marks.dash_pending_dues', $feesDue ?? 0, '', 'fa-file-invoice-dollar', 'var(--grad-rose)', []],
            ];
        @endphp
        @foreach($kpis as [$key, $val, $suffix, $icon, $grad, $trend])
            <div class="cc-kpi">
                <div class="cc-kpi-top"><div class="cc-kpi-ico" style="background:{{ $grad }};"><i class="fa {{ $icon }}" aria-hidden="true"></i></div></div>
                <div class="cc-kpi-val" data-count="{{ is_numeric($val) && floor((float) $val) == (float) $val ? $val : 0 }}">{{ is_numeric($val) ? number_format($val) : $val }}</div>
                <div class="cc-kpi-label">{{ __($key) }}</div>
                @if(count($trend) > 1)
                    {!! $spark($trend, '#0284c7') !!}
                @endif
            </div>
        @endforeach
    </div>

    <div class="cc-grid-2">
        <div class="cc-card">
            <div class="cc-card-hd">
                <div><div class="cc-card-title"><i class="fa fa-th" aria-hidden="true"></i>{{ __('marks.dash_hub') }}</div>
                <div class="cc-card-sub">{{ __('marks.dash_hub_sub', ['count' => count($hubTiles)]) }}</div></div>
            </div>
            <div class="cc-hub-controls">
                <div class="cc-search"><i class="fa fa-search" aria-hidden="true"></i><input id="cc-hubSearch" type="text" placeholder="{{ __('marks.dash_hub_search') }}" aria-label="{{ __('marks.dash_hub_search') }}"></div>
                <div class="cc-hubtabs" id="cc-hubTabs">
                    @foreach($hubCats as $cat => $label)
                        <button type="button" class="cc-tab{{ $cat === 'all' ? ' active' : '' }}" data-cat="{{ $cat }}">{{ $cat === 'all' ? 'All' : __($label) }}</button>
                    @endforeach
                </div>
            </div>
            <div class="cc-tiles" id="cc-hubGrid">
                @foreach($hubTiles as [$key, $route, $icon, $cat, $grad])
                    <a href="{{ route($route) }}" class="cc-tile" data-name="{{ strtolower(__($key)) }}" data-cat="{{ $cat }}">
                        <div class="cc-tile-ico" style="background:{{ $grad }};"><i class="fa {{ $icon }}" aria-hidden="true"></i></div>
                        <div class="cc-tile-name">{{ __($key) }}</div>
                    </a>
                @endforeach
                <div class="cc-noresults" id="cc-noResults" style="display:none;">{{ __('marks.dash_no_match') }}</div>
            </div>
        </div>
        <div class="cc-card">
            <div class="cc-card-hd"><div><div class="cc-card-title">{{ __('marks.dash_activity') }}</div><div class="cc-card-sub">{{ __('marks.dash_activity_sub') }}</div></div></div>
            <div class="cc-activity">
                @forelse($activity ?? [] as $ev)
                    <div class="cc-act"><div class="cc-act-ico tone-{{ $ev['tone'] }}"><i class="fa {{ $ev['icon'] }}" aria-hidden="true"></i></div>
                        <div class="cc-act-body"><div class="cc-act-text">{{ $ev['text'] }}</div><div class="cc-act-meta">{{ $ev['meta'] }}</div></div>
                        <div class="cc-act-time">{{ $ev['at'] ? $ev['at']->format('d M H:i') : '' }}</div></div>
                @empty
                    <div class="x-empty">{{ __('marks.dash_no_activity') }}</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="cc-grid-2">
        <div class="cc-card">
            <div class="cc-card-hd"><div><div class="cc-card-title">{{ __('marks.dash_fee_chart') }}</div><div class="cc-card-sub">{{ __('marks.dash_fee_sub') }}</div></div></div>
            <div style="position:relative;height:240px;"><canvas id="cc-feeChart" aria-label="{{ __('marks.dash_fee_chart') }}"></canvas></div>
        </div>
        <div class="cc-card">
            <div class="cc-card-hd"><div><div class="cc-card-title">{{ __('marks.dash_att_donut') }}</div><div class="cc-card-sub">{{ __('marks.dash_sub') }}</div></div></div>
            @php
                $pp = $todayPresent ?? 0; $pa = $todayAbsent ?? 0; $pt = max(1, $pp + $pa);
                $off = 345.6 * (1 - $pp / $pt);
            @endphp
            <div class="cc-donut-wrap">
                <div class="cc-donut"><svg viewBox="0 0 130 130" role="img" aria-label="{{ __('marks.dash_att_donut') }}"><circle class="cc-donut-bg" cx="65" cy="65" r="55"></circle><circle class="cc-donut-fill" cx="65" cy="65" r="55" style="stroke-dashoffset:{{ round($off, 1) }};"></circle></svg>
                    <div class="cc-donut-center"><div class="cc-donut-val">{{ $pt > 1 || $pp > 0 ? round($pp / $pt * 100) : 0 }}%</div><div class="cc-donut-lbl">{{ __('marks.att_present') }}</div></div></div>
                <div class="cc-donut-legend">
                    <div><span class="cc-dot" style="background:var(--x-accent);"></span>{{ __('marks.att_present') }}<strong>{{ number_format($pp) }}</strong></div>
                    <div><span class="cc-dot" style="background:var(--x-danger);"></span>{{ __('marks.att_absent') }}<strong>{{ number_format($pa) }}</strong></div>
                </div>
            </div>
        </div>
    </div>

    <div class="cc-grid-3">
        <div class="cc-card">
            <div class="cc-card-hd"><div class="cc-card-title"><i class="fa fa-file-invoice-dollar" aria-hidden="true"></i>{{ __('marks.dash_dues') }}</div><a href="{{ route('admin.payments.index') }}" class="cc-link">{{ __('marks.dash_view_all') }}</a></div>
            @forelse($duePayments ?? [] as $p)
                <div class="cc-row"><div class="cc-av" style="background:var(--grad-amber);">{{ strtoupper(substr($p->student->name ?? '?', 0, 2)) }}</div>
                    <div class="cc-row-main"><div class="cc-row-name">{{ $p->student->name ?? 'N/A' }}</div><div class="cc-row-sub">{{ $p->fee_type }} · {{ $p->due_date }}</div></div>
                    <div class="cc-row-end"><strong>{{ number_format($p->amount - $p->paid_amount, 2) }}</strong></div></div>
            @empty
                <div class="x-empty">{{ __('marks.dash_empty') }}</div>
            @endforelse
        </div>
        <div class="cc-card">
            <div class="cc-card-hd"><div class="cc-card-title"><i class="fa fa-file-signature" aria-hidden="true"></i>{{ __('marks.dash_admissions') }}</div><a href="{{ route('admin.admissions.applications') }}" class="cc-link">{{ __('marks.dash_view_all') }}</a></div>
            @forelse($recentAdmissions ?? [] as $a)
                <div class="cc-row"><div class="cc-av" style="background:var(--grad-violet);">{{ strtoupper(substr($a->first_name, 0, 1) . substr($a->last_name, 0, 1)) }}</div>
                    <div class="cc-row-main"><div class="cc-row-name">{{ $a->first_name }} {{ $a->last_name }}</div><div class="cc-row-sub">{{ $a->email }}</div></div>
                    <span class="x-pill x-pill-amber">{{ ucfirst($a->status) }}</span></div>
            @empty
                <div class="x-empty">{{ __('marks.dash_empty') }}</div>
            @endforelse
        </div>
        <div class="cc-card">
            <div class="cc-card-hd"><div class="cc-card-title"><i class="fa fa-envelope" aria-hidden="true"></i>{{ __('marks.dash_messages') }}</div><a href="{{ route('admin.admissions.inbox') }}" class="cc-link">{{ __('marks.dash_view_all') }}</a></div>
            @forelse($recentMessages ?? [] as $m)
                <div class="cc-row"><div class="cc-av" style="background:var(--grad-teal);">{{ strtoupper(substr($m->name, 0, 2)) }}</div>
                    <div class="cc-row-main"><div class="cc-row-name">{{ $m->subject }}</div><div class="cc-row-sub">{{ $m->name }}</div></div></div>
            @empty
                <div class="x-empty">{{ __('marks.dash_empty') }}</div>
            @endforelse
        </div>
    </div>

    <div class="cc-grid-2">
        <div class="cc-card">
            <div class="cc-card-hd"><div class="cc-card-title"><i class="fa fa-calendar-alt" aria-hidden="true"></i>{{ __('marks.dash_exams') }}</div></div>
            @forelse($upcomingExams ?? [] as $e)
                <div class="cc-row"><div class="cc-av" style="background:var(--grad-sky);"><i class="fa fa-file-alt" aria-hidden="true"></i></div>
                    <div class="cc-row-main"><div class="cc-row-name">{{ $e->name }}</div><div class="cc-row-sub">{{ $e->class->name ?? '' }}</div></div>
                    <span class="x-pill x-pill-blue">{{ $e->exam_date }}</span></div>
            @empty
                <div class="x-empty">{{ __('marks.dash_empty') }}</div>
            @endforelse
        </div>
        <div class="cc-card">
            <div class="cc-card-hd"><div class="cc-card-title"><i class="fa fa-heartbeat" aria-hidden="true"></i>{{ __('marks.dash_health') }}</div></div>
            <div class="cc-health">
                <div><strong>{{ __('marks.dash_db') }}</strong><span class="x-pill x-pill-green"><i class="fa fa-check" aria-hidden="true"></i>{{ __('marks.dash_online') }}</span></div>
                <div><strong>{{ __('marks.dash_students') }}</strong><span>{{ number_format($totalStudents ?? 0) }}</span></div>
                <div><strong>{{ __('marks.dash_teachers') }}</strong><span>{{ number_format($totalTeachers ?? 0) }}</span></div>
                <div><strong>{{ __('marks.dash_pending_dues') }}</strong><span>{{ number_format($dueCount ?? 0) }}</span></div>
                <div><strong>{{ __('marks.dash_pending_leaves') }}</strong><span>{{ number_format($pendingLeaves ?? 0) }}</span></div>
                <div><strong>{{ __('marks.dash_new_enquiries') }}</strong><span>{{ number_format($newEnquiries ?? 0) }}</span></div>
                <div><strong>{{ __('marks.dash_open_complaints') }}</strong><span>{{ number_format($openComplaints ?? 0) }}</span></div>
                <div><strong>{{ __('marks.dash_version') }}</strong><span>Laravel {{ app()->version() }}</span></div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.cc-wrap { --grad-sky: linear-gradient(135deg,#0ea5e9,#0369a1); --grad-emerald: linear-gradient(135deg,#10b981,#047857); --grad-amber: linear-gradient(135deg,#f59e0b,#b45309); --grad-violet: linear-gradient(135deg,#8b5cf6,#5b21b6); --grad-rose: linear-gradient(135deg,#f43f5e,#9f1239); --grad-teal: linear-gradient(135deg,#14b8a6,#0f766e); --grad-copper: linear-gradient(135deg,#d97706,#92400e); --grad-pink: linear-gradient(135deg,#ec4899,#9d174d); --grad-orange: linear-gradient(135deg,#fb923c,#c2410c); --grad-lime: linear-gradient(135deg,#84cc16,#3f6212); --grad-slate: linear-gradient(135deg,#64748b,#334155); }
.cc-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.25rem; }
.cc-eyebrow { display: inline-flex; align-items: center; gap: .4rem; font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--x-accent); }
.cc-head h1 { font-size: 1.7rem; font-weight: 800; margin: .25rem 0; }
.cc-head p { color: #64748b; margin: 0; }
.cc-ticker { display: flex; align-items: center; gap: .6rem; background: #fff; border: 1px solid var(--x-border); border-radius: var(--x-radius); box-shadow: var(--x-shadow); padding: .5rem .8rem; max-width: 460px; min-width: 0; }
.cc-ticker-label { display: inline-flex; align-items: center; gap: .35rem; font-size: .72rem; font-weight: 800; color: var(--x-danger); white-space: nowrap; }
.cc-ticker-track { overflow: hidden; flex: 1; min-width: 0; }
.cc-ticker-move { display: inline-flex; gap: 2rem; white-space: nowrap; animation: cc-tick 30s linear infinite; }
.cc-ticker:hover .cc-ticker-move { animation-play-state: paused; }
.cc-ticker-item { font-size: .8rem; color: #334155; }
.cc-ticker-item span { color: #94a3b8; }
@keyframes cc-tick { from { transform: translateX(0); } to { transform: translateX(-50%); } }
.cc-kpis { display: grid; grid-template-columns: repeat(5, 1fr); gap: 1rem; margin-bottom: 1.25rem; }
.cc-kpi { background: #fff; border: 1px solid var(--x-border); border-radius: var(--x-radius); box-shadow: var(--x-shadow); padding: 1rem; overflow: hidden; }
.cc-kpi-ico { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.1rem; margin-bottom: .6rem; }
.cc-kpi-val { font-size: 1.5rem; font-weight: 800; font-variant-numeric: tabular-nums; }
.cc-kpi-label { font-size: .78rem; color: #64748b; font-weight: 600; }
.cc-spark { width: 100%; height: 44px; margin-top: .4rem; }
.cc-grid-2 { display: grid; grid-template-columns: 1.4fr 1fr; gap: 1rem; margin-bottom: 1.25rem; }
.cc-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.25rem; }
.cc-card { background: #fff; border: 1px solid var(--x-border); border-radius: var(--x-radius); box-shadow: var(--x-shadow); padding: 1.1rem; min-width: 0; }
.cc-card-hd { display: flex; justify-content: space-between; align-items: flex-start; gap: .6rem; margin-bottom: .8rem; }
.cc-card-title { font-weight: 800; font-size: 1rem; display: flex; align-items: center; gap: .45rem; }
.cc-card-title i { color: var(--x-accent); font-size: .85rem; }
.cc-card-sub { font-size: .78rem; color: #64748b; }
.cc-link { font-size: .8rem; font-weight: 700; color: var(--x-primary); white-space: nowrap; }
.cc-hub-controls { display: flex; flex-direction: column; gap: .6rem; margin-bottom: .8rem; }
.cc-search { display: flex; align-items: center; gap: .5rem; border: 1px solid var(--x-border); border-radius: 8px; padding: .35rem .6rem; }
.cc-search input { border: 0; outline: 0; flex: 1; font-size: .85rem; background: transparent; }
.cc-search i { color: #94a3b8; }
.cc-hubtabs { display: flex; flex-wrap: wrap; gap: .35rem; }
.cc-tab { border: 1px solid var(--x-border); background: #fff; border-radius: 999px; padding: .2rem .7rem; font-size: .75rem; font-weight: 600; color: #475569; cursor: pointer; transition: var(--x-transition); }
.cc-tab.active, .cc-tab:hover { background: var(--x-primary); border-color: var(--x-primary); color: #fff; }
.cc-tiles { display: grid; grid-template-columns: repeat(auto-fill, minmax(96px, 1fr)); gap: .7rem; max-height: 380px; overflow-y: auto; }
.cc-tile { display: flex; flex-direction: column; align-items: center; gap: .4rem; padding: .8rem .4rem; border: 1px solid var(--x-border); border-radius: 10px; text-decoration: none !important; transition: var(--x-transition); }
.cc-tile:hover { transform: translateY(-2px); box-shadow: var(--x-shadow); }
.cc-tile-ico { width: 38px; height: 38px; border-radius: 11px; display: flex; align-items: center; justify-content: center; color: #fff; }
.cc-tile-name { font-size: .72rem; font-weight: 600; color: #334155; text-align: center; line-height: 1.25; }
.cc-noresults { grid-column: 1/-1; text-align: center; color: #94a3b8; padding: 1.2rem; }
.cc-activity { display: flex; flex-direction: column; gap: .7rem; max-height: 480px; overflow-y: auto; }
.cc-act { display: flex; gap: .6rem; align-items: flex-start; }
.cc-act-ico { width: 32px; height: 32px; border-radius: 9px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: .8rem; flex-shrink: 0; }
.tone-green { background: var(--grad-emerald); } .tone-blue { background: var(--grad-sky); } .tone-amber { background: var(--grad-amber); }
.cc-act-body { flex: 1; min-width: 0; }
.cc-act-text { font-size: .82rem; font-weight: 700; }
.cc-act-meta { font-size: .75rem; color: #64748b; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.cc-act-time { font-size: .72rem; color: #94a3b8; white-space: nowrap; }
.cc-row { display: flex; gap: .6rem; align-items: center; padding: .5rem 0; border-bottom: 1px solid var(--x-muted); }
.cc-row:last-child { border-bottom: 0; }
.cc-av { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: .72rem; font-weight: 800; flex-shrink: 0; }
.cc-row-main { flex: 1; min-width: 0; }
.cc-row-name { font-size: .82rem; font-weight: 700; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.cc-row-sub { font-size: .73rem; color: #64748b; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.cc-row-end { text-align: right; }
.cc-donut-wrap { display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; }
.cc-donut { position: relative; width: 150px; height: 150px; flex-shrink: 0; }
.cc-donut-bg { fill: none; stroke: var(--x-muted); stroke-width: 14; }
.cc-donut-fill { fill: none; stroke: var(--x-accent); stroke-width: 14; stroke-linecap: round; stroke-dasharray: 345.6; transform: rotate(-90deg); transform-origin: center; }
.cc-donut-center { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; }
.cc-donut-val { font-size: 1.4rem; font-weight: 800; }
.cc-donut-lbl { font-size: .72rem; color: #64748b; }
.cc-donut-legend { display: flex; flex-direction: column; gap: .5rem; font-size: .82rem; }
.cc-donut-legend > div { display: flex; align-items: center; gap: .45rem; }
.cc-donut-legend strong { margin-left: auto; }
.cc-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
.cc-health { display: flex; flex-direction: column; }
.cc-health > div { display: flex; justify-content: space-between; align-items: center; padding: .5rem 0; border-bottom: 1px solid var(--x-muted); font-size: .85rem; }
.cc-health > div:last-child { border-bottom: 0; }
@media (max-width: 1100px) { .cc-kpis { grid-template-columns: repeat(3, 1fr); } .cc-grid-2, .cc-grid-3 { grid-template-columns: 1fr; } }
@media (max-width: 640px) { .cc-kpis { grid-template-columns: repeat(2, 1fr); } .cc-head h1 { font-size: 1.3rem; } .cc-ticker { max-width: 100%; } }
@media (prefers-reduced-motion: reduce) { .cc-ticker-move { animation: none; } }
</style>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
(function () {
    var clock = document.getElementById('cc-clock');
    if (clock) setInterval(function () {
        clock.textContent = new Date().toLocaleDateString(undefined, { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' }) + ' · ' + new Date().toLocaleTimeString();
    }, 1000);
    document.querySelectorAll('.cc-kpi-val[data-count]').forEach(function (el) {
        var target = parseFloat(el.getAttribute('data-count')) || 0;
        if (target <= 0) return;
        var start = performance.now(), dur = 1400;
        function tick(now) {
            var p = Math.min((now - start) / dur, 1);
            el.textContent = Math.round(target * (1 - Math.pow(1 - p, 4))).toLocaleString();
            if (p < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    });
    var grid = document.getElementById('cc-hubGrid'), search = document.getElementById('cc-hubSearch'), tabs = document.getElementById('cc-hubTabs'), empty = document.getElementById('cc-noResults'), cat = 'all';
    function filterHub() {
        if (!grid) return;
        var q = (search.value || '').trim().toLowerCase(), shown = 0;
        grid.querySelectorAll('.cc-tile').forEach(function (t) {
            var vis = (cat === 'all' || t.getAttribute('data-cat') === cat) && (!q || (t.getAttribute('data-name') || '').indexOf(q) !== -1);
            t.style.display = vis ? '' : 'none';
            if (vis) shown++;
        });
        if (empty) empty.style.display = shown === 0 ? '' : 'none';
    }
    if (search) search.addEventListener('input', filterHub);
    if (tabs) tabs.addEventListener('click', function (e) {
        var b = e.target.closest('.cc-tab'); if (!b) return;
        cat = b.getAttribute('data-cat');
        tabs.querySelectorAll('.cc-tab').forEach(function (x) { x.classList.remove('active'); });
        b.classList.add('active');
        filterHub();
    });
    document.addEventListener('keydown', function (e) {
        var typing = /^(INPUT|TEXTAREA)$/.test(document.activeElement && document.activeElement.tagName);
        if (e.key === '/' && !typing && search) { e.preventDefault(); search.focus(); }
    });
    var feeEl = document.getElementById('cc-feeChart');
    if (feeEl && window.Chart) {
        new Chart(feeEl, { type: 'line',
            data: { labels: @json(collect($feeTrend ?? [])->pluck('label')), datasets: [{ label: '{{ __('marks.dash_fee_chart') }}', data: @json(collect($feeTrend ?? [])->pluck('value')), borderColor: '#059669', backgroundColor: 'rgba(5,150,105,0.15)', borderWidth: 2, fill: true, tension: 0.35, pointRadius: 3 }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } } } } });
    }
})();
</script>
@endpush
@endsection
