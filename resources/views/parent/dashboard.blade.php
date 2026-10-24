@extends('layouts.app')

@section('content')
<div class="container">
    <div class="x-page-head">
    <h4>Parent Dashboard</h4>
    <div class="actions">
<span class="text-muted">Welcome, {{ auth()->user()->name ?? 'Parent' }}</span>
    </div>
</div>

    @php
        $children = $children ?? [];
        $recentNotices = $recentNotices ?? [];
    @endphp

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="x-stat shadow h-100 py-2" style="border-left-color: var(--x-primary);">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="x-stat-label">My Children</div>
                            <div class="x-stat-value">{{ number_format(count($children)) }}</div>
                        </div>
                        <div class="col-auto" aria-hidden="true">
                            <i class="fa fa-child x-stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="x-stat shadow h-100 py-2" style="border-left-color: var(--x-accent);">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="x-stat-label">Total Payments</div>
                            <div class="x-stat-value">{{ number_format($totalPayments ?? 0) }}</div>
                        </div>
                        <div class="col-auto" aria-hidden="true">
                            <i class="fa fa-credit-card x-stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="x-stat shadow h-100 py-2" style="border-left-color: #0284c7;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="x-stat-label">New Notices</div>
                            <div class="x-stat-value">{{ number_format(count($recentNotices)) }}</div>
                        </div>
                        <div class="col-auto" aria-hidden="true">
                            <i class="fa fa-bullhorn x-stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">My Children</h6>
        </div>
        <div class="card-body">
            @if(count($children) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($children as $child)
                                <tr>
                                    <td>{{ $child->name ?? 'N/A' }}</td>
                                    <td>{{ $child->class->name ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('parent.marksheet', $child->id) }}" class="btn btn-sm btn-primary">Marks</a>
                                        <a href="{{ route('parent.timetable', $child->id) }}" class="btn btn-sm btn-success">Timetable</a>
                                        <a href="{{ route('parent.payments') }}" class="btn btn-sm btn-info">Payments</a>
                                        <a href="{{ route('parent.attendance', $child->id) }}" class="btn btn-sm btn-warning">{{ __('marks.attendance') }}</a>
                                        <a href="{{ route('parent.homework', $child->id) }}" class="btn btn-sm btn-secondary">{{ __('marks.hw_homework') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">No children linked to your account.</p>
            @endif
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Recent Notices</h6>
        </div>
        <div class="card-body">
            @if(count($recentNotices) > 0)
                <div class="list-group">
                    @foreach($recentNotices as $notice)
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $notice->title ?? 'Untitled' }}</h6>
                                <small class="text-muted">{{ $notice->created_at ? $notice->created_at->format('d M Y') : '' }}</small>
                            </div>
                            <p class="mb-1 text-muted">{{ Str::limit($notice->description ?? '', 150) }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-0">No recent notices.</p>
            @endif
        </div>
    </div>
    </div>
</div>
@endsection
