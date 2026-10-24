@extends('layouts.admin')

@section('title', 'Create Timetable')
@section('content')
<div class="x-page-head">
    <h4>Create Timetable</h4>
    <div class="actions">
<a href="{{ route('admin.timetables.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.timetables.store') }}" method="POST">
            @csrf
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
                <label for="teacher_id" class="form-label">Teacher</label>
                <select name="teacher_id" id="teacher_id" class="form-control @error('teacher_id') is-invalid @enderror" required>
                    <option value="">Select Teacher</option>
                    @foreach ($teachers ?? [] as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                    @endforeach
                </select>
                @error('teacher_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="day_of_week" class="form-label">Day of Week</label>
                <select name="day_of_week" id="day_of_week" class="form-control @error('day_of_week') is-invalid @enderror" required>
                    <option value="">Select Day</option>
                    <option value="monday" {{ old('day_of_week') === 'monday' ? 'selected' : '' }}>Monday</option>
                    <option value="tuesday" {{ old('day_of_week') === 'tuesday' ? 'selected' : '' }}>Tuesday</option>
                    <option value="wednesday" {{ old('day_of_week') === 'wednesday' ? 'selected' : '' }}>Wednesday</option>
                    <option value="thursday" {{ old('day_of_week') === 'thursday' ? 'selected' : '' }}>Thursday</option>
                    <option value="friday" {{ old('day_of_week') === 'friday' ? 'selected' : '' }}>Friday</option>
                </select>
                @error('day_of_week') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="start_time" class="form-label">Start Time</label>
                <input type="time" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                @error('start_time') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="end_time" class="form-label">End Time</label>
                <input type="time" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                @error('end_time') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="room_number" class="form-label">Room Number</label>
                <input type="text" name="room_number" id="room_number" class="form-control @error('room_number') is-invalid @enderror" value="{{ old('room_number') }}">
                @error('room_number') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary">Create Timetable</button>
        </form>
    </div>
</div>
@endsection
