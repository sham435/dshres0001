@extends('layouts.admin')

@section('title', 'Edit Study Material')
@section('content')
@php $routePrefix = $routePrefix ?? 'admin'; @endphp
<div class="x-page-head">
    <h4>Edit Study Material</h4>
    <div class="actions">
<a href="{{ route($routePrefix . '.study-materials.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <form action="{{ route($routePrefix . '.study-materials.update', $material->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $material->title) }}" required>
                @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $material->description) }}</textarea>
                @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="subject_id" class="form-label">Subject</label>
                <select name="subject_id" id="subject_id" class="form-control @error('subject_id') is-invalid @enderror" required>
                    <option value="">Select Subject</option>
                    @foreach ($subjects ?? [] as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id', $material->subject_id) == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                    @endforeach
                </select>
                @error('subject_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="file" class="form-label">File <small class="text-muted">(leave empty to keep current file)</small></label>
                <input type="file" name="file" id="file" class="form-control-file @error('file') is-invalid @enderror">
                @error('file') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary">Update Study Material</button>
        </form>
    </div>
</div>
@endsection
