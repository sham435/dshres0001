@extends('layouts.app')

@section('title', __('marks.attendance'))
@section('content')
<div class="container">
    <div class="x-page-head">
        <h4>{{ __('marks.attendance') }}</h4>
    </div>

    <div class="row mb-4">
        <div class="col-md-4"><div class="x-stat shadow py-2"><div class="card-body"><div class="x-stat-label">{{ __('marks.att_total_days') }}</div><div class="x-stat-value">{{ number_format($summary['total'] ?? 0) }}</div></div></div></div>
        <div class="col-md-4"><div class="x-stat shadow py-2" style="border-left-color: var(--x-accent);"><div class="card-body"><div class="x-stat-label">{{ __('marks.att_present') }}</div><div class="x-stat-value">{{ number_format($summary['present'] ?? 0) }}</div></div></div></div>
        <div class="col-md-4"><div class="x-stat shadow py-2" style="border-left-color: #0284c7;"><div class="card-body"><div class="x-stat-label">%</div><div class="x-stat-value">{{ $summary['percentage'] ?? 0 }}%</div></div></div></div>
    </div>

    <div class="card x-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead><tr><th>{{ __('marks.att_date') }}</th><th>{{ __('marks.att_status') }}</th><th>{{ __('marks.inv_remarks') }}</th></tr></thead>
                    <tbody>
                        @forelse($records ?? [] as $record)
                            <tr>
                                <td>{{ $record->date ? $record->date->format('d M Y') : 'N/A' }}</td>
                                <td><span class="x-pill {{ $record->status === 'present' ? 'x-pill-green' : ($record->status === 'absent' ? 'x-pill-red' : 'x-pill-amber') }}"><i class="fa {{ $record->status === 'present' ? 'fa-check' : ($record->status === 'absent' ? 'fa-times' : 'fa-clock') }}" aria-hidden="true"></i>{{ __('marks.att_st_' . $record->status) }}</span></td>
                                <td>{{ $record->remarks ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3"><div class="x-empty"><i class="fa fa-calendar-check" aria-hidden="true"></i>{{ __('marks.att_no_records') }}</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($records)){{ $records->links() }}@endif
        </div>
    </div>
</div>
@endsection
