@extends('layouts.admin')

@section('title', 'Manage Timetables')
@section('content')
<div class="x-page-head">
    <h4>Manage Timetables</h4>
    <div class="actions">
<a href="{{ route('admin.timetables.create') }}" class="btn btn-primary">Add New</a>
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

<div class="card shadow mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.timetables.index') }}" class="form-inline mb-3">
            <div class="form-group mr-3">
                <label for="class_id" class="mr-2">Filter by Class:</label>
                <select name="class_id" id="class_id" class="form-control" onchange="this.form.submit()">
                    <option value="">All Classes</option>
                    @foreach ($classes ?? [] as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Day of Week</th>
                        <th>Subject</th>
                        <th>Teacher</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Room</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($timetables ?? [] as $timetable)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ ucfirst($timetable->day_of_week) }}</td>
                            <td>{{ $timetable->subject->name ?? $timetable->subject_id }}</td>
                            <td>{{ $timetable->teacher->name ?? $timetable->teacher_id }}</td>
                            <td>{{ $timetable->start_time ? \Carbon\Carbon::parse($timetable->start_time)->format('h:i A') : 'N/A' }}</td>
                            <td>{{ $timetable->end_time ? \Carbon\Carbon::parse($timetable->end_time)->format('h:i A') : 'N/A' }}</td>
                            <td>{{ $timetable->room_number ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.timetables.edit', $timetable->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.timetables.destroy', $timetable->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No timetables found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
