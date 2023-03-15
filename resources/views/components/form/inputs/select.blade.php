@props(['id' => '', 'label' => '', 'model' => '', 'disabled' => false])

<div {{ $attributes->merge(['class' => 'form-group']) }}>
    <label class="form-label" for="{{ $id }}">{{ $label }}</label>
    <select name="{{ $id }}" id="{{ $id }}" class="form-control custom-select @error($id) is-invalid @enderror"
            {{ ($errors->has('validated') && !$errors->has($id)) ? 'is-valid':'' }}" @if($model !== '') wire:model="{{ $model }}" @endif
    {{ $disabled ? 'disabled': '' }}>
        {{ $slot }}
    </select>
    @error($id) <div class="error color-danger-700">{{ $errors->first($id) }}</div> @enderror
</div>