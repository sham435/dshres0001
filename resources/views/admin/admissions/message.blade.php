@extends('layouts.admin')

@section('title', __('marks.adm_view_message'))
@section('content')
@include('admin.admissions._tabs')
<div class="x-page-head">
    <h4>{{ $message->subject }}</h4>
    <div class="actions">
        <a href="{{ route('admin.admissions.inbox') }}" class="btn btn-secondary">{{ __('marks.adm_back_inbox') }}</a>
    </div>
</div>

<div class="card x-card">
    <div class="card-header py-3">
        <strong>{{ $message->name }}</strong> <span class="text-muted">&lt;{{ $message->email }}&gt;</span>
        <span class="float-right text-muted">{{ $message->created_at ? $message->created_at->format('d M Y H:i') : '' }}</span>
    </div>
    <div class="card-body">
        <p style="white-space: pre-wrap;">{{ $message->message }}</p>
        <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject }}" class="btn btn-primary">{{ __('marks.adm_reply_email') }}</a>
        <form action="{{ route('admin.admissions.message-destroy', $message->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('marks.adm_del_msg_q') }}')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">{{ __('marks.adm_delete') }}</button>
        </form>
    </div>
</div>
@endsection
