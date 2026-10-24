@extends('layouts.app')

@section('content')
<div class="container">
    <div class="x-page-head">
    <h4>Teacher Dashboard</h4>
    <div class="actions">
<span class="text-muted">Welcome, {{ auth()->user()->name ?? 'Teacher' }}</span>
    </div>
</div>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="x-stat shadow h-100 py-2" style="border-left-color: var(--x-primary);">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="x-stat-label">My Subjects</div>
                            <div class="x-stat-value">{{ number_format($mySubjects ?? 0) }}</div>
                        </div>
                        <div class="col-auto" aria-hidden="true">
                            <i class="fa fa-book x-stat-icon"></i>
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
                            <div class="x-stat-label">My Students</div>
                            <div class="x-stat-value">{{ number_format($myStudents ?? 0) }}</div>
                        </div>
                        <div class="col-auto" aria-hidden="true">
                            <i class="fa fa-users x-stat-icon"></i>
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
                            <div class="x-stat-label">Study Materials</div>
                            <div class="x-stat-value">{{ number_format($studyMaterials ?? 0) }}</div>
                        </div>
                        <div class="col-auto" aria-hidden="true">
                            <i class="fa fa-file x-stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fa fa-edit fa-3x text-primary mb-3"></i>
                    <h5>Mark Entry</h5>
                    <p class="text-muted">Enter or update marks for your subjects.</p>
                    <a href="{{ route('teacher.marks.index') ?? '#' }}" class="btn btn-primary">Go to Marks</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fa fa-calendar-alt fa-3x text-success mb-3"></i>
                    <h5>Timetable</h5>
                    <p class="text-muted">View your class schedule.</p>
                    <a href="{{ route('teacher.timetable') ?? '#' }}" class="btn btn-success">View Timetable</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fa fa-upload fa-3x text-info mb-3"></i>
                    <h5>Study Materials</h5>
                    <p class="text-muted">Upload materials for your subjects.</p>
                    <a href="{{ route('teacher.study-materials.index') ?? '#' }}" class="btn btn-info">Upload</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fa fa-calendar-check fa-3x text-success mb-3"></i>
                    <h5>{{ __('marks.attendance') }}</h5>
                    <p class="text-muted">{{ __('marks.att_mark') }} · {{ __('marks.att_st_present') }}: {{ $markedToday ?? 0 }}</p>
                    <a href="{{ route('teacher.attendance.mark') }}" class="btn btn-success">{{ __('marks.att_mark') }}</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fa fa-book-open fa-3x text-primary mb-3"></i>
                    <h5>{{ __('marks.hw_homework') }}</h5>
                    <p class="text-muted">{{ __('marks.hw_homework') }}: {{ $myHomework ?? 0 }}</p>
                    <a href="{{ route('teacher.homework.index') }}" class="btn btn-primary">{{ __('marks.hw_homework') }}</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fa fa-calendar-times fa-3x text-warning mb-3"></i>
                    <h5>{{ __('marks.lv_title') }}</h5>
                    <p class="text-muted">{{ __('marks.inv_opt_pending') }}: {{ $myLeaves ?? 0 }}</p>
                    <a href="{{ route('teacher.leaves.index') }}" class="btn btn-warning">{{ __('marks.lv_title') }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">My Assigned Classes & Subjects</h6>
        </div>
        <div class="card-body">
            @if(isset($assignedClasses) && count($assignedClasses) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Class</th>
                                <th>Subject</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignedClasses as $assignment)
                                <tr>
                                    <td>{{ $assignment->class->name ?? 'N/A' }}</td>
                                    <td>{{ $assignment->subject->name ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">No classes assigned yet.</p>
            @endif
        </div>
    </div>
    </div>
</div>
@endsection
