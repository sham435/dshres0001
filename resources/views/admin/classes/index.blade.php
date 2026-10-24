@extends('layouts.admin')

@section('title', 'Manage Classes')
@section('content')
<div class="x-page-head">
    <h4>Manage Classes</h4>
    <div class="actions">
<a href="{{ route('admin.classes.create') }}" class="btn btn-primary">Add New</a>
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
                        <th>Section</th>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($classes ?? [] as $class)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $class->name }}</td>
                            <td>{{ $class->section ?? 'N/A' }}</td>
                            <td>{{ $class->code ?? 'N/A' }}</td>
                            <td>{{ Str::limit($class->description, 50) ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.classes.edit', $class->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.classes.destroy', $class->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No classes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
