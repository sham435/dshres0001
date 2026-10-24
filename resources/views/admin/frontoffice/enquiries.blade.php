@extends('layouts.admin')

@section('title', __('marks.fo_enquiries'))
@section('content')
@include('admin.frontoffice._tabs')
<div class="x-page-head">
    <h4>{{ __('marks.fo_enquiries') }} <small class="text-muted">({{ __('marks.fo_status') }}: {{ __('marks.inv_opt_pending') }} {{ $open ?? 0 }})</small></h4>
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
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">{{ __('marks.fo_new_enquiry') }}</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.front-office.enquiries.store') }}" method="POST">
                    @csrf
                    <div class="form-group"><label class="form-label">{{ __('marks.fo_name') }} *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">{{ __('marks.fo_phone') }} *</label><input type="text" name="phone" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">{{ __('marks.fo_email') }}</label><input type="email" name="email" class="form-control"></div>
                    <div class="form-group"><label class="form-label">{{ __('marks.fo_enquiry_for') }}</label><input type="text" name="enquiry_for" class="form-control"></div>
                    <div class="form-group"><label class="form-label">{{ __('marks.fo_message') }}</label><textarea name="message" class="form-control" rows="2"></textarea></div>
                    <div class="form-group"><label class="form-label">{{ __('marks.fo_follow_up') }}</label><input type="date" name="follow_up_date" class="form-control"></div>
                    <button type="submit" class="btn btn-primary">{{ __('marks.fo_save') }}</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card x-card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.front-office.enquiries') }}" class="form-row">
                    <div class="form-group col-md-5"><input type="text" name="search" class="form-control" placeholder="{{ __('marks.inv_search_ph') }}" value="{{ request('search') }}"></div>
                    <div class="form-group col-md-4">
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            <option value="">{{ __('marks.inv_all') }}</option>
                            @foreach(['new' => 'marks.fo_st_new', 'contacted' => 'marks.fo_st_contacted', 'admitted' => 'marks.fo_st_admitted', 'closed' => 'marks.fo_st_closed'] as $val => $key)
                                <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ __($key) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3"><button type="submit" class="btn btn-secondary">{{ __('marks.inv_search_btn') }}</button></div>
                </form>
            </div>
        </div>
        <div class="card x-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead><tr><th>{{ __('marks.fo_name') }}</th><th>{{ __('marks.fo_phone') }}</th><th>{{ __('marks.fo_enquiry_for') }}</th><th>{{ __('marks.fo_follow_up') }}</th><th>{{ __('marks.fo_status') }}</th><th></th></tr></thead>
                        <tbody>
                            @forelse($items ?? [] as $item)
                                <tr>
                                    <td>{{ $item->name }}<br><small class="text-muted">{{ $item->email }}</small></td>
                                    <td>{{ $item->phone }}</td>
                                    <td>{{ $item->enquiry_for ?? '-' }}</td>
                                    <td>{{ $item->follow_up_date ? $item->follow_up_date->format('d M Y') : '-' }}</td>
                                    <td>
                                        <form action="{{ route('admin.front-office.enquiries.update', $item->id) }}" method="POST" class="form-inline">
                                            @csrf @method('PUT')
                                            <select name="status" class="form-control form-control-sm mr-2" onchange="this.form.submit()" aria-label="{{ __('marks.fo_status') }}">
                                                @foreach(['new' => 'marks.fo_st_new', 'contacted' => 'marks.fo_st_contacted', 'admitted' => 'marks.fo_st_admitted', 'closed' => 'marks.fo_st_closed'] as $val => $key)
                                                    <option value="{{ $val }}" {{ $item->status === $val ? 'selected' : '' }}>{{ __($key) }}</option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.front-office.enquiries.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('marks.fo_delete_q') }}')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">{{ __('marks.fo_delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6"><div class="x-empty"><i class="fa fa-question-circle" aria-hidden="true"></i>{{ __('marks.fo_no_enquiries') }}</div></td></tr>
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
