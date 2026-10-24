@extends('layouts.admin')

@section('title', 'Settings')
@section('content')
<div class="x-page-head">
    <h4>Settings</h4>
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
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="school_name" class="form-label">School Name</label>
                <input type="text" name="school_name" id="school_name" class="form-control @error('school_name') is-invalid @enderror" value="{{ old('school_name', $settings['school_name'] ?? '') }}" required>
                @error('school_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="school_address" class="form-label">School Address</label>
                <textarea name="school_address" id="school_address" class="form-control @error('school_address') is-invalid @enderror" rows="3">{{ old('school_address', $settings['school_address'] ?? '') }}</textarea>
                @error('school_address') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="school_phone" class="form-label">School Phone</label>
                <input type="text" name="school_phone" id="school_phone" class="form-control @error('school_phone') is-invalid @enderror" value="{{ old('school_phone', $settings['school_phone'] ?? '') }}">
                @error('school_phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="school_email" class="form-label">School Email</label>
                <input type="email" name="school_email" id="school_email" class="form-control @error('school_email') is-invalid @enderror" value="{{ old('school_email', $settings['school_email'] ?? '') }}">
                @error('school_email') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="academic_year" class="form-label">Academic Year</label>
                <input type="text" name="academic_year" id="academic_year" class="form-control @error('academic_year') is-invalid @enderror" value="{{ old('academic_year', $settings['academic_year'] ?? '') }}" required>
                @error('academic_year') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary">Update Settings</button>
        </form>
    </div>
</div>
@endsection
