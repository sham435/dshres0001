@extends('layouts.admin')

@section('title', 'Enter Counts')
@section('content')
@include('admin.inventory._tabs')
<div class="x-page-head">
    <h4>{{ $session->session_name }} <small class="text-muted">({{ $session->check_date }})</small></h4>
    <div class="actions">
        <a href="{{ route('admin.inventory.checks.report', $session->id) }}" class="btn btn-secondary">{{ __('marks.inv_report_btn') }}</a>
        <a href="{{ route('admin.inventory.checks.index') }}" class="btn btn-secondary">{{ __('marks.inv_back') }}</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
@endif

<div class="card x-card">
    <div class="card-body">
        <form action="{{ route('admin.inventory.checks.update-items', $session->id) }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead><tr><th>#</th><th>{{ __('marks.inv_item') }}</th><th>{{ __('marks.inv_inventory_no') }}</th><th class="text-right">{{ __('marks.inv_book') }}</th><th>{{ __('marks.inv_real') }} *</th><th>{{ __('marks.inv_remarks') }}</th><th>{{ __('marks.inv_status') }}</th></tr></thead>
                    <tbody>
                        @forelse($session->items as $check)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $check->item_name }}</td>
                                <td>{{ $check->inventory_number }}</td>
                                <td class="table-numeric">{{ $check->book_balance }}</td>
                                <td style="min-width: 110px;">
                                    <input type="number" name="items[{{ $check->id }}][real_balance]" min="0" value="{{ old('items.' . $check->id . '.real_balance', $check->real_balance) }}" class="form-control" required aria-label="Real balance for {{ $check->item_name }}">
                                </td>
                                <td><input type="text" name="items[{{ $check->id }}][remarks]" value="{{ old('items.' . $check->id . '.remarks', $check->remarks) }}" class="form-control" aria-label="Remarks for {{ $check->item_name }}"></td>
                                <td>
                                    <select name="items[{{ $check->id }}][checked_status]" class="form-control" aria-label="Status for {{ $check->item_name }}">
                                        @foreach(['pending' => 'marks.inv_opt_pending', 'checked' => 'marks.inv_opt_checked', 'verified' => 'marks.inv_opt_verified'] as $val => $key)
                                            <option value="{{ $val }}" {{ $check->checked_status === $val ? 'selected' : '' }}>{{ __($key) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7"><div class="x-empty"><i class="fa fa-box-open" aria-hidden="true"></i>{{ __('marks.inv_no_snapshot') }}</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($session->items->count() > 0 && $session->status === 'active')
                <button type="submit" class="btn btn-primary">{{ __('marks.inv_save_balances') }}</button>
            @endif
        </form>
        @if($session->status === 'active' && $session->items->count() > 0)
            <form action="{{ route('admin.inventory.checks.complete', $session->id) }}" method="POST" class="mt-2" onsubmit="return confirm('{{ __('marks.inv_complete_confirm') }}')">
                @csrf
                <button type="submit" class="btn btn-success">{{ __('marks.inv_complete_session') }}</button>
            </form>
        @endif
    </div>
</div>
@endsection
