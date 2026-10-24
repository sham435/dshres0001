@extends('layouts.admin')

@section('title', 'Manage Payments')
@section('content')
<div class="x-page-head">
    <h4>Manage Payments</h4>
    <div class="actions">
<a href="{{ route('admin.payments.create') }}" class="btn btn-primary">Add New</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Fee Type</th>
                        <th>Amount</th>
                        <th>Paid Amount</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th>Receipt</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments ?? [] as $payment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $payment->student->name ?? $payment->student_id }}</td>
                            <td>{{ $payment->fee_type }}</td>
                            <td>{{ number_format($payment->amount, 2) }}</td>
                            <td>{{ number_format($payment->paid_amount, 2) }}</td>
                            <td>
                                @php
                                    $status = $payment->status ?? 'pending';
                                @endphp
                                <span class="x-pill {{ $status === 'paid' ? 'x-pill-green' : ($status === 'pending' ? 'x-pill-amber' : 'x-pill-red') }}"><i class="fa {{ $status === 'paid' ? 'fa-check' : ($status === 'pending' ? 'fa-clock' : 'fa-times') }}" aria-hidden="true"></i>
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td>{{ $payment->due_date ? \Carbon\Carbon::parse($payment->due_date)->format('d M Y') : 'N/A' }}</td>
                            <td>
                                @if($payment->receipt)
                                    <a href="{{ asset('storage/' . $payment->receipt) }}" target="_blank" class="btn btn-sm btn-info">View Receipt</a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.payments.edit', $payment->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.payments.destroy', $payment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No payments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
