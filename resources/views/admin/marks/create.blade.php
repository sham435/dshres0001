@extends('layouts.admin')

@section('title', 'Create Mark')
@section('content')
<div class="x-page-head">
    <h4>Create Mark</h4>
    <div class="actions">
<a href="{{ route('admin.marks.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.marks.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="exam_id" class="form-label">Exam</label>
                <select name="exam_id" id="exam_id" class="form-control @error('exam_id') is-invalid @enderror" required>
                    <option value="">Select Exam</option>
                    @foreach ($exams ?? [] as $exam)
                        <option value="{{ $exam->id }}" {{ old('exam_id') == $exam->id ? 'selected' : '' }}>{{ $exam->name }}</option>
                    @endforeach
                </select>
                @error('exam_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="subject_id" class="form-label">Subject</label>
                <select name="subject_id" id="subject_id" class="form-control @error('subject_id') is-invalid @enderror" required>
                    <option value="">Select Subject</option>
                    @foreach ($subjects ?? [] as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                    @endforeach
                </select>
                @error('subject_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="student_id" class="form-label">Student</label>
                <select name="student_id" id="student_id" class="form-control @error('student_id') is-invalid @enderror" required>
                    <option value="">Select Student</option>
                    @foreach ($students ?? [] as $student)
                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>{{ $student->name }} ({{ $student->username }})</option>
                    @endforeach
                </select>
                @error('student_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="marks_obtained" class="form-label">Marks Obtained</label>
                <input type="number" step="0.01" name="marks_obtained" id="marks_obtained" class="form-control @error('marks_obtained') is-invalid @enderror" value="{{ old('marks_obtained') }}" required>
                @error('marks_obtained') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="total_marks" class="form-label">Total Marks</label>
                <input type="number" step="0.01" name="total_marks" id="total_marks" class="form-control @error('total_marks') is-invalid @enderror" value="{{ old('total_marks') }}" required>
                @error('total_marks') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="grade" class="form-label">Grade</label>
                <input type="text" name="grade" id="grade" class="form-control @error('grade') is-invalid @enderror" value="{{ old('grade') }}">
                @error('grade') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="remarks" class="form-label">Remarks</label>
                <textarea name="remarks" id="remarks" class="form-control @error('remarks') is-invalid @enderror" rows="3">{{ old('remarks') }}</textarea>
                @error('remarks') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary">Create Mark</button>
        </form>
    </div>
</div>
@endsection
