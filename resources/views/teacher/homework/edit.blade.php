@extends('layouts.app')

@section('title', __('marks.hw_update'))
@section('content')
<div class="container">
    <div class="x-page-head">
        <h4>{{ __('marks.hw_update') }}</h4>
        <div class="actions">
            <a href="{{ route('teacher.homework.index') }}" class="btn btn-secondary">{{ __('marks.hw_back') }}</a>
        </div>
    </div>

    <div class="card x-card">
        <div class="card-body">
            <form action="{{ route('teacher.homework.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('teacher.homework._form')
                <button type="submit" class="btn btn-primary">{{ __('marks.hw_update') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
