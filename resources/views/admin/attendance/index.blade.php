@extends('layouts.admin')

@section('title', __('marks.attendance'))
@section('content')
<div class="x-page-head">
    <h4>{{ __('marks.attendance') }} — {{ $date }}</h4>
</div>

<div class="row mb-4">
    <div class="col-md-3"><div class="x-stat shadow py-2" style="border-left-color: var(--x-accent);"><div class="card-body"><div class="x-stat-label">{{ __('marks.att_present') }}</div><div class="x-stat-value">{{ number_format($present) }}</div></div></div></div>
    <div class="col-md-3"><div class="x-stat shadow py-2" style="border-left-color: var(--x-danger);"><div class="card-body"><div class="x-stat-label">{{ __('marks.att_absent') }}</div><div class="x-stat-value">{{ number_format($absent) }}</div></div></div></div>
    <div class="col-md-3"><div class="x-stat shadow py-2" style="border-left-color: #d97706;"><div class="card-body"><div class="x-stat-label">{{ __('marks.att_late') }}</div><div class="x-stat-value">{{ number_format($late) }}</div></div></div></div>
    <div class="col-md-3"><div class="x-stat shadow py-2"><div class="card-body"><div class="x-stat-label">{{ __('marks.att_half_day') }}</div><div class="x-stat-value">{{ number_format($half) }}</div></div></div></div>
</div>

<div class="card x-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.attendance.index') }}" class="form-row">
            <div class="form-group col-md-3">
                <label for="date" class="form-label">{{ __('marks.att_date') }}</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
            </div>
            <div class="form-group col-md-3">
                <label for="class_id" class="form-label">{{ __('marks.inv_section') }}</label>
                <select name="class_id" id="class_id" class="form-control" onchange="this.form.submit()">
                    <option value="">{{ __('marks.inv_all') }}</option>
                    @foreach($classes ?? [] as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

<div class="card x-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead><tr><th>#</th><th>{{ __('marks.att_student') }}</th><th>{{ __('marks.att_class') }}</th><th>{{ __('marks.att_status') }}</th><th>{{ __('marks.inv_remarks') }}</th></tr></thead>
                <tbody>
                    @forelse($records ?? [] as $record)
                        <tr>
                            <td>{{ $loop->iteration + ($records->currentPage() - 1) * $records->perPage() }}</td>
                            <td>{{ $record->student->name ?? 'N/A' }}</td>
                            <td>{{ $record->class->name ?? 'N/A' }}</td>
                            <td><span class="x-pill {{ $record->status === 'present' ? 'x-pill-green' : ($record->status === 'absent' ? 'x-pill-red' : 'x-pill-amber') }}"><i class="fa {{ $record->status === 'present' ? 'fa-check' : ($record->status === 'absent' ? 'fa-times' : 'fa-clock') }}" aria-hidden="true"></i>{{ __('marks.att_st_' . $record->status) }}</span></td>
                            <td>{{ $record->remarks ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="x-empty"><i class="fa fa-calendar-check" aria-hidden="true"></i>{{ __('marks.att_no_records') }}</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($records)){{ $records->links() }}@endif
    </div>
</div>
@endsection
