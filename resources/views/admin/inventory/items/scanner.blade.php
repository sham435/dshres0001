@extends('layouts.admin')

@section('title', __('marks.qr_scanner'))
@section('content')
@include('admin.inventory._tabs')
<div class="x-page-head">
    <h4>{{ __('marks.qr_scanner') }}</h4>
    <div class="actions">
<a href="{{ route('admin.inventory.items.index') }}" class="btn btn-secondary">{{ __('marks.inv_back') }}</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
@endif

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">{{ __('marks.inv_scan_qr') }}</h6></div>
            <div class="card-body">
                <div id="reader" style="width: 100%;"></div>
                <div id="scan-result" class="mt-3" data-found="{{ __('marks.inv_found', ['name' => ':name']) }}" data-notfound="{{ __('marks.inv_not_found') }}"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">{{ __('marks.inv_log_quick_check') }}</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.inventory.scanner.log') }}" method="POST" id="log-form">
                    @csrf
                    <input type="hidden" name="item_id" id="log_item_id">
                    <div class="form-group">
                        <label class="form-label">{{ __('marks.inv_scanned_item') }}</label>
                        <input type="text" id="log_item_name" class="form-control" readonly placeholder="{{ __('marks.inv_scan_first') }}">
                    </div>
                    <div class="form-group">
                        <label for="real_balance" class="form-label">{{ __('marks.inv_real_balance') }} *</label>
                        <input type="number" name="real_balance" id="real_balance" min="0" value="1" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="remarks" class="form-label">{{ __('marks.inv_remarks') }}</label>
                        <input type="text" name="remarks" id="remarks" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('marks.inv_log_check') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card shadow">
    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">{{ __('marks.inv_recent_scans') }}</h6></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead><tr><th>{{ __('marks.inv_item') }}</th><th>{{ __('marks.inv_inventory_no') }}</th><th>{{ __('marks.inv_session') }}</th><th>{{ __('marks.inv_status') }}</th><th>{{ __('marks.inv_when') }}</th></tr></thead>
                <tbody>
                    @forelse($recent ?? [] as $scan)
                        <tr>
                            <td>{{ $scan->item_name }}</td>
                            <td>{{ $scan->inventory_number }}</td>
                            <td>{{ $scan->session->session_name ?? __('marks.inv_na') }}</td>
                            <td>{{ ucfirst($scan->checked_status) }}</td>
                            <td>{{ $scan->checked_at ? $scan->checked_at->format('d M Y H:i') : __('marks.inv_na') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">{{ __('marks.inv_no_scans') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
function onScanSuccess(decodedText) {
    fetch("{{ route('admin.inventory.scanner.lookup') }}", {
        method: "POST",
        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
        body: JSON.stringify({ qr_data: decodedText })
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        var box = document.getElementById('scan-result');
        if (data.success) {
            document.getElementById('log_item_id').value = data.item.id;
            document.getElementById('log_item_name').value = data.item.item_name + ' (' + data.item.inventory_number + ')';
            box.innerHTML = '<div class="alert alert-success">' + box.dataset.found.replace(':name', data.item.item_name) + '</div>';
        } else {
            box.innerHTML = '<div class="alert alert-danger">' + (data.message || box.dataset.notfound) + '</div>';
        }
    });
}
new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 }).render(onScanSuccess);
</script>
@endsection
