@extends('layouts.admin')

@section('title', __('marks.upload_title'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ __('marks.upload_title') }}</h4>
    <a href="{{ route('admin.student-marks.index') }}" class="btn btn-secondary">{{ __('marks.view_marks') }}</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('marks.close') }}"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('marks.close') }}"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.marks.import') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">{{ __('marks.select_file') }}</label>
                <input type="file" name="file" class="form-control @error('file') is-invalid @enderror"
                       accept=".xlsx,.xls" required>
                @error('file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">{{ __('marks.max_file_size') }}</div>
            </div>

            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upload me-1" viewBox="0 0 16 16">
                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                    <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
                </svg>
                {{ __('marks.import_process') }}
            </button>
        </form>
    </div>
</div>

@if(session('import'))
<div class="card mt-4">
    <div class="card-header">{{ __('marks.import_results') }}</div>
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-sm table-bordered" style="width: auto;">
            <tr>
                <th>{{ __('marks.sheets_processed') }}</th>
                <td>{{ session('import')['sheets_processed'] }}</td>
            </tr>
            <tr>
                <th>{{ __('marks.records_imported') }}</th>
                <td>{{ session('import')['imported'] }}</td>
            </tr>
            <tr>
                <th>{{ __('marks.records_skipped') }}</th>
                <td>{{ session('import')['skipped'] }}</td>
            </tr>
        </table>
        </div>
        @if(count(session('import')['errors']))
            <h6>{{ __('marks.errors') }}:</h6>
            <ul class="list-group">
                @foreach(array_slice(session('import')['errors'], 0, 20) as $error)
                    <li class="list-group-item list-group-item-danger small">{{ $error }}</li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">{{ __('marks.no_errors') }}</p>
        @endif
    </div>
</div>
@endif
@endsection
