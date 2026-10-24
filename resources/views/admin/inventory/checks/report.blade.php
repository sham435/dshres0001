@extends('layouts.admin')

@section('title', __('marks.inv_check_report'))
@section('content')
@include('admin.inventory._tabs')
<div class="x-page-head">
    <h4>{{ $session->session_name }} <small class="text-muted">({{ $session->check_date }})</small></h4>
    <div class="actions">
        <a href="{{ route('admin.inventory.checks.report-csv', $session->id) }}" class="btn btn-success">CSV</a>
        <a href="{{ route('admin.inventory.checks.report-pdf', $session->id) }}" class="btn btn-danger">PDF</a>
        <a href="{{ route('admin.inventory.checks.index') }}" class="btn btn-secondary">{{ __('marks.inv_back') }}</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
@endif

@php
    $items = $session->items;
    $totalSurplus = $items->sum('surplus');
    $totalDeficiency = $items->sum('deficiency');
    $checked = $items->where('checked_status', '!=', 'pending')->count();
@endphp
<div class="row mb-4">
    <div class="col-md-3"><div class="x-stat shadow py-2"><div class="card-body"><div class="x-stat-label">{{ __('marks.inv_total_items') }}</div><div class="x-stat-value">{{ number_format($items->count()) }}</div></div></div></div>
    <div class="col-md-3"><div class="x-stat shadow py-2" style="border-left-color: var(--x-accent);"><div class="card-body"><div class="x-stat-label">{{ __('marks.inv_stat_counted') }}</div><div class="x-stat-value">{{ number_format($checked) }}</div></div></div></div>
    <div class="col-md-3"><div class="x-stat shadow py-2" style="border-left-color: #0284c7;"><div class="card-body"><div class="x-stat-label">{{ __('marks.inv_surplus') }}</div><div class="x-stat-value">{{ number_format($totalSurplus) }}</div></div></div></div>
    <div class="col-md-3"><div class="x-stat shadow py-2" style="border-left-color: var(--x-danger);"><div class="card-body"><div class="x-stat-label">{{ __('marks.inv_deficiency') }}</div><div class="x-stat-value">{{ number_format($totalDeficiency) }}</div></div></div></div>
</div>

<div class="card x-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead><tr><th>#</th><th>{{ __('marks.inv_item') }}</th><th>{{ __('marks.inv_inventory_no') }}</th><th class="text-right">{{ __('marks.inv_book') }}</th><th class="text-right">{{ __('marks.inv_real') }}</th><th class="text-right">{{ __('marks.inv_surplus') }}</th><th class="text-right">{{ __('marks.inv_deficiency') }}</th><th>{{ __('marks.inv_remarks') }}</th><th>{{ __('marks.inv_status') }}</th></tr></thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->inventory_number }}</td>
                            <td class="table-numeric">{{ $item->book_balance }}</td>
                            <td class="table-numeric">{{ $item->real_balance }}</td>
                            <td class="table-numeric">{{ $item->surplus }}</td>
                            <td class="table-numeric">{{ $item->deficiency }}</td>
                            <td>{{ $item->remarks ?? '-' }}</td>
                            <td><span class="x-pill {{ $item->checked_status === 'verified' ? 'x-pill-green' : ($item->checked_status === 'checked' ? 'x-pill-blue' : 'x-pill-amber') }}"><i class="fa {{ $item->checked_status === 'pending' ? 'fa-clock' : 'fa-check' }}" aria-hidden="true"></i>{{ __('marks.inv_opt_' . $item->checked_status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="9"><div class="x-empty"><i class="fa fa-box-open" aria-hidden="true"></i>{{ __('marks.inv_no_rows') }}</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
