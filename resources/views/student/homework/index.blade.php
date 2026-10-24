@extends('layouts.app')

@section('title', __('marks.hw_homework'))
@section('content')
<div class="container">
    <div class="x-page-head">
        <h4>{{ __('marks.hw_homework') }}</h4>
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
                    <thead><tr><th>{{ __('marks.hw_title') }}</th><th>{{ __('marks.hw_subject') }}</th><th>{{ __('marks.hw_teacher') }}</th><th>{{ __('marks.hw_due_date') }}</th><th>{{ __('marks.hw_submissions') }}</th><th></th></tr></thead>
                    <tbody>
                        @forelse($items ?? [] as $item)
                            <tr>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->subject->name ?? __('marks.inv_na') }}</td>
                                <td>{{ $item->teacher->name ?? __('marks.inv_na') }}</td>
                                <td>{{ $item->due_date ? $item->due_date->format('d M Y') : '-' }}@if($item->due_date && $item->due_date->isPast()) <span class="x-pill x-pill-red">{{ __('marks.hw_overdue') }}</span>@endif</td>
                                <td>@php $mine = $item->submissionFor(auth()->id()); @endphp
                                    @if($mine)<span class="x-pill x-pill-green"><i class="fa fa-check" aria-hidden="true"></i>{{ __('marks.hw_submitted') }}</span>
                                    @else<span class="x-pill x-pill-amber"><i class="fa fa-clock" aria-hidden="true"></i>{{ __('marks.hw_not_submitted') }}</span>@endif</td>
                                <td><a href="{{ route('student.homework.show', $item->id) }}" class="btn btn-sm btn-info">{{ __('marks.hw_view') }}</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6"><div class="x-empty"><i class="fa fa-book-open" aria-hidden="true"></i>{{ __('marks.hw_no_homework') }}</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($items)){{ $items->links() }}@endif
        </div>
    </div>
</div>
@endsection
