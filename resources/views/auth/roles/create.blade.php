@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.roles', 1)]))

@section('subheader-title')
    <i class="fal fa-plus text-primary"></i> {{ trans('general.title.create', ['type' => trans_choice('general.roles', 1)]) }}
@endsection

@section('content')
    <x-form action="{{ route('roles.store') }}" method="post">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <x-form.inputs.text id="name" label="{{ trans('general.name') }}" class="col-md-6 col-sm-12" value="{{ old('name') }}"/>

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
                                         {{ (is_array(old('permissions')) && in_array($item->id, old('permissions'))) ? ' checked' : '' }}>
                                        <label class="custom-control-label" for="permissions-{{ $item->id }}">
                                            {{ $item->display_name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <x-form.footer urlBack="{{ route('roles.index') }}"/>
        </div>
    </x-form>
@endsection