@extends('layouts.app')

@section('title', __('marks.hw_homework'))
@section('content')
<div class="container">
    <div class="x-page-head">
        <h4>{{ __('marks.hw_homework') }}</h4>
        <div class="actions">
            <a href="{{ route('teacher.homework.create') }}" class="btn btn-primary">{{ __('marks.hw_new') }}</a>
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
                    <thead><tr><th>{{ __('marks.hw_title') }}</th><th>{{ __('marks.hw_subject') }}</th><th>{{ __('marks.hw_class') }}</th><th>{{ __('marks.hw_due_date') }}</th><th>{{ __('marks.hw_submissions') }}</th><th></th></tr></thead>
                    <tbody>
                        @forelse($items ?? [] as $item)
                            <tr>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->subject->name ?? __('marks.inv_na') }}</td>
                                <td>{{ $item->class->name ?? __('marks.hw_all_classes') }}</td>
                                <td>{{ $item->due_date ? $item->due_date->format('d M Y') : '-' }}@if($item->due_date && $item->due_date->isPast()) <span class="x-pill x-pill-red">{{ __('marks.hw_overdue') }}</span>@endif</td>
                                <td class="table-numeric">{{ $item->submissions_count }}</td>
                                <td class="text-nowrap">
                                    <a href="{{ route('teacher.homework.submissions', $item->id) }}" class="btn btn-sm btn-info">{{ __('marks.hw_submissions') }}</a>
                                    <a href="{{ route('teacher.homework.edit', $item->id) }}" class="btn btn-sm btn-warning">{{ __('marks.hw_edit') }}</a>
                                    <form action="{{ route('teacher.homework.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('marks.hw_delete_q') }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">{{ __('marks.hw_delete') }}</button>
                                    </form>
                                </td>
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
