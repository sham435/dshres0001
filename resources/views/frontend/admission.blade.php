@extends('layouts.frontend')
@section('title', 'Online Admission')
@section('heading', 'Online Admission')
@section('content')
@php
  $in = fn ($name, $label, $type = 'text', $req = false) =>
    '<div class="col-md-6"><label class="form-label">'.$label.($req ? ' *' : '').'</label>'
    .'<input type="'.$type.'" name="'.$name.'" value="'.e(old($name)).'" class="form-control'.($errors->has($name) ? ' is-invalid' : '').'"'.($req ? ' required' : '').'>'
    .($errors->has($name) ? '<div class="invalid-feedback">'.e($errors->first($name)).'</div>' : '').'</div>';
@endphp
<section class="section"><div class="container"><div class="row justify-content-center"><div class="col-lg-10">
  <p class="text-muted">Please fill out the form for admission guidance and information. Fields marked * are required.</p>
  <form method="POST" action="{{ route('admission.store') }}" enctype="multipart/form-data" class="card card-hover"><div class="card-body p-4"><div class="row g-3">
    @csrf
    <h5 class="col-12 mt-0">Student</h5>
    {!! $in('first_name','First Name','text',true) !!}
    {!! $in('last_name','Last Name','text',true) !!}
    {!! $in('phone','Phone No','tel') !!}
    {!! $in('email','Email Address','email',true) !!}
    {!! $in('dob','Date Of Birth','date',true) !!}
    <div class="col-md-6"><label class="form-label">Student Image</label>
      <input type="file" name="student_image" accept="image/*" class="form-control @error('student_image') is-invalid @enderror">
      @error('student_image')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>

    <div class="col-md-4"><label class="form-label">Academic Year *</label>
      <select name="academic_year" class="form-select" required>
        @foreach (range(date('Y'), date('Y') + 2) as $y)<option value="{{ $y }}" {{ (old('academic_year') == $y) ? 'selected' : '' }}>{{ $y }}</option>@endforeach
      </select></div>
    <div class="col-md-4"><label class="form-label">Class *</label>
      <select name="class_id" class="form-select @error('class_id') is-invalid @enderror" required>
        <option value="">Select class</option>
        @foreach ($classes as $c)<option value="{{ $c->id }}" {{ (old('class_id') == $c->id) ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach
      </select>
      @error('class_id')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="col-md-4"><label class="form-label">Shift</label>
      <select name="shift" class="form-select"><option value="">Select shift</option>
        @foreach (['1st','2nd','3rd'] as $s)<option {{ (old('shift') == $s) ? 'selected' : '' }}>{{ $s }}</option>@endforeach
      </select></div>

    <div class="col-md-4"><label class="form-label">Gender *</label>
      <select name="gender" class="form-select" required>
        <option value="">Select gender</option>
        <option value="male" {{ (old('gender')=='male') ? 'selected' : '' }}>Male</option>
        <option value="female" {{ (old('gender')=='female') ? 'selected' : '' }}>Female</option>
      </select></div>
    <div class="col-md-4"><label class="form-label">Religion</label>
      <select name="religion" class="form-select"><option value="">Select religion</option>
        @foreach (['Buddhism','Hinduism','Islam','Christianity','Other'] as $r)<option {{ (old('religion') == $r) ? 'selected' : '' }}>{{ $r }}</option>@endforeach
      </select></div>
    {!! str_replace('col-md-6','col-md-4',$in('nationality','Nationality')) !!}
    {!! $in('home_language','Language Spoken At Home') !!}
    {!! $in('place_of_birth','Place Of Birth') !!}
    <div class="col-12"><label class="form-label">Residence Address</label><textarea name="address" rows="2" class="form-control">{{ old('address') }}</textarea></div>

    <div class="col-12 form-check ms-2">
      <input type="checkbox" class="form-check-input" id="prev" name="attended_school" value="1" {{ (old('attended_school')) ? 'checked' : '' }}>
      <label class="form-check-label" for="prev">Attended school previously</label>
    </div>
    <div class="col-12" id="prevBox" style="display:none"><label class="form-label">Previous School Information</label>
      <textarea name="previous_school" rows="2" class="form-control">{{ old('previous_school') }}</textarea></div>

    <h5 class="col-12 mt-4">Guardian</h5>
    {!! $in('guardian_name','Guardian Name','text',true) !!}
    {!! $in('guardian_phone','Guardian Phone','tel',true) !!}
    {!! $in('guardian_profession','Guardian Profession') !!}

    <h5 class="col-12 mt-4">Parents</h5>
    {!! $in('father_name','Father Name') !!}
    {!! $in('father_phone','Father Phone','tel') !!}
    {!! $in('mother_name','Mother Name') !!}
    {!! $in('mother_phone','Mother Phone','tel') !!}

    <div class="col-12 mt-3"><button class="btn btn-brand px-5">Submit</button></div>
  </div></div></form>
</div></div></div></section>
@push('scripts')
<script>
  const c=document.getElementById('prev'),b=document.getElementById('prevBox');
  const t=()=>b.style.display=c.checked?'block':'none';c.addEventListener('change',t);t();
</script>
@endpush
@endsection
