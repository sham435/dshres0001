@extends('layouts.admin')

@section('title', 'Review Application')
@section('content')
@include('admin.admissions._tabs')
<div class="x-page-head">
    <h4>{{ $application->first_name }} {{ $application->last_name }}
        <span class="x-pill {{ $application->status === 'approved' ? 'x-pill-green' : ($application->status === 'rejected' ? 'x-pill-red' : 'x-pill-amber') }}">{{ ucfirst($application->status) }}</span>
    </h4>
    <div class="actions">
        <a href="{{ route('admin.admissions.applications') }}" class="btn btn-secondary">{{ __('marks.inv_back') }}</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card x-card mb-4">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">{{ __('marks.adm_student_details') }}</h6></div>
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-bordered">
                    <tr><th style="width: 200px;">{{ __('marks.inv_name') }}</th><td>{{ $application->first_name }} {{ $application->last_name }}</td></tr>
                    <tr><th>{{ __('marks.adm_dob') }}</th><td>{{ $application->dob ? $application->dob->format('d M Y') : 'N/A' }}</td></tr>
                    <tr><th>{{ __('marks.adm_gender') }}</th><td>{{ $application->gender ? __('marks.adm_g_' . $application->gender) : __('marks.inv_na') }}</td></tr>
                    <tr><th>{{ __('marks.adm_phone_email') }}</th><td>{{ $application->phone ?? __('marks.inv_na') }} / {{ $application->email }}</td></tr>
                    <tr><th>{{ __('marks.adm_address') }}</th><td>{{ $application->address ?? __('marks.inv_na') }}</td></tr>
                    <tr><th>{{ __('marks.adm_class_applied') }}</th><td>{{ $application->class->name ?? __('marks.inv_na') }} ({{ $application->academic_year }}){{ $application->shift ? ', ' . $application->shift : '' }}</td></tr>
                    <tr><th>{{ __('marks.adm_religion_nat') }}</th><td>{{ $application->religion ?? __('marks.inv_na') }} / {{ $application->nationality ?? __('marks.inv_na') }}</td></tr>
                    <tr><th>{{ __('marks.adm_home_lang') }}</th><td>{{ $application->home_language ?? __('marks.inv_na') }}</td></tr>
                    <tr><th>{{ __('marks.adm_pob') }}</th><td>{{ $application->place_of_birth ?? __('marks.inv_na') }}</td></tr>
                    <tr><th>{{ __('marks.adm_prev_school') }}</th><td>{{ $application->attended_school ? __('marks.adm_yes') : __('marks.adm_no') }}{{ $application->previous_school ? ' — ' . $application->previous_school : '' }}</td></tr>
                </table>
                </div>
            </div>
        </div>
        <div class="card x-card">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">{{ __('marks.adm_guardian_parents') }}</h6></div>
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-bordered">
                    <tr><th style="width: 200px;">{{ __('marks.adm_guardian_row') }}</th><td>{{ $application->guardian_name }} ({{ $application->guardian_phone }}){{ $application->guardian_profession ? ', ' . $application->guardian_profession : '' }}</td></tr>
                    <tr><th>{{ __('marks.adm_father') }}</th><td>{{ $application->father_name ?? __('marks.inv_na') }}{{ $application->father_phone ? ' (' . $application->father_phone . ')' : '' }}</td></tr>
                    <tr><th>{{ __('marks.adm_mother') }}</th><td>{{ $application->mother_name ?? __('marks.inv_na') }}{{ $application->mother_phone ? ' (' . $application->mother_phone . ')' : '' }}</td></tr>
                </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        @if($application->student_image)
            <div class="card x-card mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">{{ __('marks.adm_photo') }}</h6></div>
                <div class="card-body text-center">
                    <img loading="lazy" src="{{ asset('storage/' . $application->student_image) }}" alt="Applicant photo" class="img-fluid rounded">
                </div>
            </div>
        @endif
        <div class="card x-card mb-4">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">{{ __('marks.adm_decision') }}</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.admissions.decide', $application->id) }}" method="POST">
                    @csrf
                    <div class="d-flex gap-2">
                        <button type="submit" name="decision" value="approved" class="btn btn-success" onclick="return confirm('{{ __('marks.adm_approve_q') }}')">{{ __('marks.adm_approve') }}</button>
                        <button type="submit" name="decision" value="rejected" class="btn btn-danger" onclick="return confirm('{{ __('marks.adm_reject_q') }}')">{{ __('marks.adm_reject') }}</button>
                    </div>
                </form>
                <form action="{{ route('admin.admissions.destroy', $application->id) }}" method="POST" class="mt-2" onsubmit="return confirm('{{ __('marks.adm_delete_q') }}')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('marks.adm_delete_record') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
