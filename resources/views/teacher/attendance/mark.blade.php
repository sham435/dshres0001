@extends('layouts.app')

@section('title', __('marks.att_mark'))
@section('content')
<div class="container">
    <div class="x-page-head">
        <h4>{{ __('marks.att_mark') }}</h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    <div class="card x-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('teacher.attendance.mark') }}" class="form-row">
                <div class="form-group col-md-4">
                    <label for="date" class="form-label">{{ __('marks.att_date') }}</label>
                    <input type="date" name="date" id="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
                </div>
                <div class="form-group col-md-4">
                    <label for="class_id" class="form-label">{{ __('marks.inv_section') }}</label>
                    <select name="class_id" id="class_id" class="form-control" onchange="this.form.submit()">
                        <option value="">{{ __('marks.inv_select_section') }}</option>
                        @foreach($classes ?? [] as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if(count($students) > 0)
        <div class="card x-card">
            <div class="card-body">
                <form action="{{ route('teacher.attendance.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                    <div class="mb-3">
                        <button type="button" class="btn btn-sm btn-success" id="allPresent">{{ __('marks.att_mark_all_present') }}</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead><tr><th>#</th><th>{{ __('marks.att_student') }}</th><th>{{ __('marks.att_status') }}</th><th>{{ __('marks.inv_remarks') }}</th></tr></thead>
                            <tbody>
                                @foreach($students as $student)
                                    @php $rec = $existing[$student->id] ?? null; @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>
                                            <select name="records[{{ $student->id }}][status]" class="form-control status-select" aria-label="{{ __('marks.att_status') }} - {{ $student->name }}">
                                                @foreach(['present' => 'marks.att_st_present', 'absent' => 'marks.att_st_absent', 'late' => 'marks.att_st_late', 'half_day' => 'marks.att_st_half_day'] as $val => $key)
                                                    <option value="{{ $val }}" {{ ($rec->status ?? 'present') === $val ? 'selected' : '' }}>{{ __($key) }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" name="records[{{ $student->id }}][remarks]" value="{{ $rec->remarks ?? '' }}" class="form-control" aria-label="{{ __('marks.inv_remarks') }} - {{ $student->name }}"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('marks.att_save') }}</button>
                </form>
            </div>
        </div>
    @else
        <div class="card x-card"><div class="card-body"><div class="x-empty"><i class="fa fa-users" aria-hidden="true"></i>{{ __('marks.att_pick_class') }}</div></div></div>
    @endif
</div>
<script>
document.getElementById('allPresent').onclick = function () {
    document.querySelectorAll('.status-select').forEach(function (s) { s.value = 'present'; });
};
</script>
@endsection
