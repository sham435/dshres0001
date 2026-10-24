@extends('layouts.app')

@section('title', __('marks.hw_submissions'))
@section('content')
<div class="container">
    <div class="x-page-head">
        <h4>{{ $item->title }} — {{ __('marks.hw_submissions') }}</h4>
        <div class="actions">
            <a href="{{ route('teacher.homework.index') }}" class="btn btn-secondary">{{ __('marks.hw_back') }}</a>
        </div>
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
                    <thead><tr><th>{{ __('marks.hw_student') }}</th><th>{{ __('marks.hw_note') }}</th><th>{{ __('marks.hw_file') }}</th><th>{{ __('marks.hw_submitted_on') }}</th><th>{{ __('marks.hw_grade') }}</th></tr></thead>
                    <tbody>
                        @forelse($subs ?? [] as $sub)
                            <tr>
                                <td>{{ $sub->student->name ?? 'N/A' }}</td>
                                <td>{{ $sub->note ?? '-' }}</td>
                                <td>@if($sub->file_path)<a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" class="btn btn-sm btn-info">{{ __('marks.hw_view') }}</a>@else - @endif</td>
                                <td>{{ $sub->created_at ? $sub->created_at->format('d M Y H:i') : 'N/A' }}</td>
                                <td>
                                    <form action="{{ route('teacher.homework.grade', [$item->id, $sub->id]) }}" method="POST" class="form-inline">
                                        @csrf
                                        <input type="text" name="grade" value="{{ $sub->grade }}" class="form-control form-control-sm mr-2" style="width: 80px;" aria-label="{{ __('marks.hw_grade') }}">
                                        <button type="submit" class="btn btn-sm btn-success">{{ __('marks.hw_save_grade') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5"><div class="x-empty"><i class="fa fa-inbox" aria-hidden="true"></i>{{ __('marks.hw_no_submissions') }}</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($subs)){{ $subs->links() }}@endif
        </div>
    </div>
</div>
@endsection
