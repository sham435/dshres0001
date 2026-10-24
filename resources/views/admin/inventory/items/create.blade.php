@extends('layouts.admin')

@section('title', __('marks.inv_add_item'))
@section('content')
@include('admin.inventory._tabs')
<div class="x-page-head">
    <h4>{{ __('marks.inv_add_item') }}</h4>
    <div class="actions">
<a href="{{ route('admin.inventory.items.index') }}" class="btn btn-secondary">{{ __('marks.inv_back') }}</a>
    </div>
</div>
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.inventory.items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.inventory.items._form')
            <button type="submit" class="btn btn-primary">{{ __('marks.inv_save_item') }}</button>
        </form>
    </div>
</div>
@endsection
