@extends('layouts.app')

@section('title', __('marks.pf_title'))
@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
        <div>
            <h3 class="mb-0">{{ __('marks.pf_title') }}</h3>
            <small class="text-muted">{{ __('marks.pf_subtitle') }}</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    <div class="row tpl-form">
        <div class="col-lg-6 mb-3">
            <div class="tpl-card no-hover h-100 d-flex flex-column">
                <div class="tpl-card-head">
                    <h3 class="tpl-card-title"><i class="fa fa-key" aria-hidden="true"></i> {{ __('marks.pf_change_pass') }}</h3>
                </div>
                <form action="{{ url()->current() }}" method="POST" class="d-flex flex-column flex-grow-1">
                    @csrf
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <div class="tpl-card-body flex-grow-1">
                        <div class="form-group">
                            <label for="current_password">{{ __('marks.pf_current') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></span></div>
                                <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="{{ __('marks.pf_current_ph') }}">
                            </div>
                            @error('current_password') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">{{ __('marks.pf_new') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-key" aria-hidden="true"></i></span></div>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('marks.pf_new_ph') }}" required>
                            </div>
                            @error('password') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group mb-0">
                            <label for="password_confirmation">{{ __('marks.pf_confirm') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-key" aria-hidden="true"></i></span></div>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{ __('marks.pf_confirm_ph') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="tpl-card-foot">
                        <button type="submit" class="btn btn-primary">{{ __('marks.pf_update_pass') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="tpl-card no-hover h-100 d-flex flex-column">
                <div class="tpl-card-head">
                    <h3 class="tpl-card-title"><i class="fa fa-user-shield" aria-hidden="true"></i> {{ __('marks.pf_account_info') }}</h3>
                </div>
                <form action="{{ url()->current() }}" method="POST" class="d-flex flex-column flex-grow-1">
                    @csrf
                    <div class="tpl-card-body flex-grow-1">
                        <div class="form-group">
                            <label for="name">{{ __('marks.pf_name') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></span></div>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" placeholder="{{ __('marks.pf_name_ph') }}" required>
                            </div>
                            @error('name') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">{{ __('marks.pf_email') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-envelope" aria-hidden="true"></i></span></div>
                                <input type="email" id="email" class="form-control" value="{{ $user->email }}" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="phone">{{ __('marks.pf_phone') }}</label>
                                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="dob">{{ __('marks.pf_dob') }}</label>
                                <input type="date" name="dob" id="dob" class="form-control" value="{{ old('dob', $user->dob ? $user->dob->format('Y-m-d') : '') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address">{{ __('marks.pf_address') }}</label>
                            <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $user->address) }}">
                        </div>
                        <div class="form-group">
                            <label for="gender">{{ __('marks.pf_gender') }}</label>
                            <select name="gender" id="gender" class="form-control">
                                <option value="">{{ __('marks.pf_select_gender') }}</option>
                                <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>{{ __('marks.adm_g_male') }}</option>
                                <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>{{ __('marks.adm_g_female') }}</option>
                                <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>{{ __('marks.adm_g_other') }}</option>
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label>{{ __('marks.pf_role') }}</label>
                            <div class="d-flex align-items-center" style="gap:12px; padding:10px 12px; border:1px solid var(--x-border, #e6e8ea); border-radius: 8px; background: var(--x-muted, #f2f3f4);">
                                <span class="tpl-ico is-violet"><i class="fa fa-user-tag" aria-hidden="true"></i></span>
                                <div>
                                    <strong>{{ optional($user->role)->display_name ?? optional($user->role)->name ?? 'N/A' }}</strong>
                                    <small class="d-block text-muted">{{ __('marks.pf_role_note') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tpl-card-foot">
                        <button type="submit" class="btn btn-primary">{{ __('marks.pf_update_profile') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
