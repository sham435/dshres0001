@extends('layouts.admin')

@section('title', 'Edit Class')
@section('content')
<div class="x-page-head">
    <h4>Edit Class</h4>
    <div class="actions">
<a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.classes.update', $class->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $class->name) }}" required>
                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="section" class="form-label">Section</label>
                <input type="text" name="section" id="section" class="form-control @error('section') is-invalid @enderror" value="{{ old('section', $class->section) }}">
                @error('section') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="code" class="form-label">Code</label>
                <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $class->code) }}">
                @error('code') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $class->description) }}</textarea>
                @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary">Update Class</button>
        </form>
    </div>
</div>
@endsection
