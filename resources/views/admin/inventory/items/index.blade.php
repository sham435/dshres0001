@extends('layouts.admin')

@section('title', __('marks.inv_items'))
@section('content')
@include('admin.inventory._tabs')
<div class="x-page-head">
    <h4>{{ __('marks.inv_items') }}</h4>
    <div class="actions">
        <a href="{{ route('admin.inventory.bulk') }}" class="btn btn-success">{{ __('marks.bulk_import') }}</a>
        <a href="{{ route('admin.inventory.items.create') }}" class="btn btn-primary">{{ __('marks.inv_add_item') }}</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
@endif

<div class="card shadow mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.inventory.items.index') }}" class="form-row">
            <div class="form-group col-md-3">
                <input type="text" name="search" class="form-control" placeholder="{{ __('marks.inv_search_items_ph') }}" value="{{ request('search') }}">
            </div>
            <div class="form-group col-md-3">
                <select name="section" class="form-control" onchange="this.form.submit()">
                    <option value="">{{ __('marks.inv_all_sections') }}</option>
                    @foreach($sections ?? [] as $section)
                        <option value="{{ $section->id }}" {{ request('section') == $section->id ? 'selected' : '' }}>{{ $section->section_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2">
                <select name="condition" class="form-control" onchange="this.form.submit()">
                    <option value="">{{ __('marks.inv_any_condition') }}"></option>
                    @foreach(['working' => 'marks.inv_cond_working', 'good' => 'marks.inv_cond_good', 'not_working' => 'marks.inv_cond_not_working', 'not_usable' => 'marks.inv_cond_not_usable'] as $val => $key)
                        <option value="{{ $val }}" {{ request('condition') === $val ? 'selected' : '' }}>{{ __($key) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2">
                <select name="status" class="form-control" onchange="this.form.submit()">
                    <option value="">{{ __('marks.inv_any_status') }}"></option>
                    @foreach(['available' => 'marks.inv_available', 'borrowed' => 'marks.inv_borrowed', 'transferred' => 'marks.inv_transferred'] as $val => $key)
                        <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ __($key) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2">
                <button type="submit" class="btn btn-secondary btn-block">{{ __('marks.inv_filter') }}</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr><th>#</th><th>{{ __('marks.inv_item') }}</th><th>{{ __('marks.inv_inventory_no') }}</th><th>{{ __('marks.inv_section') }}</th><th>{{ __('marks.inv_incharge') }}</th><th>{{ __('marks.inv_condition') }}</th><th>{{ __('marks.inv_status') }}</th><th>{{ __('marks.inv_actions') }}</th></tr>
                </thead>
                <tbody>
                    @forelse($items ?? [] as $item)
                        <tr>
                            <td>{{ $loop->iteration + ($items->currentPage() - 1) * $items->perPage() }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->inventory_number }}</td>
                            <td>{{ $item->section->section_name ?? __('marks.inv_na') }}</td>
                            <td>{{ $item->incharge->officer_name ?? __('marks.inv_na') }}</td>
                            <td>
                                @if(in_array($item->condition_status, ['not_working', 'not_usable']))
                                    <span class="x-pill x-pill-red"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ __('marks.inv_cond_' . $item->condition_status) }}</span>
                                @else
                                    <span class="x-pill x-pill-green"><i class="fa fa-check-circle" aria-hidden="true"></i>{{ __('marks.inv_cond_' . $item->condition_status) }}</span>
                                @endif
                            </td>
                            <td>
                                @php $st = $item->statusLabel(); @endphp
                                <span class="x-pill {{ $st === 'available' ? 'x-pill-green' : ($st === 'borrowed' ? 'x-pill-blue' : 'x-pill-slate') }}"><i class="fa {{ $st === 'available' ? 'fa-check' : ($st === 'borrowed' ? 'fa-hand-holding' : 'fa-exchange-alt') }}" aria-hidden="true"></i>{{ __('marks.inv_' . $st) }}</span>
                            </td>
                            <td class="text-nowrap">
                                <a href="{{ route('admin.inventory.items.show', $item->id) }}" class="btn btn-sm btn-info">{{ __('marks.inv_view') }}</a>
                                <a href="{{ route('admin.inventory.items.edit', $item->id) }}" class="btn btn-sm btn-warning">{{ __('marks.inv_edit') }}</a>
                                <a href="{{ route('admin.inventory.qr.single', $item->id) }}" class="btn btn-sm btn-secondary">{{ __('marks.inv_qr') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8"><div class="x-empty"><i class="fa fa-box-open" aria-hidden="true"></i>{{ __('marks.inv_try_clearing') }} <a href="{{ route('admin.inventory.items.create') }}">{{ __('marks.inv_add_first_item') }}</a>.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($items)){{ $items->links() }}@endif
    </div>
</div>
@endsection
