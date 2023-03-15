@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.users', 1)]))

@section('subheader-title')
    <i class="fal fa-plus text-primary"></i> {{ trans('general.title.edit', ['type' => trans_choice('general.users', 1)]) }}
@endsection

@section('content')
    <x-form action="{{ route('users.update', $user->id) }}" method="put" card="true" urlBack="{{ route('users.index') }}">
        <div class="row">
            <x-form.inputs.text id="name" label="{{ trans('general.name') }}" class="col-6 required" value="{{ $user->name }}"/>

            <x-form.inputs.text type="email" id="email" label="{{ trans('general.email') }}" class="col-6 required" value="{{ $user->email }}"/>

            <x-form.inputs.text type="password" id="password" label="{{ trans('general.password') }}" class="col-6"/>

            <x-form.inputs.text type="password" id="password_confirmation" label="{{ trans('auth.password.current_confirm') }}" class="col-6"/>

            @if( $user->enabled )
                <x-form.inputs.radio-enabled id="enabled" label="{{ trans('general.enabled') }}" enabled="true"/>
            @else
                <x-form.inputs.radio-enabled id="enabled" label="{{ trans('general.enabled') }}"/>
            @endif
            <x-form.inputs.checkbox id="roles" label="{{ trans_choice('general.roles', 0) }}" :items="$roles" :actual="$userRolesIds"/>
        </div>
    </x-form>
@endsection