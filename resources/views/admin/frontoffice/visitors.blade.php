@extends('layouts.admin')

@section('title', __('marks.fo_visitors'))
@section('content')
@include('admin.frontoffice._tabs')
<div class="x-page-head">
    <h4>{{ __('marks.fo_visitors') }} <small class="text-muted">({{ __('marks.fo_inside_now') }}: {{ $inside ?? 0 }})</small></h4>
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
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">{{ __('marks.fo_new_visitor') }}</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.front-office.visitors.store') }}" method="POST">
                    @csrf
                    <div class="form-group"><label class="form-label">{{ __('marks.fo_name') }} *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">{{ __('marks.fo_phone') }}</label><input type="text" name="phone" class="form-control"></div>
                    <div class="form-group"><label class="form-label">{{ __('marks.fo_purpose') }} *</label><input type="text" name="purpose" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">{{ __('marks.fo_meet') }}</label><input type="text" name="person_to_meet" class="form-control"></div>
                    <div class="form-group"><label class="form-label">{{ __('marks.fo_visit_date') }} *</label><input type="date" name="visit_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                    <div class="form-group"><label class="form-label">{{ __('marks.fo_time_in') }}</label><input type="time" name="time_in" class="form-control"></div>
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
                        <thead><tr><th>{{ __('marks.fo_name') }}</th><th>{{ __('marks.fo_purpose') }}</th><th>{{ __('marks.fo_meet') }}</th><th>{{ __('marks.fo_time_in') }} / {{ __('marks.fo_time_out') }}</th><th></th></tr></thead>
                        <tbody>
                            @forelse($items ?? [] as $item)
                                <tr>
                                    <td>{{ $item->name }}<br><small class="text-muted">{{ $item->phone }} · {{ $item->visit_date }}</small></td>
                                    <td>{{ $item->purpose }}</td>
                                    <td>{{ $item->person_to_meet ?? '-' }}</td>
                                    <td>{{ $item->time_in ?? '-' }} / {{ $item->time_out ?? '-' }}</td>
                                    <td class="text-nowrap">
                                        @if(!$item->time_out)
                                            <form action="{{ route('admin.front-office.visitors.sign-out', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">{{ __('marks.fo_sign_out') }}</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.front-office.visitors.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('marks.fo_delete_q') }}')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">{{ __('marks.fo_delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5"><div class="x-empty"><i class="fa fa-walking" aria-hidden="true"></i>{{ __('marks.fo_no_visitors') }}</div></td></tr>
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
