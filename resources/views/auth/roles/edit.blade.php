@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.roles', 1)]))

@section('subheader-title')
    <i class="fal fa-edit text-primary"></i> {{ trans('general.title.edit', ['type' => trans_choice('general.roles', 1)]) }}
@endsection

@section('content')
    <x-form action="{{ route('roles.update', $role->id) }}" method="put">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <x-form.inputs.text id="name" label="{{ trans('general.name') }}" class="col-6" value="{{ $role->name }}"/>

                    <div class="form-group col-md-6 col-sm-12 {{ $errors->has('permissions') ? 'has-error' : '' }}">
                        <label class="form-label" for="permissions">{{ trans_choice('general.permissions', 2) }}</label>
                        @error('permissions')
                        <div class="text-danger">{{ $errors->first('permissions') }}</div>
                        @enderror
                        <div class="row">
                            @foreach($permissions as $item)
                                <div class="col-md-4 col-sm-6  role-list">
                                    <div class="custom-control custom-checkbox">
                                        <input name="permissions[]" id="permissions-{{ $item->id }}" type="checkbox" class="custom-control-input" value="{{ $item->id }}"
                                                {{ ($errors->has('validated') && is_array(old('permissions')) && in_array($item->id, old('permissions'))) ? ' checked' : '' }}
                                                {{ (!$errors->has('validated') && $role->hasPermissionTo($item->id)) ? ' checked' : '' }}>
                                        <label class="custom-control-label" for="permissions-{{ $item->id }}">
                                            {{ $item->display_name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <x-form.footer urlBack="{{ route('roles.index') }}"/>
            </div>
        </div>

    </x-form>
@endsection