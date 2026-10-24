@extends('layouts.admin')

@section('title', __('marks.inv_item_details'))
@section('content')
@include('admin.inventory._tabs')
<div class="x-page-head">
    <h4>{{ $item->item_name }}</h4>
    <div class="actions">
<div>
        <a href="{{ route('admin.inventory.qr.single', $item->id) }}" class="btn btn-secondary">{{ __('marks.inv_qr_label') }}</a>
        <a href="{{ route('admin.inventory.items.edit', $item->id) }}" class="btn btn-warning">{{ __('marks.inv_edit') }}</a>
        <a href="{{ route('admin.inventory.items.index') }}" class="btn btn-secondary">{{ __('marks.inv_back') }}</a>
    </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-bordered">
                    <tr><th style="width: 200px;">{{ __('marks.inv_inventory_no') }}</th><td>{{ $item->inventory_number }}</td></tr>
                    <tr><th>{{ __('marks.inv_serial_no') }}</th><td>{{ $item->serial_number ?? __('marks.inv_na') }}</td></tr>
                    <tr><th>{{ __('marks.inv_brand') }}</th><td>{{ $item->brand_name ?? __('marks.inv_na') }}</td></tr>
                    <tr><th>{{ __('marks.inv_book_no') }}</th><td>{{ $item->book_number }}</td></tr>
                    <tr><th>{{ __('marks.inv_section') }}</th><td>{{ $item->section->section_name ?? __('marks.inv_na') }}</td></tr>
                    <tr><th>{{ __('marks.inv_incharge_officer') }}</th><td>{{ $item->incharge->officer_name ?? __('marks.inv_na') }}</td></tr>
                    <tr><th>{{ __('marks.inv_received_date') }}</th><td>{{ $item->received_date ? $item->received_date->format('d M Y') : __('marks.inv_na') }}</td></tr>
                    <tr><th>{{ __('marks.inv_received_from') }}</th><td>{{ $item->received_from ?? __('marks.inv_na') }}</td></tr>
                    <tr><th>{{ __('marks.inv_condition') }}</th><td>{{ __('marks.inv_cond_' . $item->condition_status) }}</td></tr>
                    <tr><th>{{ __('marks.inv_status') }}</th><td>{{ __('marks.inv_' . $item->statusLabel()) }}</td></tr>
                    @if($item->borrowed_officer)<tr><th>{{ __('marks.inv_borrowed_by') }}</th><td>{{ $item->borrowed_officer }}</td></tr>@endif
                    @if($item->is_transferred)<tr><th>{{ __('marks.inv_transferred_date') }}</th><td>{{ $item->transferred_date ? $item->transferred_date->format('d M Y') : __('marks.inv_na') }}</td></tr>@endif
                </table>
                </div>
                <form action="{{ route('admin.inventory.items.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('marks.inv_delete_item_confirm') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('marks.inv_delete_item') }}</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">{{ __('marks.inv_photo') }}</h6></div>
            <div class="card-body text-center">
                @if($item->image_path)
                    <img loading="lazy" src="{{ asset('storage/' . $item->image_path) }}" alt="Item photo" class="img-fluid rounded">
                @else
                    <p class="text-muted">{{ __('marks.inv_no_photo') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
