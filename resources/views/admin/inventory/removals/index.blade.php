@extends('layouts.admin')

@section('title', __('marks.removals'))
@section('content')
@include('admin.inventory._tabs')
<div class="x-page-head">
    <h4>{{ __('marks.inv_removal_workflow') }}</h4>
    <div class="actions">
        <a href="{{ route('admin.inventory.removals.pending-csv') }}" class="btn btn-sm btn-success">{{ __('marks.inv_pending_csv') }}</a>
        <a href="{{ route('admin.inventory.removals.archive-csv') }}" class="btn btn-sm btn-secondary">{{ __('marks.inv_archive_csv') }}</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
@endif

@php $tab = $tab ?? 'inventory'; @endphp
<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item"><a class="nav-link {{ $tab === 'inventory' ? 'active' : '' }}" href="{{ route('admin.inventory.removals.index', ['tab' => 'inventory']) }}">{{ __('marks.inv_tab_inventory') }}</a></li>
    <li class="nav-item"><a class="nav-link {{ $tab === 'pending' ? 'active' : '' }}" href="{{ route('admin.inventory.removals.index', ['tab' => 'pending']) }}">{{ __('marks.inv_tab_pending') }} ({{ $pending->total() ?? 0 }})</a></li>
    <li class="nav-item"><a class="nav-link {{ $tab === 'archive' ? 'active' : '' }}" href="{{ route('admin.inventory.removals.index', ['tab' => 'archive']) }}">{{ __('marks.inv_tab_archive') }}</a></li>
</ul>

@if($tab === 'inventory')
    <div class="card x-card"><div class="card-body">
        <p class="text-muted">{{ __('marks.inv_removal_intro') }}</p>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead><tr><th>{{ __('marks.inv_item') }}</th><th>{{ __('marks.inv_inventory_no') }}</th><th>{{ __('marks.inv_condition') }}</th><th>{{ __('marks.inv_recommend') }}</th></tr></thead>
                <tbody>
                    @forelse($eligible ?? [] as $item)
                        <tr>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->inventory_number }}</td>
                            <td>{{ __('marks.inv_cond_' . $item->condition_status) }}</td>
                            <td>
                                <form action="{{ route('admin.inventory.removals.recommend') }}" method="POST" class="form-inline">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                                    <input type="text" name="reason" class="form-control form-control-sm mr-2" placeholder="{{ __('marks.inv_reason_ph') }} *" required aria-label="Removal reason">
                                    <button type="submit" class="btn btn-sm btn-warning">{{ __('marks.inv_recommend_btn') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><div class="x-empty"><i class="fa fa-check-circle" aria-hidden="true"></i>{{ __('marks.inv_nothing_eligible') }}</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($eligible)){{ $eligible->links() }}@endif
    </div></div>
@elseif($tab === 'pending')
    <div class="card x-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead><tr><th>{{ __('marks.inv_item') }}</th><th>{{ __('marks.inv_inventory_no') }}</th><th>{{ __('marks.inv_reason') }}</th><th>{{ __('marks.inv_by') }}</th><th>{{ __('marks.inv_decide') }}</th></tr></thead>
                <tbody>
                    @forelse($pending ?? [] as $rec)
                        <tr>
                            <td>{{ $rec->item_name }}</td>
                            <td>{{ $rec->inventory_number }}</td>
                            <td>{{ $rec->recommendation_reason }}</td>
                            <td>{{ $rec->recommended_by ?? __('marks.inv_na') }}</td>
                            <td>
                                <form action="{{ route('admin.inventory.removals.decide', $rec->id) }}" method="POST" class="form-inline">
                                    @csrf
                                    <input type="text" name="disposal_method" class="form-control form-control-sm mr-2" placeholder="{{ __('marks.inv_disposal_ph') }}" aria-label="Disposal method">
                                    <button type="submit" name="decision" value="approve" class="btn btn-sm btn-success mr-1" onclick="return confirm('{{ __('marks.inv_approve_confirm') }}')">{{ __('marks.inv_approve') }}</button>
                                    <button type="submit" name="decision" value="reject" class="btn btn-sm btn-secondary">{{ __('marks.inv_reject') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="x-empty"><i class="fa fa-inbox" aria-hidden="true"></i>{{ __('marks.inv_no_pending') }}</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($pending)){{ $pending->links() }}@endif
    </div></div>
@else
    <div class="card x-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead><tr><th>{{ __('marks.inv_item') }}</th><th>{{ __('marks.inv_inventory_no') }}</th><th>{{ __('marks.inv_reason') }}</th><th>{{ __('marks.inv_removed') }}</th><th>{{ __('marks.inv_disposal_ph') }}</th><th></th></tr></thead>
                <tbody>
                    @forelse($archive ?? [] as $row)
                        <tr>
                            <td>{{ $row->item_name }}</td>
                            <td>{{ $row->inventory_number }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($row->removal_reason, 60) }}</td>
                            <td>{{ $row->removal_date }}</td>
                            <td>{{ $row->disposal_method ?? __('marks.inv_na') }}</td>
                            <td>
                                <form action="{{ route('admin.inventory.removals.archive-destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('marks.inv_perm_delete_confirm') }}')">
                                    @csrf @method('DELETE')
                                    <input type="hidden" name="confirm_delete" value="1">
                                    <button type="submit" class="btn btn-sm btn-danger">{{ __('marks.delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="x-empty"><i class="fa fa-archive" aria-hidden="true"></i>{{ __('marks.inv_archive_empty') }}</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($archive)){{ $archive->links() }}@endif
    </div></div>
@endif
@endsection
