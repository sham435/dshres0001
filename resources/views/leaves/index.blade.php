@extends('layouts.app')

@section('title', __('marks.lv_title'))
@section('content')
<div class="container">
    <div class="x-page-head">
        <h4>{{ ($isAdmin ?? false) ? __('marks.lv_title') : __('marks.lv_my_leaves') }}</h4>
        <div class="actions">
            @if(!empty($createUrl))
                <a href="{{ $createUrl }}" class="btn btn-primary">{{ __('marks.lv_apply') }}</a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    @if(($isAdmin ?? false) && ($pending ?? 0) > 0)
        <div class="alert alert-warning">{{ __('marks.inv_opt_pending') }}: {{ $pending }}</div>
    @endif

    <div class="card x-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead><tr>
                        @if($isAdmin ?? false)<th>{{ __('marks.lv_applicant') }}</th>@endif
                        <th>{{ __('marks.lv_type') }}</th><th>{{ __('marks.lv_from') }}</th><th>{{ __('marks.lv_to') }}</th><th>{{ __('marks.lv_days') }}</th><th>{{ __('marks.lv_status') }}</th>
                        @if($isAdmin ?? false)<th></th>@endif
                    </tr></thead>
                    <tbody>
                        @forelse($items ?? [] as $app)
                            <tr>
                                @if($isAdmin ?? false)<td>{{ $app->user->name ?? 'N/A' }}</td>@endif
                                <td>{{ $app->type->name ?? 'N/A' }}</td>
                                <td>{{ $app->from_date ? $app->from_date->format('d M Y') : '' }}</td>
                                <td>{{ $app->to_date ? $app->to_date->format('d M Y') : '' }}</td>
                                <td class="table-numeric">{{ $app->days() }}</td>
                                <td><span class="x-pill {{ $app->status === 'approved' ? 'x-pill-green' : ($app->status === 'rejected' ? 'x-pill-red' : 'x-pill-amber') }}"><i class="fa {{ $app->status === 'pending' ? 'fa-clock' : ($app->status === 'approved' ? 'fa-check' : 'fa-times') }}" aria-hidden="true"></i>{{ $app->status === 'approved' ? __('marks.adm_opt_approved') : ($app->status === 'rejected' ? __('marks.adm_opt_rejected') : __('marks.inv_opt_pending')) }}</span></td>
                                @if($isAdmin ?? false)
                                    <td>
                                        @if($app->status === 'pending')
                                            <form action="{{ route('admin.leaves.review', $app->id) }}" method="POST" class="form-inline">
                                                @csrf
                                                <button type="submit" name="decision" value="approved" class="btn btn-sm btn-success mr-1">{{ __('marks.lv_approve') }}</button>
                                                <button type="submit" name="decision" value="rejected" class="btn btn-sm btn-danger">{{ __('marks.lv_reject') }}</button>
                                            </form>
                                        @else
                                            <small class="text-muted">{{ __('marks.lv_reviewed_by') }}: {{ $app->reviewer->name ?? '-' }}</small>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr><td colspan="7"><div class="x-empty"><i class="fa fa-calendar-times" aria-hidden="true"></i>{{ __('marks.lv_no_leaves') }}</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($items)){{ $items->links() }}@endif
        </div>
    </div>
</div>
@endsection
