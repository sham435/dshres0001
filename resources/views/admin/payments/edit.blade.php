@extends('layouts.admin')

@section('title', 'Edit Payment')
@section('content')
<div class="x-page-head">
    <h4>Edit Payment</h4>
    <div class="actions">
<a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.payments.update', $payment->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="student_id" class="form-label">Student</label>
                <select name="student_id" id="student_id" class="form-control @error('student_id') is-invalid @enderror" required>
                    <option value="">Select Student</option>
                    @foreach ($students ?? [] as $student)
                        <option value="{{ $student->id }}" {{ old('student_id', $payment->student_id) == $student->id ? 'selected' : '' }}>{{ $student->name }} ({{ $student->username }})</option>
                    @endforeach
                </select>
                @error('student_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="fee_type" class="form-label">Fee Type</label>
                <input type="text" name="fee_type" id="fee_type" class="form-control @error('fee_type') is-invalid @enderror" value="{{ old('fee_type', $payment->fee_type) }}" required>
                @error('fee_type') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $payment->amount) }}" required>
                @error('amount') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="paid_amount" class="form-label">Paid Amount</label>
                <input type="number" step="0.01" name="paid_amount" id="paid_amount" class="form-control @error('paid_amount') is-invalid @enderror" value="{{ old('paid_amount', $payment->paid_amount) }}" required>
                @error('paid_amount') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="due_date" class="form-label">Due Date</label>
                <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date', $payment->due_date) }}" required>
                @error('due_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                    <option value="">Select Status</option>
                    <option value="pending" {{ old('status', $payment->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ old('status', $payment->status) === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="partial" {{ old('status', $payment->status) === 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="overdue" {{ old('status', $payment->status) === 'overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="cancelled" {{ old('status', $payment->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="remarks" class="form-label">Remarks</label>
                <textarea name="remarks" id="remarks" class="form-control @error('remarks') is-invalid @enderror" rows="3">{{ old('remarks', $payment->remarks) }}</textarea>
                @error('remarks') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="receipt" class="form-label">Receipt</label>
                <input type="file" name="receipt" id="receipt" class="form-control-file @error('receipt') is-invalid @enderror">
                @if($payment->receipt)
                    <small class="form-text text-muted">Current receipt: <a href="{{ asset('storage/' . $payment->receipt) }}" target="_blank">View</a></small>
                @endif
                @error('receipt') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary">Update Payment</button>
        </form>
    </div>
</div>
@endsection
