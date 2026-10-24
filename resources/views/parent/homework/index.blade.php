@extends('layouts.app')

@section('title', __('marks.hw_homework'))
@section('content')
<div class="container">
    <div class="x-page-head">
        <h4>{{ __('marks.hw_homework') }} — {{ $student->name }}</h4>
    </div>

    <div class="card x-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead><tr><th>{{ __('marks.hw_title') }}</th><th>{{ __('marks.hw_subject') }}</th><th>{{ __('marks.hw_due_date') }}</th><th>{{ __('marks.hw_submissions') }}</th></tr></thead>
                    <tbody>
                        @forelse($items ?? [] as $item)
                            <tr>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->subject->name ?? __('marks.inv_na') }}</td>
                                <td>{{ $item->due_date ? $item->due_date->format('d M Y') : '-' }}</td>
                                <td>@php $mine = $item->submissionFor($student->id); @endphp
                                    @if($mine)<span class="x-pill x-pill-green"><i class="fa fa-check" aria-hidden="true"></i>{{ __('marks.hw_submitted') }}@if($mine->grade) ({{ $mine->grade }})@endif</span>
                                    @else<span class="x-pill x-pill-amber"><i class="fa fa-clock" aria-hidden="true"></i>{{ __('marks.hw_not_submitted') }}</span>@endif</td>
                            </tr>
                        @empty
                            <tr><td colspan="4"><div class="x-empty"><i class="fa fa-book-open" aria-hidden="true"></i>{{ __('marks.hw_no_homework') }}</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($items)){{ $items->links() }}@endif
        </div>
    </div>
</div>
@endsection
