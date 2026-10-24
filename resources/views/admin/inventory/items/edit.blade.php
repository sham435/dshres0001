@extends('layouts.admin')

@section('title', __('marks.inv_edit_item'))
@section('content')
@include('admin.inventory._tabs')
<div class="x-page-head">
    <h4>{{ __('marks.inv_edit_item') }}</h4>
    <div class="actions">
<a href="{{ route('admin.inventory.items.index') }}" class="btn btn-secondary">{{ __('marks.inv_back') }}</a>
    </div>
</div>
<div class="card shadow">
    <div class="card-body">
        @if($item->image_path)
            <img loading="lazy" src="{{ asset('storage/' . $item->image_path) }}" alt="Item photo" style="max-height: 150px;" class="mb-3 rounded">
        @endif
        <form action="{{ route('admin.inventory.items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.inventory.items._form')
            <button type="submit" class="btn btn-primary">{{ __('marks.inv_update_item') }}</button>
        </form>
    </div>
</div>
@endsection
