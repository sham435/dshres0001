@extends('layouts.admin')

@section('title', 'Manage Exams')
@section('content')
<div class="x-page-head">
    <h4>Manage Exams</h4>
    <div class="actions">
<a href="{{ route('admin.exams.create') }}" class="btn btn-primary">Add New</a>
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
                        <th>Name</th>
                        <th>Class</th>
                        <th>Exam Date</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($exams ?? [] as $exam)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $exam->name }}</td>
                            <td>{{ $exam->class->name ?? $exam->class_id }}</td>
                            <td>{{ $exam->exam_date ? \Carbon\Carbon::parse($exam->exam_date)->format('d M Y') : 'N/A' }}</td>
                            <td>{{ Str::limit($exam->description, 50) ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.exams.edit', $exam->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.exams.destroy', $exam->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No exams found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
