@extends('layouts.admin')

@section('title', __('marks.stock_checks'))
@section('content')
@include('admin.inventory._tabs')
<div class="x-page-head">
    <h4>{{ __('marks.stock_checks') }}</h4>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
@endif

<div class="row">
    <div class="col-md-5 mb-4">
        <div class="card x-card">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">{{ __('marks.inv_new_session') }}</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.inventory.checks.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="session_name" class="form-label">{{ __('marks.inv_session_name') }} *</label>
                        <input type="text" name="session_name" id="session_name" class="form-control" value="{{ old('session_name', 'Stock Check ' . date('Y-m-d')) }}" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="check_date" class="form-label">{{ __('marks.inv_check_date') }} *</label>
                            <input type="date" name="check_date" id="check_date" class="form-control" value="{{ old('check_date', date('Y-m-d')) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="section_id" class="form-label">{{ __('marks.inv_limit_section') }}</label>
                            <select name="section_id" id="section_id" class="form-control">
                                <option value="">{{ __('marks.inv_all_snapshot') }}</option>
                                @foreach($sections ?? [] as $section)
                                    <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="conducted_by" class="form-label">{{ __('marks.inv_conducted_by') }}</label>
                        <input type="text" name="conducted_by" id="conducted_by" class="form-control" value="{{ old('conducted_by', auth()->user()->name) }}">
                    </div>
                    <div class="form-group">
                        <label for="notes" class="form-label">{{ __('marks.inv_notes') }}</label>
                        <textarea name="notes" id="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('marks.inv_start_session') }}</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7 mb-4">
        <div class="card x-card mb-4">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">{{ __('marks.inv_active_sessions') }}</h6></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead><tr><th>{{ __('marks.inv_session') }}</th><th>{{ __('marks.inv_date') }}</th><th>{{ __('marks.inv_by') }}</th><th></th></tr></thead>
                        <tbody>
                            @forelse($active ?? [] as $session)
                                <tr>
                                    <td>{{ $session->session_name }}</td>
                                    <td>{{ $session->check_date }}</td>
                                    <td>{{ $session->conducted_by ?? __('marks.inv_na') }}</td>
                                    <td class="text-nowrap">
                                        <a href="{{ route('admin.inventory.checks.show', $session->id) }}" class="btn btn-sm btn-info">{{ __('marks.inv_enter_counts') }}</a>
                                        <a href="{{ route('admin.inventory.checks.report', $session->id) }}" class="btn btn-sm btn-secondary">{{ __('marks.inv_report_btn') }}</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4"><div class="x-empty"><i class="fa fa-clipboard-check" aria-hidden="true"></i>{{ __('marks.inv_no_active') }}</div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card x-card">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">{{ __('marks.inv_history') }}</h6></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead><tr><th>{{ __('marks.inv_session') }}</th><th>{{ __('marks.inv_date') }}</th><th>{{ __('marks.inv_status') }}</th><th></th></tr></thead>
                        <tbody>
                            @forelse($history ?? [] as $session)
                                <tr>
                                    <td>{{ $session->session_name }}</td>
                                    <td>{{ $session->check_date }}</td>
                                    <td><span class="x-pill {{ $session->status === 'completed' ? 'x-pill-green' : 'x-pill-slate' }}"><i class="fa {{ $session->status === 'completed' ? 'fa-check' : 'fa-ban' }}" aria-hidden="true"></i>{{ __('marks.inv_st_' . $session->status) }}</span></td>
                                    <td><a href="{{ route('admin.inventory.checks.report', $session->id) }}" class="btn btn-sm btn-secondary">{{ __('marks.inv_report_btn') }}</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">{{ __('marks.inv_no_history') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(isset($history)){{ $history->links() }}@endif
            </div>
        </div>
    </div>
</div>
@endsection
