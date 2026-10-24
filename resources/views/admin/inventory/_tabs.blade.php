@php
$invTabs = [
    ['marks.inventory_dashboard', 'admin.inventory.dashboard', 'fa-tachometer-alt'],
    ['marks.inv_items', 'admin.inventory.items.*', 'fa-boxes'],
    ['marks.section_map', 'admin.inventory.items.map', 'fa-th-large'],
    ['marks.sections_officers', 'admin.inventory.sections.*', 'fa-users'],
    ['marks.stock_checks', 'admin.inventory.checks.*', 'fa-clipboard-check'],
    ['marks.removals', 'admin.inventory.removals.*', 'fa-trash-alt'],
    ['marks.qr_labels', 'admin.inventory.qr*', 'fa-qrcode'],
    ['marks.qr_scanner', 'admin.inventory.scanner*', 'fa-camera'],
    ['marks.bulk_import', 'admin.inventory.bulk*', 'fa-file-upload'],
    ['marks.reports', 'admin.inventory.reports.*', 'fa-file-alt'],
    ['marks.school_profile', 'admin.inventory.school.*', 'fa-school'],
];
@endphp
<div class="card x-card mb-4">
    <div class="card-body py-2">
        <ul class="nav nav-pills flex-wrap" role="navigation" aria-label="Inventory modules">
            @foreach($invTabs as [$key, $pattern, $icon])
                @php
                    $url = '#';
                    if ($pattern === 'admin.inventory.dashboard') $url = route('admin.inventory.dashboard');
                    elseif ($pattern === 'admin.inventory.items.*') $url = route('admin.inventory.items.index');
                    elseif ($pattern === 'admin.inventory.items.map') $url = route('admin.inventory.items.map');
                    elseif ($pattern === 'admin.inventory.sections.*') $url = route('admin.inventory.sections.index');
                    elseif ($pattern === 'admin.inventory.checks.*') $url = route('admin.inventory.checks.index');
                    elseif ($pattern === 'admin.inventory.removals.*') $url = route('admin.inventory.removals.index');
                    elseif ($pattern === 'admin.inventory.qr*') $url = route('admin.inventory.qr');
                    elseif ($pattern === 'admin.inventory.scanner*') $url = route('admin.inventory.scanner');
                    elseif ($pattern === 'admin.inventory.bulk*') $url = route('admin.inventory.bulk');
                    elseif ($pattern === 'admin.inventory.reports.*') $url = route('admin.inventory.reports.index');
                    elseif ($pattern === 'admin.inventory.school.*') $url = route('admin.inventory.school.edit');
                    $isActive = Route::is($pattern) && !($pattern === 'admin.inventory.items.*' && Route::is('admin.inventory.items.map'));
                @endphp
                <li class="nav-item">
                    <a class="nav-link {{ $isActive ? 'active' : '' }}" href="{{ $url }}"><i class="fa {{ $icon }}" aria-hidden="true"></i> {{ __($key) }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
