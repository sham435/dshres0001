@php
$admTabs = [
    ['marks.applications', 'admin.admissions.applications', 'fa-file-signature'],
    ['marks.contact_inbox', 'admin.admissions.inbox*', 'fa-inbox'],
];
@endphp
<div class="card x-card mb-4">
    <div class="card-body py-2">
        <ul class="nav nav-pills flex-wrap" role="navigation" aria-label="Admissions modules">
            @foreach($admTabs as [$key, $pattern, $icon])
                @php
                    $url = $pattern === 'admin.admissions.applications'
                        ? route('admin.admissions.applications')
                        : route('admin.admissions.inbox');
                @endphp
                <li class="nav-item">
                    <a class="nav-link {{ Route::is($pattern) || ($pattern === 'admin.admissions.applications' && Route::is('admin.admissions.show')) ? 'active' : '' }}" href="{{ $url }}"><i class="fa {{ $icon }}" aria-hidden="true"></i> {{ __($key) }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
