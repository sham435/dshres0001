@extends('layouts.admin')

@section('title', __('marks.section_map'))
@section('content')
@include('admin.inventory._tabs')
<div class="x-page-head">
    <h4>{{ __('marks.section_map') }}</h4>
    <div class="actions">
<a href="{{ route('admin.inventory.items.index') }}" class="btn btn-secondary">{{ __('marks.inv_items') }}</a>
    </div>
</div>

<div class="row">
    @forelse($sections ?? [] as $section)
        <div class="col-md-4 mb-4">
            <a href="{{ route('admin.inventory.items.map', ['section_id' => $section->id]) }}" class="text-decoration-none">
                <div class="card shadow {{ isset($selectedSection) && $selectedSection->id === $section->id ? 'border-primary' : '' }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $section->section_name }}</h5>
                        <p class="card-text text-muted mb-1">{{ __('marks.inv_type') }}: {{ $section->section_type }}</p>
                        <p class="card-text text-muted mb-1">{{ __('marks.inv_incharge') }}: {{ optional($section->inchargeOfficer)->officer_name ?? __('marks.inv_na') }}</p>
                        <span class="badge badge-primary">{{ $section->items_count }} items</span>
                    </div>
                </div>
            </a>
        </div>
    @empty
        <div class="col-12"><p class="text-muted">{{ __('marks.inv_no_sections_yet') }}</p></div>
    @endforelse
</div>

@if(isset($selectedSection) && $selectedSection)
    <div class="card shadow">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Items in {{ $selectedSection->section_name }}</h6></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead><tr><th>#</th><th>{{ __('marks.inv_item') }}</th><th>{{ __('marks.inv_inventory_no') }}</th><th>{{ __('marks.inv_incharge') }}</th><th>{{ __('marks.inv_condition') }}</th><th></th></tr></thead>
                    <tbody>
                        @forelse($sectionItems as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->item_name }}</td>
                                <td>{{ $item->inventory_number }}</td>
                                <td>{{ $item->incharge->officer_name ?? __('marks.inv_na') }}</td>
                                <td>{{ __('marks.inv_cond_' . $item->condition_status) }}</td>
                                <td><a href="{{ route('admin.inventory.items.show', $item->id) }}" class="btn btn-sm btn-info">{{ __('marks.inv_view') }}</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">{{ __('marks.inv_no_items_in_section') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection
