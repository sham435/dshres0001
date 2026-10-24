@extends('layouts.app')

@section('content')
<div class="container">
    <div class="x-page-head">
    <h4>Accountant Dashboard</h4>
    <div class="actions">
<span class="text-muted">Welcome, {{ auth()->user()->name ?? 'Accountant' }}</span>
    </div>
</div>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="x-stat shadow h-100 py-2" style="border-left-color: var(--x-primary);">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="x-stat-label">Total Payments</div>
                            <div class="x-stat-value">{{ number_format($totalPayments ?? 0) }}</div>
                        </div>
                        <div class="col-auto" aria-hidden="true">
                            <i class="fa fa-receipt x-stat-icon"></i>
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
                            <div class="x-stat-label">Paid Amount</div>
                            <div class="x-stat-value">{{ number_format($paidAmount ?? 0, 2) }}</div>
                        </div>
                        <div class="col-auto" aria-hidden="true">
                            <i class="fa fa-check-circle x-stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="x-stat shadow h-100 py-2" style="border-left-color: #d97706;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="x-stat-label">Pending Amount</div>
                            <div class="x-stat-value">{{ number_format($pendingAmount ?? 0, 2) }}</div>
                        </div>
                        <div class="col-auto" aria-hidden="true">
                            <i class="fa fa-clock x-stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fa fa-plus-circle fa-3x text-success mb-3"></i>
                    <h5>Record Payment</h5>
                    <p class="text-muted">Add a new payment record.</p>
                    <a href="{{ route('accountant.payments.create') ?? '#' }}" class="btn btn-success">New Payment</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fa fa-list fa-3x text-primary mb-3"></i>
                    <h5>All Payments</h5>
                    <p class="text-muted">View and manage all payments.</p>
                    <a href="{{ route('accountant.payments.index') ?? '#' }}" class="btn btn-primary">Manage Payments</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fa fa-calendar-times fa-3x text-warning mb-3"></i>
                    <h5>{{ __('marks.lv_title') }}</h5>
                    <p class="text-muted">{{ __('marks.lv_apply') }}</p>
                    <a href="{{ route('accountant.leaves.index') }}" class="btn btn-warning">{{ __('marks.lv_title') }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Recent Payments</h6>
            <a href="{{ route('accountant.payments.index') ?? '#' }}" class="btn btn-sm btn-primary">View All</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentPayments ?? [] as $payment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $payment->student->name ?? 'N/A' }}</td>
                                <td>{{ number_format($payment->amount ?? 0, 2) }}</td>
                                <td>
                                    @php
                                        $status = $payment->status ?? 'pending';
                                    @endphp
                                    <span class="x-pill {{ $status === 'paid' ? 'x-pill-green' : ($status === 'pending' ? 'x-pill-amber' : 'x-pill-red') }}"><i class="fa {{ $status === 'paid' ? 'fa-check' : ($status === 'pending' ? 'fa-clock' : 'fa-times') }}" aria-hidden="true"></i>
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td>{{ $payment->created_at ? $payment->created_at->format('d M Y') : 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No payments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
