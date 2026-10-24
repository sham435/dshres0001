@extends('layouts.admin')

@section('title', 'Manage Marks')
@section('content')
<div class="x-page-head">
    <h4>Manage Marks</h4>
    <div class="actions">
<a href="{{ route('admin.marks.create') }}" class="btn btn-primary">Add New</a>
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
                        <th>Exam</th>
                        <th>Subject</th>
                        <th>Marks Obtained</th>
                        <th>Total Marks</th>
                        <th>Grade</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($marks ?? [] as $mark)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $mark->student->name ?? $mark->student_id }}</td>
                            <td>{{ $mark->exam->name ?? $mark->exam_id }}</td>
                            <td>{{ $mark->subject->name ?? $mark->subject_id }}</td>
                            <td>{{ $mark->marks_obtained }}</td>
                            <td>{{ $mark->total_marks }}</td>
                            <td>{{ $mark->grade ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.marks.edit', $mark->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.marks.destroy', $mark->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No marks found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
