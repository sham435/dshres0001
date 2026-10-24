@extends('layouts.admin')

@section('title', __('marks.inv_reports_title'))
@section('content')
@include('admin.inventory._tabs')
<div class="x-page-head">
    <h4>{{ __('marks.inv_reports_title') }}</h4>
    <div class="actions">
        <a href="{{ route('admin.inventory.reports.pdf', request()->query()) }}" class="btn btn-danger">{{ __('marks.inv_download_pdf') }}</a>
        <a href="{{ route('admin.inventory.reports.excel', request()->query()) }}" class="btn btn-success">{{ __('marks.inv_download_csv') }}</a>
    </div>
</div>

<div class="card x-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.inventory.reports.index') }}" class="form-row">
            <div class="form-group col-md-3">
                <label for="search" class="form-label">{{ __('marks.inv_search_btn') }}</label>
                <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Name / inv / serial">
            </div>
            <div class="form-group col-md-2">
                <label for="section" class="form-label">{{ __('marks.inv_section') }}</label>
                <select name="section" id="section" class="form-control" onchange="this.form.submit()">
                    <option value="">{{ __('marks.inv_all') }}</option>
                    @foreach($sections ?? [] as $section)
                        <option value="{{ $section->id }}" {{ request('section') == $section->id ? 'selected' : '' }}>{{ $section->section_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2">
                <label for="condition" class="form-label">{{ __('marks.inv_condition') }}</label>
                <select name="condition" id="condition" class="form-control" onchange="this.form.submit()">
                    <option value="">{{ __('marks.inv_any') }}</option>
                    @foreach(['working' => 'marks.inv_cond_working', 'good' => 'marks.inv_cond_good', 'not_working' => 'marks.inv_cond_not_working', 'not_usable' => 'marks.inv_cond_not_usable'] as $val => $key)
                        <option value="{{ $val }}" {{ request('condition') === $val ? 'selected' : '' }}>{{ __($key) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2">
                <label for="status" class="form-label">{{ __('marks.inv_status') }}</label>
                <select name="status" id="status" class="form-control" onchange="this.form.submit()">
                    <option value="">{{ __('marks.inv_any') }}</option>
                    @foreach(['available' => 'marks.inv_available', 'borrowed' => 'marks.inv_borrowed', 'transferred' => 'marks.inv_transferred'] as $val => $key)
                        <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ __($key) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-3">
                <label class="form-label">{{ __('marks.inv_received_between') }}</label>
                <div class="form-row">
                    <div class="col"><input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" aria-label="From date"></div>
                    <div class="col"><input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" aria-label="To date"></div>
                </div>
            </div>
            <div class="form-group col-md-12"><button type="submit" class="btn btn-secondary">{{ __('marks.inv_apply_filters') }}</button></div>
        </form>
    </div>
</div>

<div class="card x-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead><tr><th>#</th><th>{{ __('marks.inv_item') }}</th><th>{{ __('marks.inv_inventory_no') }}</th><th>{{ __('marks.inv_section') }}</th><th>{{ __('marks.inv_incharge') }}</th><th>{{ __('marks.inv_received') }}</th><th>{{ __('marks.inv_condition') }}</th><th>{{ __('marks.inv_status') }}</th></tr></thead>
                <tbody>
                    @forelse($items ?? [] as $item)
                        <tr>
                            <td>{{ $loop->iteration + ($items->currentPage() - 1) * $items->perPage() }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->inventory_number }}</td>
                            <td>{{ $item->section->section_name ?? __('marks.inv_na') }}</td>
                            <td>{{ $item->incharge->officer_name ?? __('marks.inv_na') }}</td>
                            <td>{{ $item->received_date ? $item->received_date->format('d M Y') : __('marks.inv_na') }}</td>
                            <td>{{ __('marks.inv_cond_' . $item->condition_status) }}</td>
                            <td>{{ __('marks.inv_' . $item->statusLabel()) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8"><div class="x-empty"><i class="fa fa-file-alt" aria-hidden="true"></i>{{ __('marks.inv_no_rows_match') }}</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($items)){{ $items->links() }}@endif
    </div>
</div>
@endsection
