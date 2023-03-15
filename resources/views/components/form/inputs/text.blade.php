@props(['id' => '', 'type' => 'text', 'placeholder' => '', 'label' => '', 'value' => '', 'model' => '', 'disabled' => false])

<div {{ $attributes->merge(['class' => 'form-group']) }}>
    <label class="form-label" for="{{ $id }}">{{ $label }}</label>
    <input type="{{ $type }}" name="{{ $id }}" id="{{ $id }}" class="form-control @error($id) is-invalid @enderror
            {{ ($errors->has('validated') && !$errors->has($id)) ? 'is-valid':'' }}" value="{{ $value }}" placeholder="{{ $placeholder }}"
           @if($model !== '') wire:model="{{ $model }}" @endif {{ $disabled ? 'disabled': '' }}>
    <div class="invalid-feedback">{{ $errors->first($id) }}</div>
</div>