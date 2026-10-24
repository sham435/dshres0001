@extends('layouts.admin')

@section('title', 'Manage Notices')
@section('content')
<div class="x-page-head">
    <h4>Manage Notices</h4>
    <div class="actions">
<a href="{{ route('admin.notices.create') }}" class="btn btn-primary">Add New</a>
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
                        <th>Posted By</th>
                        <th>Notice Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($notices ?? [] as $notice)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $notice->title }}</td>
                            <td>{{ $notice->postedBy->name ?? $notice->posted_by ?? 'N/A' }}</td>
                            <td>{{ $notice->notice_date ? \Carbon\Carbon::parse($notice->notice_date)->format('d M Y') : 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.notices.edit', $notice->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.notices.destroy', $notice->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No notices found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
