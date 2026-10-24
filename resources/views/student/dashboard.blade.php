@extends('layouts.app')

@section('content')
<div class="container">
    <div class="x-page-head">
    <h4>Student Dashboard</h4>
    <div class="actions">
<span class="text-muted">Welcome, {{ auth()->user()->name ?? 'Student' }}</span>
    </div>
</div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="x-stat shadow h-100 py-2" style="border-left-color: var(--x-primary);">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="x-stat-label">My Marks</div>
                            <div class="x-stat-value">{{ number_format($myMarks ?? 0) }}</div>
                        </div>
                        <div class="col-auto" aria-hidden="true">
                            <i class="fa fa-chart-line x-stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="x-stat shadow h-100 py-2" style="border-left-color: var(--x-accent);">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="x-stat-label">Notices</div>
                            <div class="x-stat-value">{{ number_format($notices ?? 0) }}</div>
                        </div>
                        <div class="col-auto" aria-hidden="true">
                            <i class="fa fa-bullhorn x-stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="x-stat shadow h-100 py-2" style="border-left-color: #0284c7;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="x-stat-label">Payments</div>
                            <div class="x-stat-value">{{ number_format($payments ?? 0) }}</div>
                        </div>
                        <div class="col-auto" aria-hidden="true">
                            <i class="fa fa-credit-card x-stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="x-stat shadow h-100 py-2" style="border-left-color: #d97706;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="x-stat-label">Books Available</div>
                            <div class="x-stat-value">{{ number_format($booksAvailable ?? 0) }}</div>
                        </div>
                        <div class="col-auto" aria-hidden="true">
                            <i class="fa fa-book x-stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fa fa-file-alt fa-3x text-primary mb-3"></i>
                    <h5>Marksheet</h5>
                    <a href="{{ route('student.marksheet') }}" class="btn btn-primary btn-sm">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fa fa-calendar-alt fa-3x text-success mb-3"></i>
                    <h5>Timetable</h5>
                    <a href="{{ route('student.timetable') }}" class="btn btn-success btn-sm">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fa fa-credit-card fa-3x text-info mb-3"></i>
                    <h5>Payments</h5>
                    <a href="{{ route('student.payments') }}" class="btn btn-info btn-sm">Pay</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fa fa-book fa-3x text-warning mb-3"></i>
                    <h5>Library</h5>
                    <a href="{{ route('student.books') }}" class="btn btn-warning btn-sm">Browse</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fa fa-calendar-check fa-3x text-success mb-3"></i>
                    <h5>{{ __('marks.attendance') }} ({{ $attPct ?? 0 }}%)</h5>
                    <a href="{{ route('student.attendance') }}" class="btn btn-success btn-sm">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fa fa-book-open fa-3x text-primary mb-3"></i>
                    <h5>{{ __('marks.hw_homework') }}</h5>
                    <a href="{{ route('student.homework.index') }}" class="btn btn-primary btn-sm">View</a>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
