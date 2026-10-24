@php $item = $item ?? null; @endphp
<div class="form-group">
    <label for="title" class="form-label">{{ __('marks.hw_title') }} *</label>
    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $item->title ?? '') }}" required>
    @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
</div>
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="subject_id" class="form-label">{{ __('marks.hw_subject') }}</label>
        <select name="subject_id" id="subject_id" class="form-control">
            <option value="">{{ __('marks.hw_all_classes') }}</option>
            @foreach($subjects ?? [] as $subject)
                <option value="{{ $subject->id }}" {{ old('subject_id', $item->subject_id ?? '') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-6">
        <label for="class_id" class="form-label">{{ __('marks.hw_class') }}</label>
        <select name="class_id" id="class_id" class="form-control">
            <option value="">{{ __('marks.hw_all_classes') }}</option>
            @foreach($classes ?? [] as $class)
                <option value="{{ $class->id }}" {{ old('class_id', $item->class_id ?? '') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group">
    <label for="description" class="form-label">{{ __('marks.hw_description') }}</label>
    <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $item->description ?? '') }}</textarea>
</div>
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="due_date" class="form-label">{{ __('marks.hw_due_date') }}</label>
        <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date', isset($item->due_date) && $item->due_date ? $item->due_date->format('Y-m-d') : '') }}">
    </div>
    <div class="form-group col-md-6">
        <label for="file" class="form-label">{{ __('marks.hw_file') }}</label>
        <input type="file" name="file" id="file" class="form-control-file">
    </div>
</div>
