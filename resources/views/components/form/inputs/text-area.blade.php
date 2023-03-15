@props(['id' => '', 'placeholder' => '', 'label' => '', 'rows' => 3, 'model' => ''])

<div {{ $attributes->merge(['class' => 'form-group']) }}>
    <label class="form-label" for="{{ $id }}">{{ $label }}</label>
    <textarea name="{{ $id }}" id="{{ $id }}" class="form-control @error($id) is-invalid @enderror {{ ($errors->has('validated') && !$errors->has($id)) ? 'is-valid':'' }}"
              placeholder="{{ $placeholder }}" rows="{{ $rows }}" @if($model !== '') wire:model="{{ $model }}" @endif>{{ $slot }}</textarea>
    <div class="invalid-feedback">{{ $errors->first($id) }}</div>
</div>