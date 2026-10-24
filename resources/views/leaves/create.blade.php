@extends('layouts.app')

@section('title', __('marks.lv_apply'))
@section('content')
<div class="container">
    <div class="x-page-head">
        <h4>{{ __('marks.lv_apply') }}</h4>
        <div class="actions">
            <a href="{{ $backUrl ?? url()->previous() }}" class="btn btn-secondary">{{ __('marks.lv_back') }}</a>
        </div>
    </div>

    <div class="card x-card mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">{{ __('marks.lv_allowance') }}</h6></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead><tr><th>{{ __('marks.lv_type') }}</th><th class="text-right">{{ __('marks.lv_allowance') }}</th><th class="text-right">{{ __('marks.lv_used') }}</th><th class="text-right">{{ __('marks.lv_remaining') }}</th><th>{{ __('marks.lv_rule') }}</th></tr></thead>
                    <tbody>
                        @foreach($types ?? [] as $type)
                            <tr>
                                <td>{{ $type->name }}</td>
                                <td class="table-numeric">{{ $type->days_allowed > 0 ? $type->days_allowed : '—' }}</td>
                                <td class="table-numeric">{{ $balances[$type->id]['used'] ?? 0 }}</td>
                                <td class="table-numeric">{{ $balances[$type->id]['remaining'] ?? __('marks.lv_no_quota') }}</td>
                                <td><small class="text-muted">{{ $type->rule_note }}{{ $type->max_consecutive_days ? ' ' . __('marks.lv_max_at_time', ['max' => $type->max_consecutive_days]) : '' }}</small></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card x-card">
        <div class="card-body">
            <form action="{{ $storeUrl }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="leave_type_id" class="form-label">{{ __('marks.lv_type') }} *</label>
                    <select name="leave_type_id" id="leave_type_id" class="form-control @error('leave_type_id') is-invalid @enderror" required>
                        <option value="">{{ __('marks.lv_type') }}</option>
                        @foreach($types ?? [] as $type)
                            <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}@if($type->days_allowed > 0) ({{ __('marks.lv_used_of', ['used' => $balances[$type->id]['used'] ?? 0, 'quota' => $type->days_allowed]) }})@endif</option>
                        @endforeach
                    </select>
                    @error('leave_type_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="from_date" class="form-label">{{ __('marks.lv_from') }} *</label>
                        <input type="date" name="from_date" id="from_date" class="form-control @error('from_date') is-invalid @enderror" value="{{ old('from_date') }}" required>
                        @error('from_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="to_date" class="form-label">{{ __('marks.lv_to') }} *</label>
                        <input type="date" name="to_date" id="to_date" class="form-control @error('to_date') is-invalid @enderror" value="{{ old('to_date') }}" required>
                        @error('to_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="reason" class="form-label">{{ __('marks.lv_reason') }} *</label>
                    <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" required>{{ old('reason') }}</textarea>
                    @error('reason') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" name="is_abroad" id="is_abroad" value="1" class="form-check-input" {{ old('is_abroad') ? 'checked' : '' }}>
                        <label for="is_abroad" class="form-check-label">{{ __('marks.lv_abroad') }}</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('marks.lv_submit') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
