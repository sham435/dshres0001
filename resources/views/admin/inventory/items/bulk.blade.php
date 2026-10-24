@extends('layouts.admin')

@section('title', __('marks.bulk_import'))
@section('content')
@include('admin.inventory._tabs')
<div class="x-page-head">
    <h4>{{ __('marks.bulk_import') }} (CSV)</h4>
    <div class="actions">
<div>
        <a href="{{ route('admin.inventory.bulk.template') }}" class="btn btn-success">{{ __('marks.inv_download_template') }}</a>
        <a href="{{ route('admin.inventory.items.index') }}" class="btn btn-secondary">{{ __('marks.inv_back') }}</a>
    </div>
    </div>
</div>

@if(!isset($preview))
    <div class="card shadow">
        <div class="card-body">
            <p class="text-muted">{{ __('marks.inv_bulk_step1') }}</p>
            <form action="{{ route('admin.inventory.bulk.preview') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="excel_file" class="form-label">{{ __('marks.inv_csv_file') }}</label>
                    <input type="file" name="excel_file" id="excel_file" accept=".csv" class="form-control-file @error('excel_file') is-invalid @enderror" required>
                    @error('excel_file') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn btn-primary">{{ __('marks.inv_preview') }}</button>
            </form>
        </div>
    </div>
@else
    <div class="card shadow">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">{{ __('marks.inv_bulk_step2', ['valid' => count($preview), 'skipped' => $skipped ?? 0]) }}</h6></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead><tr><th>#</th><th>{{ __('marks.inv_item') }}</th><th>{{ __('marks.inv_inventory_no') }}</th><th>{{ __('marks.inv_book_no') }}</th><th>{{ __('marks.inv_condition') }}</th></tr></thead>
                    <tbody>
                        @foreach($preview as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row['item_name'] }}</td>
                                <td>{{ $row['inventory_number'] }}</td>
                                <td>{{ $row['book_number'] }}</td>
                                <td>{{ $row['condition_status'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <form action="{{ route('admin.inventory.bulk.confirm') }}" method="POST">
                @csrf
                <input type="hidden" name="items_data" value="{{ json_encode($preview) }}">
                <button type="submit" class="btn btn-success" {{ count($preview) === 0 ? 'disabled' : '' }}>{{ __('marks.inv_confirm_import') }}</button>
                <a href="{{ route('admin.inventory.bulk') }}" class="btn btn-secondary">{{ __('marks.cancel') }}</a>
            </form>
        </div>
    </div>
@endif
@endsection
