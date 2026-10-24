@extends('layouts.admin')

@section('title', 'Create Exam')
@section('content')
<div class="x-page-head">
    <h4>Create Exam</h4>
    <div class="actions">
<a href="{{ route('admin.exams.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.exams.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="class_id" class="form-label">Class</label>
                <select name="class_id" id="class_id" class="form-control @error('class_id') is-invalid @enderror" required>
                    <option value="">Select Class</option>
                    @foreach ($classes ?? [] as $class)
                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
                @error('class_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="exam_date" class="form-label">Exam Date</label>
                <input type="date" name="exam_date" id="exam_date" class="form-control @error('exam_date') is-invalid @enderror" value="{{ old('exam_date') }}" required>
                @error('exam_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary">Create Exam</button>
        </form>
    </div>
</div>
@endsection
