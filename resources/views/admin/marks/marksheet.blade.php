@extends('layouts.app')

@section('title', 'Marksheet')

@section('content')
<div class="container">
    <div class="x-page-head">
    <h4>Student Marksheet</h4>
    <div class="actions">
<a href="javascript:window.print()" class="btn btn-primary">
            <i class="fa fa-print"></i> Print
        </a>
    </div>
</div>

    <div class="card shadow">
        <div class="card-header bg-light">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="mb-1"><strong>Student Name:</strong> {{ $student->name }}</h6>
                    <h6 class="mb-1"><strong>Email:</strong> {{ $student->email }}</h6>
                    @if($student->phone)
                        <h6 class="mb-0"><strong>Phone:</strong> {{ $student->phone }}</h6>
                    @endif
                </div>
                <div class="col-md-6 text-md-end">
                    @if($student->gender)
                        <h6 class="mb-1"><strong>Gender:</strong> {{ ucfirst($student->gender) }}</h6>
                    @endif
                    @if($student->address)
                        <h6 class="mb-0"><strong>Address:</strong> {{ $student->address }}</h6>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($marks->count() > 0)
                @foreach($exams as $examId => $examMarks)
                    @php $exam = $examMarks->first()->exam; @endphp
                    <div class="mb-4">
                        <h5 class="text-primary border-bottom pb-2">
                            {{ $exam->name ?? 'Exam #' . $examId }}
                            @if($exam && $exam->date)
                                <small class="text-muted">({{ \Carbon\Carbon::parse($exam->date)->format('d M Y') }})</small>
                            @endif
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Subject</th>
                                        <th>Marks Obtained</th>
                                        <th>Total Marks</th>
                                        <th>Percentage</th>
                                        <th>Grade</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalObtained = 0;
                                        $totalMax = 0;
                                    @endphp
                                    @foreach($examMarks as $mark)
                                        @php
                                            $totalObtained += $mark->marks_obtained ?? 0;
                                            $totalMax += $mark->total_marks ?? 0;
                                            $percentage = $mark->total_marks > 0
                                                ? round(($mark->marks_obtained / $mark->total_marks) * 100, 2)
                                                : 0;
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $mark->subject->name ?? 'Subject #' . $mark->subject_id }}</td>
                                            <td>{{ $mark->marks_obtained }}</td>
                                            <td>{{ $mark->total_marks }}</td>
                                            <td>{{ $percentage }}%</td>
                                            <td>
                                                <span class="badge bg-info">{{ $mark->grade ?? 'N/A' }}</span>
                                            </td>
                                            <td>{{ $mark->remarks ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold table-secondary">
                                        <td colspan="2">Total</td>
                                        <td>{{ $totalObtained }}</td>
                                        <td>{{ $totalMax }}</td>
                                        <td>
                                            {{ $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0 }}%
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-5">
                    <i class="fa fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">No marks available for this student yet.</p>
                </div>
            @endif
        </div>
    </div>
    </div>
</div>
@endsection
