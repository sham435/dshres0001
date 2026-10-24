@extends('layouts.app')

@section('content')
<div class="container">
    <div class="x-page-head">
    <h4>Librarian Dashboard</h4>
    <div class="actions">
<span class="text-muted">Welcome, {{ auth()->user()->name ?? 'Librarian' }}</span>
    </div>
</div>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="x-stat shadow h-100 py-2" style="border-left-color: var(--x-primary);">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="x-stat-label">Total Books</div>
                            <div class="x-stat-value">{{ number_format($totalBooks ?? 0) }}</div>
                        </div>
                        <div class="col-auto" aria-hidden="true">
                            <i class="fa fa-book x-stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="x-stat shadow h-100 py-2" style="border-left-color: var(--x-accent);">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="x-stat-label">Total Quantity</div>
                            <div class="x-stat-value">{{ number_format($totalQuantity ?? 0) }}</div>
                        </div>
                        <div class="col-auto" aria-hidden="true">
                            <i class="fa fa-copy x-stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="x-stat shadow h-100 py-2" style="border-left-color: #0284c7;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="x-stat-label">Available Books</div>
                            <div class="x-stat-value">{{ number_format($availableBooks ?? 0) }}</div>
                        </div>
                        <div class="col-auto" aria-hidden="true">
                            <i class="fa fa-check-circle x-stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fa fa-plus-circle fa-3x text-success mb-3"></i>
                    <h5>Add Book</h5>
                    <p class="text-muted">Add a new book to the library.</p>
                    <a href="{{ route('librarian.books.create') ?? '#' }}" class="btn btn-success">Add Book</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fa fa-list fa-3x text-primary mb-3"></i>
                    <h5>Manage Books</h5>
                    <p class="text-muted">View, edit, or remove books.</p>
                    <a href="{{ route('librarian.books.index') ?? '#' }}" class="btn btn-primary">Manage Books</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fa fa-calendar-times fa-3x text-warning mb-3"></i>
                    <h5>{{ __('marks.lv_title') }}</h5>
                    <p class="text-muted">{{ __('marks.lv_apply') }}</p>
                    <a href="{{ route('librarian.leaves.index') }}" class="btn btn-warning">{{ __('marks.lv_title') }}</a>
                </div>
            </div>
        </div>
    </div>

    @if(isset($recentBooks) && count($recentBooks) > 0)
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Recently Added Books</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>ISBN</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentBooks as $book)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $book->title ?? 'N/A' }}</td>
                                <td>{{ $book->author ?? 'N/A' }}</td>
                                <td>{{ $book->isbn ?? 'N/A' }}</td>
                                <td>{{ $book->quantity ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
    </div>
</div>
@endsection
