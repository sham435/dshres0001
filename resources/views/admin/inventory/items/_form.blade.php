@php $item = $item ?? null; @endphp
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="item_name" class="form-label">{{ __('marks.inv_item_name') }} *</label>
        <input type="text" name="item_name" id="item_name" class="form-control @error('item_name') is-invalid @enderror" value="{{ old('item_name', $item->item_name ?? '') }}" required>
        @error('item_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="brand_name" class="form-label">{{ __('marks.inv_brand') }}</label>
        <input type="text" name="brand_name" id="brand_name" class="form-control" value="{{ old('brand_name', $item->brand_name ?? '') }}">
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-4">
        <label for="inventory_number" class="form-label">{{ __('marks.inv_inventory_no') }} *</label>
        <input type="text" name="inventory_number" id="inventory_number" class="form-control @error('inventory_number') is-invalid @enderror" value="{{ old('inventory_number', $item->inventory_number ?? '') }}" required>
        @error('inventory_number') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="serial_number" class="form-label">{{ __('marks.inv_serial_no') }}</label>
        <input type="text" name="serial_number" id="serial_number" class="form-control" value="{{ old('serial_number', $item->serial_number ?? '') }}">
    </div>
    <div class="form-group col-md-4">
        <label for="book_number" class="form-label">{{ __('marks.inv_book_no') }} *</label>
        <input type="text" name="book_number" id="book_number" class="form-control @error('book_number') is-invalid @enderror" value="{{ old('book_number', $item->book_number ?? '') }}" required>
        @error('book_number') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-4">
        <label for="section_id" class="form-label">{{ __('marks.inv_section') }}</label>
        <select name="section_id" id="section_id" class="form-control">
            <option value="">{{ __('marks.inv_select_section') }}</option>
            @foreach($sections ?? [] as $section)
                <option value="{{ $section->id }}" {{ old('section_id', $item->section_id ?? '') == $section->id ? 'selected' : '' }}>{{ $section->section_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4">
        <label for="incharge_officer_id" class="form-label">{{ __('marks.inv_incharge_officer') }}</label>
        <select name="incharge_officer_id" id="incharge_officer_id" class="form-control">
            <option value="">{{ __('marks.inv_select_officer') }}</option>
            @foreach($officers ?? [] as $officer)
                <option value="{{ $officer->id }}" {{ old('incharge_officer_id', $item->incharge_officer_id ?? '') == $officer->id ? 'selected' : '' }}>{{ $officer->officer_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4">
        <label for="condition_status" class="form-label">{{ __('marks.inv_condition') }} *</label>
        <select name="condition_status" id="condition_status" class="form-control" required>
            @foreach(['working' => 'marks.inv_cond_working', 'good' => 'marks.inv_cond_good', 'not_working' => 'marks.inv_cond_not_working', 'not_usable' => 'marks.inv_cond_not_usable'] as $val => $key)
                <option value="{{ $val }}" {{ old('condition_status', $item->condition_status ?? 'working') === $val ? 'selected' : '' }}>{{ __($key) }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-4">
        <label for="received_date" class="form-label">{{ __('marks.inv_received_date') }}</label>
        <input type="date" name="received_date" id="received_date" class="form-control" value="{{ old('received_date', isset($item->received_date) && $item->received_date ? $item->received_date->format('Y-m-d') : '') }}">
    </div>
    <div class="form-group col-md-4">
        <label for="received_from" class="form-label">{{ __('marks.inv_received_from') }}</label>
        <input type="text" name="received_from" id="received_from" class="form-control" value="{{ old('received_from', $item->received_from ?? '') }}">
    </div>
    <div class="form-group col-md-4">
        <label for="borrowed_officer" class="form-label">{{ __('marks.inv_borrowed_by') }}</label>
        <input type="text" name="borrowed_officer" id="borrowed_officer" class="form-control" value="{{ old('borrowed_officer', $item->borrowed_officer ?? '') }}">
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-4">
        <div class="form-check mt-4">
            <input type="checkbox" name="is_transferred" id="is_transferred" value="1" class="form-check-input" {{ old('is_transferred', $item->is_transferred ?? false) ? 'checked' : '' }}>
            <label for="is_transferred" class="form-check-label">{{ __('marks.inv_transferred_check') }}</label>
        </div>
    </div>
    <div class="form-group col-md-4">
        <label for="transferred_date" class="form-label">{{ __('marks.inv_transferred_date') }}</label>
        <input type="date" name="transferred_date" id="transferred_date" class="form-control" value="{{ old('transferred_date', isset($item->transferred_date) && $item->transferred_date ? $item->transferred_date->format('Y-m-d') : '') }}">
    </div>
    <div class="form-group col-md-4">
        <label for="item_image" class="form-label">{{ __('marks.inv_photo') }} @if($item && $item->image_path)<small class="text-muted">{{ __('marks.inv_keep_current_file') }}</small>@endif</label>
        <input type="file" name="item_image" id="item_image" class="form-control-file @error('item_image') is-invalid @enderror">
        @error('item_image') <span class="invalid-feedback">{{ $message }}</span> @enderror
        @if($item && $item->image_path)
            <div class="form-check mt-2">
                <input type="checkbox" name="remove_image" id="remove_image" value="1" class="form-check-input">
                <label for="remove_image" class="form-check-label">{{ __('marks.inv_remove_current_photo') }}</label>
            </div>
        @endif
    </div>
</div>
