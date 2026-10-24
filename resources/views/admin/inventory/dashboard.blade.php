@extends('layouts.admin')

@section('title', __('marks.inventory_dashboard'))
@section('content')
@include('admin.inventory._tabs')
<div class="x-page-head">
    <h4>{{ __('marks.inventory_dashboard') }}</h4>
    <div class="actions">
        <a href="{{ route('admin.inventory.items.create') }}" class="btn btn-primary">{{ __('marks.inv_add_item') }}</a>
    </div>
</div>

<div class="row">
    @php
        $cards = [
            [__('marks.inv_total_items'), $totalItems ?? 0, 'var(--x-primary)', 'fa-boxes'],
            [__('marks.inv_available'), $availableItems ?? 0, 'var(--x-accent)', 'fa-check-circle'],
            [__('marks.inv_borrowed'), $borrowedItems ?? 0, '#0284c7', 'fa-hand-holding'],
            [__('marks.inv_transferred'), $transferredItems ?? 0, '#64748b', 'fa-exchange-alt'],
            [__('marks.inv_needs_attention'), $needsAttention ?? 0, 'var(--x-danger)', 'fa-exclamation-triangle'],
            [__('marks.inv_sections'), $totalSections ?? 0, '#d97706', 'fa-th-large'],
            [__('marks.inv_officers'), $totalOfficers ?? 0, 'var(--x-primary)', 'fa-users'],
            [__('marks.inv_incharge_officers'), $inchargeOfficers ?? 0, 'var(--x-accent)', 'fa-user-shield'],
        ];
    @endphp
    @foreach($cards as [$label, $value, $accent, $icon])
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="x-stat shadow h-100 py-2" style="border-left-color: {{ $accent }};">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="x-stat-label">{{ $label }}</div>
                            <div class="x-stat-value">{{ number_format($value) }}</div>
                        </div>
                        <div class="col-auto" aria-hidden="true"><i class="fa {{ $icon }} x-stat-icon"></i></div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">{{ __('marks.inv_quick_actions') }}</h6></div>
            <div class="card-body">
                <a href="{{ route('admin.inventory.items.index') }}" class="btn btn-primary mb-2">{{ __('marks.inv_manage_items') }}</a>
                <a href="{{ route('admin.inventory.items.map') }}" class="btn btn-secondary mb-2">{{ __('marks.section_map') }}</a>
                <a href="{{ route('admin.inventory.checks.index') }}" class="btn btn-info mb-2">{{ __('marks.stock_checks') }}</a>
                <a href="{{ route('admin.inventory.scanner') }}" class="btn btn-warning mb-2">{{ __('marks.qr_scanner') }}</a>
                <a href="{{ route('admin.inventory.bulk') }}" class="btn btn-success mb-2">{{ __('marks.bulk_import') }}</a>
                <a href="{{ route('admin.inventory.reports.index') }}" class="btn btn-dark mb-2">{{ __('marks.reports') }}</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">{{ __('marks.inv_recently_added') }}</h6></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead><tr><th>{{ __('marks.inv_item') }}</th><th>{{ __('marks.inv_inventory_no') }}</th><th>{{ __('marks.inv_section') }}</th></tr></thead>
                        <tbody>
                            @forelse($recentItems ?? [] as $item)
                                <tr>
                                    <td><a href="{{ route('admin.inventory.items.show', $item->id) }}">{{ $item->item_name }}</a></td>
                                    <td>{{ $item->inventory_number }}</td>
                                    <td>{{ $item->section->section_name ?? __('marks.inv_na') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center">{{ __('marks.inv_no_items_yet') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
