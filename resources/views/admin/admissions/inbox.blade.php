@extends('layouts.admin')

@section('title', __('marks.contact_inbox'))
@section('content')
@include('admin.admissions._tabs')
<div class="x-page-head">
    <h4>{{ __('marks.contact_inbox') }} <small class="text-muted">({{ __('marks.adm_unread', ['count' => $unread ?? 0]) }})</small></h4>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
@endif

<div class="card x-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead><tr><th></th><th>{{ __('marks.adm_from') }}</th><th>{{ __('marks.adm_subject') }}</th><th>{{ __('marks.adm_received') }}</th><th></th></tr></thead>
                <tbody>
                    @forelse($messages ?? [] as $message)
                        <tr class="{{ $message->is_read ? '' : 'font-weight-bold' }}">
                            <td>{!! $message->is_read ? '<i class="fa fa-envelope-open text-muted" aria-hidden="true"></i>' : '<i class="fa fa-envelope text-primary" aria-hidden="true"></i>' !!}</td>
                            <td>{{ $message->name }}<br><small class="text-muted">{{ $message->email }}</small></td>
                            <td>{{ $message->subject }}</td>
                            <td>{{ $message->created_at ? $message->created_at->format('d M Y H:i') : 'N/A' }}</td>
                            <td class="text-nowrap">
                                <a href="{{ route('admin.admissions.message', $message->id) }}" class="btn btn-sm btn-info">{{ __('marks.adm_open') }}</a>
                                <form action="{{ route('admin.admissions.message-destroy', $message->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('marks.adm_del_msg_q') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">{{ __('marks.adm_delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="x-empty"><i class="fa fa-inbox" aria-hidden="true"></i>{{ __('marks.adm_inbox_empty') }}</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($messages)){{ $messages->links() }}@endif
    </div>
</div>
@endsection
