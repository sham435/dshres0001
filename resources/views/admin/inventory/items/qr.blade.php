@extends('layouts.admin')

@section('title', __('marks.qr_labels'))
@section('content')
@include('admin.inventory._tabs')
<div class="x-page-head">
    <h4>{{ __('marks.qr_labels') }}</h4>
    <div class="actions">
<a href="{{ route('admin.inventory.items.index') }}" class="btn btn-secondary">{{ __('marks.inv_back') }}</a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.inventory.qr') }}" class="form-row">
            <div class="form-group col-md-4">
                <input type="text" name="search" class="form-control" placeholder="{{ __('marks.inv_search_ph') }}" value="{{ request('search') }}">
            </div>
            <div class="form-group col-md-4">
                <select name="section" class="form-control" onchange="this.form.submit()">
                    <option value="">{{ __('marks.inv_all_sections') }}</option>
                    @foreach($sections ?? [] as $section)
                        <option value="{{ $section->id }}" {{ request('section') == $section->id ? 'selected' : '' }}>{{ $section->section_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <button type="submit" class="btn btn-secondary">{{ __('marks.inv_search_btn') }}</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.inventory.qr.batch') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary mb-3">{{ __('marks.inv_generate_pdf') }}</button>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead><tr><th><input type="checkbox" id="checkAll"></th><th>{{ __('marks.inv_item') }}</th><th>{{ __('marks.inv_inventory_no') }}</th><th>{{ __('marks.inv_section') }}</th><th></th></tr></thead>
                    <tbody>
                        @forelse($items ?? [] as $item)
                            <tr>
                                <td><input type="checkbox" name="selected_items[]" value="{{ $item->id }}" class="item-check"></td>
                                <td>{{ $item->item_name }}</td>
                                <td>{{ $item->inventory_number }}</td>
                                <td>{{ $item->section->section_name ?? __('marks.inv_na') }}</td>
                                <td><a href="{{ route('admin.inventory.qr.single', $item->id) }}" class="btn btn-sm btn-secondary">{{ __('marks.inv_single_pdf') }}</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">{{ __('marks.inv_no_items_found') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
        @if(isset($items)){{ $items->links() }}@endif
    </div>
</div>
<script>
document.getElementById('checkAll').onclick = function () {
    document.querySelectorAll('.item-check').forEach(function (c) { c.checked = document.getElementById('checkAll').checked; });
};
</script>
@endsection
