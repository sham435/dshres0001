@extends('layouts.frontend')
@section('title', 'Result')
@section('heading', 'Exam Result')
@section('content')
<section class="section"><div class="container"><div class="row justify-content-center"><div class="col-lg-8">
  <div class="card card-hover"><div class="card-body p-4">
    <form method="POST" action="{{ route('result') }}" class="row g-3">
      @csrf
      <div class="col-md-12">
        <label class="form-label">Exam *</label>
        <select name="exam_id" class="form-select @error('exam_id') is-invalid @enderror" required>
          <option value="">Select exam</option>
          @foreach ($exams as $e)
            <option value="{{ $e->id }}" {{ (old('exam_id') == $e->id) ? 'selected' : '' }}>{{ $e->name }}</option>
          @endforeach
        </select>
        @error('exam_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label class="form-label">Username or Email *</label>
        <input name="student" value="{{ old('student') }}" class="form-control @error('student') is-invalid @enderror" required>
        @error('student')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label class="form-label">Date of Birth *</label>
        <input type="date" name="dob" value="{{ old('dob') }}" class="form-control @error('dob') is-invalid @enderror" required>
        @error('dob')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-12"><button class="btn btn-brand px-4">Search</button></div>
    </form>
  </div></div>

  @if ($marks)
  <div class="card mt-4 border-0 shadow-sm"><div class="card-body p-4">
    <h5 class="mb-0">{{ $student->name }}</h5>
    <p class="text-muted">{{ $exam->name ?? '' }}</p>
    <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead><tr><th>Subject</th><th class="text-end">Marks</th><th class="text-end">Total</th><th class="text-end">Grade</th></tr></thead>
      <tbody>
        @foreach ($marks as $m)
        <tr><td>{{ $m->subject }}</td><td class="text-end">{{ $m->marks_obtained }}</td><td class="text-end">{{ $m->total_marks }}</td><td class="text-end fw-bold">{{ $m->grade }}</td></tr>
        @endforeach
      </tbody>
      <tfoot><tr class="fw-bold"><td>Total</td><td class="text-end">{{ $marks->sum('marks_obtained') }}</td><td class="text-end">{{ $marks->sum('total_marks') }}</td><td></td></tr></tfoot>
    </table>
    </div>
  </div></div>
  @endif
</div></div></div></section>
@endsection
