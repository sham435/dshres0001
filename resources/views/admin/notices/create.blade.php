@extends('layouts.admin')

@section('title', 'Create Notice')
@section('content')
<div class="x-page-head">
    <h4>Create Notice</h4>
    <div class="actions">
<a href="{{ route('admin.notices.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.notices.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="content" class="form-label">Content</label>
                <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="5" required>{{ old('content') }}</textarea>
                @error('content') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="notice_date" class="form-label">Notice Date</label>
                <input type="date" name="notice_date" id="notice_date" class="form-control @error('notice_date') is-invalid @enderror" value="{{ old('notice_date') }}" required>
                @error('notice_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary">Create Notice</button>
        </form>
    </div>
</div>
@endsection
