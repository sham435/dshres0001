@extends('layouts.admin')

@section('title', __('marks.school_profile'))
@section('content')
@include('admin.inventory._tabs')
<div class="x-page-head">
    <h4>{{ __('marks.school_profile') }}</h4>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card x-card">
            <div class="card-body">
                <form action="{{ route('admin.inventory.school.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="school_name" class="form-label">{{ __('marks.inv_sch_name') }} *</label>
                            <input type="text" name="school_name" id="school_name" class="form-control @error('school_name') is-invalid @enderror" value="{{ old('school_name', $school->school_name) }}" required>
                            @error('school_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="username" class="form-label">{{ __('marks.inv_sch_username') }} *</label>
                            <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $school->username) }}" required>
                            @error('username') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address" class="form-label">{{ __('marks.inv_sch_address') }} *</label>
                        <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="2" required>{{ old('address', $school->address) }}</textarea>
                        @error('address') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="phone" class="form-label">{{ __('marks.inv_sch_phone') }} *</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $school->phone) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email" class="form-label">{{ __('marks.inv_sch_email') }} *</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $school->email) }}" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="principal_name" class="form-label">{{ __('marks.inv_sch_principal') }} *</label>
                            <input type="text" name="principal_name" id="principal_name" class="form-control" value="{{ old('principal_name', $school->principal_name) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="principal_phone" class="form-label">{{ __('marks.inv_sch_principal_phone') }} *</label>
                            <input type="text" name="principal_phone" id="principal_phone" class="form-control" value="{{ old('principal_phone', $school->principal_phone) }}" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inventory_officer_name" class="form-label">{{ __('marks.inv_sch_officer') }} *</label>
                            <input type="text" name="inventory_officer_name" id="inventory_officer_name" class="form-control" value="{{ old('inventory_officer_name', $school->inventory_officer_name) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inventory_officer_phone" class="form-label">{{ __('marks.inv_sch_officer_phone') }} *</label>
                            <input type="text" name="inventory_officer_phone" id="inventory_officer_phone" class="form-control" value="{{ old('inventory_officer_phone', $school->inventory_officer_phone) }}" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="new_password" class="form-label">{{ __('marks.inv_sch_new_pass') }} <small class="text-muted">{{ __('marks.inv_sch_keep_pass') }}</small></label>
                            <input type="password" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror">
                            @error('new_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="new_password_confirmation" class="form-label">{{ __('marks.inv_sch_confirm_pass') }}</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="logo" class="form-label">{{ __('marks.inv_sch_logo') }}</label>
                        <input type="file" name="logo" id="logo" class="form-control-file">
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('marks.inv_save_profile') }}</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card x-card">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">{{ __('marks.inv_sch_logo') }}</h6></div>
            <div class="card-body text-center">
                @php
                    $logoFile = $school->logo_path ? basename($school->logo_path) : null;
                    $logoOk = $logoFile && file_exists(public_path('storage/inventory/logos/' . $logoFile));
                @endphp
                @if($logoOk)
                    <img loading="lazy" src="{{ asset('storage/inventory/logos/' . $logoFile) }}" alt="School logo" class="img-fluid rounded">
                @else
                    <p class="text-muted">{{ __('marks.inv_no_logo') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
