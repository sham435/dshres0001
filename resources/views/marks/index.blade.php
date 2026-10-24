@extends('layouts.admin')

@section('title', __('marks.title'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ __('marks.title') }}</h4>
    <div>
        <a href="{{ route('admin.student-marks.upload') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upload me-1" viewBox="0 0 16 16">
                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
            </svg>
            {{ __('marks.upload_file') }}
        </a>
        <a href="{{ route('admin.marks.export') }}" class="btn btn-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download me-1" viewBox="0 0 16 16">
                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
            </svg>
            {{ __('marks.export') }}
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.student-marks.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="{{ __('marks.search') }}"
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="grade" class="form-select">
                    <option value="">{{ __('marks.all_grades') }}</option>
                    @foreach($grades as $g)
                        <option value="{{ $g }}" {{ request('grade') == $g ? 'selected' : '' }}>{{ __('marks.grade') }} {{ $g }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="section" class="form-select">
                    <option value="">{{ __('marks.all_sections') }}</option>
                    @foreach($sections as $s)
                        <option value="{{ $s }}" {{ request('section') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="subject" class="form-select">
                    <option value="">{{ __('marks.all_subjects') }}</option>
                    @foreach($subjects as $s)
                        <option value="{{ $s }}" {{ request('subject') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">{{ __('marks.filter') }}</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>{{ __('marks.id') }}</th>
                        <th>{{ __('marks.student_name') }}</th>
                        <th>{{ __('marks.admission_no') }}</th>
                        <th>{{ __('marks.grade') }}</th>
                        <th>{{ __('marks.section') }}</th>
                        <th>{{ __('marks.subject') }}</th>
                        <th>{{ __('marks.mark') }}</th>
                        <th>{{ __('marks.grade_letter') }}</th>
                        <th>{{ __('marks.term') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($marks as $mark)
                        <tr>
                            <td>{{ $mark->id }}</td>
                            <td>{{ $mark->student_name }}</td>
                            <td>{{ $mark->admission_no }}</td>
                            <td>{{ $mark->grade }}</td>
                            <td>{{ $mark->section }}</td>
                            <td>{{ $mark->subject }}</td>
                            <td>
                                <span class="badge bg-{{ $mark->mark >= 75 ? 'success' : ($mark->mark >= 55 ? 'primary' : ($mark->mark >= 35 ? 'warning' : 'danger')) }}">
                                    {{ $mark->mark }}
                                </span>
                            </td>
                            <td>{{ $mark->grade_letter }}</td>
                            <td>{{ $mark->term }} {{ $mark->year }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                {{ __('marks.no_data') }}
                                <a href="{{ route('admin.student-marks.upload') }}">{{ __('marks.upload_to_start') }}</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($marks->hasPages())
        <div class="card-footer">
            {{ $marks->links() }}
        </div>
    @endif
</div>
@endsection
