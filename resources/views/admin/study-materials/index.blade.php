@extends('layouts.admin')

@section('title', 'Manage Study Materials')
@section('content')
@php $routePrefix = $routePrefix ?? 'admin'; @endphp
<div class="x-page-head">
    <h4>Manage Study Materials</h4>
    <div class="actions">
<a href="{{ route($routePrefix . '.study-materials.create') }}" class="btn btn-primary">Add New</a>
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
                        <th>Title</th>
                        <th>Subject</th>
                        <th>Teacher</th>
                        <th>File</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($materials ?? [] as $material)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $material->title }}</td>
                            <td>{{ $material->subject->name ?? $material->subject_id }}</td>
                            <td>{{ $material->teacher->name ?? $material->teacher_id ?? 'N/A' }}</td>
                            <td>
                                @if($material->file_path)
                                    <a href="{{ route($routePrefix . '.study-materials.download', $material->id) }}" class="btn btn-sm btn-info">Download</a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <a href="{{ route($routePrefix . '.study-materials.edit', $material->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route($routePrefix . '.study-materials.destroy', $material->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No study materials found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
