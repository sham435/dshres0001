@extends('layouts.admin')

@section('title', 'Create Book')
@section('content')
<div class="x-page-head">
    <h4>Create Book</h4>
    <div class="actions">
<a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.books.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="author" class="form-label">Author</label>
                <input type="text" name="author" id="author" class="form-control @error('author') is-invalid @enderror" value="{{ old('author') }}" required>
                @error('author') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="publisher" class="form-label">Publisher</label>
                <input type="text" name="publisher" id="publisher" class="form-control @error('publisher') is-invalid @enderror" value="{{ old('publisher') }}">
                @error('publisher') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" name="isbn" id="isbn" class="form-control @error('isbn') is-invalid @enderror" value="{{ old('isbn') }}">
                @error('isbn') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" required>
                @error('quantity') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="available_quantity" class="form-label">Available Quantity</label>
                <input type="number" name="available_quantity" id="available_quantity" class="form-control @error('available_quantity') is-invalid @enderror" value="{{ old('available_quantity') }}" required>
                @error('available_quantity') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary">Create Book</button>
        </form>
    </div>
</div>
@endsection
