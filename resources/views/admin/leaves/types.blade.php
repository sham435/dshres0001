@extends('layouts.admin')

@section('title', __('marks.lv_types'))
@section('content')
<div class="x-page-head">
    <h4>{{ __('marks.lv_types') }}</h4>
    <div class="actions">
        <a href="{{ route('admin.leaves.index') }}" class="btn btn-secondary">{{ __('marks.lv_back') }}</a>
    </div>
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

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card x-card">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">{{ __('marks.lv_add_type') }}</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.leave-types.store') }}" method="POST">
                    @csrf
                    <div class="form-group"><label class="form-label">{{ __('marks.lv_type_name') }} *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">{{ __('marks.lv_days_allowed') }} *</label><input type="number" name="days_allowed" min="0" value="0" class="form-control" required><small class="text-muted">0 = {{ __('marks.lv_no_quota') }}</small></div>
                    <div class="form-group"><label class="form-label">{{ __('marks.lv_max_at_time', ['max' => '']) }}</label><input type="number" name="max_consecutive_days" min="1" class="form-control" placeholder="—"></div>
                    <div class="form-group"><label class="form-label">{{ __('marks.lv_rule') }}</label><input type="text" name="rule_note" maxlength="500" class="form-control"></div>
                    <button type="submit" class="btn btn-primary">{{ __('marks.lv_add_type') }}</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card x-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead><tr><th>{{ __('marks.lv_type_name') }}</th><th class="text-right">{{ __('marks.lv_days_allowed') }}</th><th class="text-right">{{ __('marks.lv_used') }}</th><th>{{ __('marks.lv_rule') }}</th><th></th></tr></thead>
                        <tbody>
                            @forelse($types ?? [] as $type)
                                <tr>
                                    <td>{{ $type->name }}</td>
                                    <td class="table-numeric">{{ $type->days_allowed }}</td>
                                    <td class="table-numeric">{{ $type->applications_count }}</td>
                                    <td>
                                        <form action="{{ route('admin.leave-types.destroy', $type->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('marks.fo_delete_q') }}')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">{{ __('marks.fo_delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4"><div class="x-empty"><i class="fa fa-calendar-times" aria-hidden="true"></i>{{ __('marks.lv_no_leaves') }}</div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
