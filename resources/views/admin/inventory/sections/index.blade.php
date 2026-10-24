@extends('layouts.admin')

@section('title', __('marks.sections_officers'))
@section('content')
@include('admin.inventory._tabs')
<div class="x-page-head">
    <h4>{{ __('marks.sections_officers') }}</h4>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
@endif

<div class="row mb-4">
    <div class="col-md-4"><div class="card shadow py-2"><div class="card-body"><div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Sections</div><div class="h5 mb-0 font-weight-bold">{{ $totalSections ?? 0 }}</div></div></div></div>
    <div class="col-md-4"><div class="card shadow py-2"><div class="card-body"><div class="text-xs font-weight-bold text-success text-uppercase mb-1">Officers</div><div class="h5 mb-0 font-weight-bold">{{ $totalOfficers ?? 0 }}</div></div></div></div>
    <div class="col-md-4"><div class="card shadow py-2"><div class="card-body"><div class="text-xs font-weight-bold text-info text-uppercase mb-1">Incharge Officers</div><div class="h5 mb-0 font-weight-bold">{{ $inchargeCount ?? 0 }}</div></div></div></div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Sections</h6>
                <button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#addSectionForm">{{ __('marks.inv_add_section') }}</button>
            </div>
            <div class="card-body">
                <div id="addSectionForm" class="collapse mb-3">
                    <form action="{{ route('admin.inventory.sections.store') }}" method="POST">
                        @csrf
                        <div class="form-group"><input type="text" name="section_name" class="form-control" placeholder="{{ __('marks.inv_section_name') }} *" required></div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <select name="section_type" class="form-control" required>
                                    <option value="Primary">Primary</option>
                                    <option value="Secondary">Secondary</option>
                                    <option value="AL">AL</option>
                                    <option value="Other" selected>Other</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6"><input type="text" name="main_section" class="form-control" placeholder="{{ __('marks.inv_main_section_ph') }}"></div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">{{ __('marks.inv_save_section') }}</button>
                    </form>
                    <hr>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead><tr><th>{{ __('marks.inv_name') }}</th><th>{{ __('marks.inv_type') }}</th><th>{{ __('marks.inv_incharge') }}</th><th></th></tr></thead>
                        <tbody>
                            @forelse($sections ?? [] as $section)
                                <tr>
                                    <td>{{ $section->section_name }}</td>
                                    <td>{{ $section->section_type }}</td>
                                    <td>{{ optional($section->inchargeOfficer)->officer_name ?? __('marks.inv_na') }}</td>
                                    <td class="text-nowrap">
                                        <form action="{{ route('admin.inventory.sections.destroy', $section->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('marks.inv_delete_section_confirm') }}')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">{{ __('marks.delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">{{ __('marks.inv_no_sections_yet') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <small class="text-muted">{{ __('marks.inv_rename_note') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Officers</h6>
                <button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#addOfficerForm">{{ __('marks.inv_add_officer') }}</button>
            </div>
            <div class="card-body">
                <div id="addOfficerForm" class="collapse mb-3">
                    <form action="{{ route('admin.inventory.officers.store') }}" method="POST">
                        @csrf
                        <div class="form-group"><input type="text" name="officer_name" class="form-control" placeholder="{{ __('marks.inv_officer_name') }} *" required></div>
                        <div class="form-row">
                            <div class="form-group col-md-6"><input type="text" name="officer_id_number" class="form-control" placeholder="{{ __('marks.inv_id_number') }} *" required></div>
                            <div class="form-group col-md-6"><input type="text" name="phone" class="form-control" placeholder="{{ __('marks.inv_phone') }} *" required></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <select name="section_id" class="form-control">
                                    <option value="">{{ __('marks.inv_no_section') }}</option>
                                    @foreach($sections ?? [] as $section)
                                        <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="form-check mt-2">
                                    <input type="checkbox" name="is_incharge" value="1" class="form-check-input" id="new_incharge">
                                    <label for="new_incharge" class="form-check-label">{{ __('marks.inv_incharge_check') }}</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">{{ __('marks.inv_save_officer') }}</button>
                    </form>
                    <hr>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead><tr><th>{{ __('marks.inv_name') }}</th><th>{{ __('marks.inv_id_no') }}</th><th>{{ __('marks.inv_section') }}</th><th>{{ __('marks.inv_incharge') }}</th><th></th></tr></thead>
                        <tbody>
                            @forelse($officers ?? [] as $officer)
                                <tr>
                                    <td>{{ $officer->officer_name }}</td>
                                    <td>{{ $officer->officer_id_number }}</td>
                                    <td>{{ $officer->section->section_name ?? __('marks.inv_no_section') }}</td>
                                    <td>{{ $officer->is_incharge ? 'Yes' : 'No' }}</td>
                                    <td>
                                        <form action="{{ route('admin.inventory.officers.destroy', $officer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('marks.inv_delete_officer_confirm') }}')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">{{ __('marks.delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">{{ __('marks.inv_no_officers') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if(count($unassigned ?? []) > 0)
    <div class="alert alert-warning">{{ __('marks.inv_unassigned', ['names' => $unassigned->pluck('officer_name')->join(', ')]) }}</div>
@endif
@endsection
