@extends('layouts.admin')

@section('title', __('marks.fo_complaints'))
@section('content')
@include('admin.frontoffice._tabs')
<div class="x-page-head">
    <h4>{{ __('marks.fo_complaints') }} <small class="text-muted">({{ __('marks.inv_opt_pending') }}: {{ $open ?? 0 }})</small></h4>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
@endif

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card x-card">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">{{ __('marks.fo_new_complaint') }}</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.front-office.complaints.store') }}" method="POST">
                    @csrf
                    <div class="form-group"><label class="form-label">{{ __('marks.fo_complainant') }} *</label><input type="text" name="complainant" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">{{ __('marks.fo_phone') }}</label><input type="text" name="phone" class="form-control"></div>
                    <div class="form-group"><label class="form-label">{{ __('marks.fo_subject') }} *</label><input type="text" name="subject" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">{{ __('marks.fo_description') }} *</label><textarea name="description" class="form-control" rows="3" required></textarea></div>
                    <button type="submit" class="btn btn-primary">{{ __('marks.fo_save') }}</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card x-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead><tr><th>{{ __('marks.fo_complainant') }}</th><th>{{ __('marks.fo_subject') }}</th><th>{{ __('marks.fo_status') }}</th><th></th></tr></thead>
                        <tbody>
                            @forelse($items ?? [] as $item)
                                <tr>
                                    <td>{{ $item->complainant }}<br><small class="text-muted">{{ $item->phone }}</small></td>
                                    <td><strong>{{ $item->subject }}</strong><br><small class="text-muted">{{ \Illuminate\Support\Str::limit($item->description, 80) }}</small>
                                        @if($item->resolution)<br><small class="text-success">{{ __('marks.fo_resolution') }}: {{ $item->resolution }}</small>@endif</td>
                                    <td>
                                        <form action="{{ route('admin.front-office.complaints.update', $item->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <select name="status" class="form-control form-control-sm mb-2" onchange="this.form.submit()" aria-label="{{ __('marks.fo_status') }}">
                                                @foreach(['open' => 'marks.fo_c_open', 'in_review' => 'marks.fo_c_review', 'resolved' => 'marks.fo_c_resolved', 'closed' => 'marks.fo_c_closed'] as $val => $key)
                                                    <option value="{{ $val }}" {{ $item->status === $val ? 'selected' : '' }}>{{ __($key) }}</option>
                                                @endforeach
                                            </select>
                                            <input type="text" name="resolution" value="{{ $item->resolution }}" placeholder="{{ __('marks.fo_resolution') }}" class="form-control form-control-sm">
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.front-office.complaints.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('marks.fo_delete_q') }}')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">{{ __('marks.fo_delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4"><div class="x-empty"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{ __('marks.fo_no_complaints') }}</div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(isset($items)){{ $items->links() }}@endif
            </div>
        </div>
    </div>
</div>
@endsection
