@extends('layouts.admin')

@section('title', __('marks.applications'))
@section('content')
@include('admin.admissions._tabs')
<div class="x-page-head">
    <h4>{{ __('marks.applications') }}</h4>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
@endif

<div class="row mb-4">
    @foreach(['pending' => ['amber', 'marks.inv_opt_pending'], 'approved' => ['green', 'marks.adm_opt_approved'], 'rejected' => ['red', 'marks.adm_opt_rejected']] as $status => [$tone, $labelKey])
        <div class="col-md-4">
            <a href="{{ route('admin.admissions.applications', ['status' => $status]) }}" class="text-decoration-none">
                <div class="x-stat shadow py-2"><div class="card-body"><div class="x-stat-label">{{ __($labelKey) }}</div><div class="x-stat-value">{{ number_format($counts[$status] ?? 0) }}</div></div></div>
            </a>
        </div>
    @endforeach
</div>

<div class="card x-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.admissions.applications') }}" class="form-row">
            <div class="form-group col-md-4">
                <label for="search" class="form-label">{{ __('marks.inv_search_btn') }}</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="{{ __('marks.adm_search_ph') }}" value="{{ request('search') }}">
            </div>
            <div class="form-group col-md-3">
                <label for="status" class="form-label">{{ __('marks.inv_status') }}</label>
                <select name="status" id="status" class="form-control" onchange="this.form.submit()">
                    <option value="">{{ __('marks.inv_all') }}</option>
                    @foreach(['pending' => 'marks.inv_opt_pending', 'approved' => 'marks.adm_opt_approved', 'rejected' => 'marks.adm_opt_rejected'] as $val => $key)
                        <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ __($key) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-secondary">{{ __('marks.inv_filter') }}</button>
            </div>
        </form>
    </div>
</div>

<div class="card x-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead><tr><th>#</th><th>{{ __('marks.adm_applicant') }}</th><th>{{ __('marks.adm_class') }}</th><th>{{ __('marks.adm_guardian') }}</th><th>{{ __('marks.adm_applied') }}</th><th>{{ __('marks.inv_status') }}</th><th></th></tr></thead>
                <tbody>
                    @forelse($applications ?? [] as $app)
                        <tr>
                            <td>{{ $loop->iteration + ($applications->currentPage() - 1) * $applications->perPage() }}</td>
                            <td>{{ $app->first_name }} {{ $app->last_name }}<br><small class="text-muted">{{ $app->email }}</small></td>
                            <td>{{ $app->class->name ?? __('marks.inv_na') }} ({{ $app->academic_year }})</td>
                            <td>{{ $app->guardian_name }}<br><small class="text-muted">{{ $app->guardian_phone }}</small></td>
                            <td>{{ $app->created_at ? $app->created_at->format('d M Y') : 'N/A' }}</td>
                            <td><span class="x-pill {{ $app->status === 'approved' ? 'x-pill-green' : ($app->status === 'rejected' ? 'x-pill-red' : 'x-pill-amber') }}"><i class="fa {{ $app->status === 'pending' ? 'fa-clock' : ($app->status === 'approved' ? 'fa-check' : 'fa-times') }}" aria-hidden="true"></i>{{ __('marks.' . ($app->status === 'approved' ? 'adm_opt_approved' : ($app->status === 'rejected' ? 'adm_opt_rejected' : 'inv_opt_pending'))) }}</span></td>
                            <td><a href="{{ route('admin.admissions.show', $app->id) }}" class="btn btn-sm btn-info">{{ __('marks.adm_review') }}</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><div class="x-empty"><i class="fa fa-file-signature" aria-hidden="true"></i>{{ __('marks.adm_no_apps') }}</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($applications)){{ $applications->links() }}@endif
    </div>
</div>
@endsection
