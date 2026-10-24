@php
$foTabs = [
    ['marks.fo_enquiries', 'admin.front-office.enquiries*', 'fa-question-circle'],
    ['marks.fo_visitors', 'admin.front-office.visitors*', 'fa-walking'],
    ['marks.fo_complaints', 'admin.front-office.complaints*', 'fa-exclamation-circle'],
];
@endphp
<div class="card x-card mb-4">
    <div class="card-body py-2">
        <ul class="nav nav-pills flex-wrap" role="navigation" aria-label="Front office modules">
            @foreach($foTabs as [$key, $pattern, $icon])
                @php
                    $url = $pattern === 'admin.front-office.enquiries*' ? route('admin.front-office.enquiries')
                        : ($pattern === 'admin.front-office.visitors*' ? route('admin.front-office.visitors') : route('admin.front-office.complaints'));
                @endphp
                <li class="nav-item">
                    <a class="nav-link {{ Route::is($pattern) ? 'active' : '' }}" href="{{ $url }}"><i class="fa {{ $icon }}" aria-hidden="true"></i> {{ __($key) }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
