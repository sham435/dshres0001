@extends('layouts.app')

@section('title', __('marks.hw_homework'))
@section('content')
<div class="container">
    <div class="x-page-head">
        <h4>{{ $item->title }}</h4>
        <div class="actions">
            <a href="{{ route('student.homework.index') }}" class="btn btn-secondary">{{ __('marks.hw_back') }}</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="row">
        <div class="col-md-7 mb-4">
            <div class="card x-card">
                <div class="card-body">
                    <p><strong>{{ __('marks.hw_subject') }}:</strong> {{ $item->subject->name ?? __('marks.inv_na') }}</p>
                    <p><strong>{{ __('marks.hw_teacher') }}:</strong> {{ $item->teacher->name ?? __('marks.inv_na') }}</p>
                    <p><strong>{{ __('marks.hw_due_date') }}:</strong> {{ $item->due_date ? $item->due_date->format('d M Y') : '-' }}</p>
                    <p style="white-space: pre-wrap;">{{ $item->description }}</p>
                    @if($item->file_path)
                        <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn btn-info">{{ __('marks.hw_file') }}: {{ __('marks.hw_view') }}</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-5 mb-4">
            <div class="card x-card">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">{{ __('marks.hw_my_submission') }}</h6></div>
                <div class="card-body">
                    @if($mine && $mine->grade)
                        <p><strong>{{ __('marks.hw_grade') }}:</strong> <span class="x-pill x-pill-green">{{ $mine->grade }}</span></p>
                    @endif
                    <form action="{{ route('student.homework.submit', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="note" class="form-label">{{ __('marks.hw_note') }}</label>
                            <textarea name="note" id="note" class="form-control" rows="3">{{ old('note', $mine->note ?? '') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="file" class="form-label">{{ __('marks.hw_file') }}</label>
                            <input type="file" name="file" id="file" class="form-control-file">
                            <small class="text-muted">{{ __('marks.hw_submit_hint') }}</small>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ $mine ? __('marks.hw_submit_update') : __('marks.hw_submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
