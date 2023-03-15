@extends('layouts.admin')

@section('title', trans_choice('general.users', 2))

@section('subheader-title')
    <i class="fal fa-tasks text-primary"></i> {{ trans_choice('general.users', 2) }}
@endsection

@section('subheader-right')
    @can('manage-users')
        <a href="{{ route('users.create') }}" class="btn btn-outline-success btn-sm"><span class="fas fa-plus mr-1"></span> &nbsp;{{ trans('general.create') }}</a>
    @endcan
@endsection

@section('content')

    @include('flash::message')

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover m-0">
                <thead class="bg-primary-50">
                <tr>
                    <th>@sortablelink('name', trans('general.name'))</th>
                    <th>@sortablelink('email', trans('general.email'))</th>
                    <th class="color-primary-500">{{ trans_choice('general.roles', 0) }}</th>
                    <th>@sortablelink('last_logged_in_at', trans('general.last_logged_in_at'))</th>
                    <th>@sortablelink('enabled', trans('general.enabled'))</th>
                    <th class="text-center color-primary-500">{{ trans('general.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $item)
                    <tr>
                        <td>
                            <x-link route="{{ route('users.edit', $item->id) }}">
                                <span class="mr-2">
                                @if (is_object($item->picture))
                                        <img src="{{ Storage::url($item->picture->id) }}" class="rounded-circle width-2" alt="{{ $item->name }}">
                                    @else
                                        <img src="{{ asset("img/user.svg") }}" class="rounded-circle width-2" alt="{{ $item->name }}">
                                    @endif
                                </span>
                                {{ $item->name }}
                            </x-link>
                        </td>
                        <td>{{ $item->email }}</td>
                        <td>
                            @foreach($item->roles as $role)
                                <span class="badge badge-info badge-pill">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td>{{  $item->last_logged_in_at }}</td>
                        <td>
                            <x-enabled enabled="{{ $item->enabled }}"/>
                        </td>
                        <x-table-action>
                            @can('manage-users')
                                <a class="dropdown-item" href="{{ route('users.edit', $item->id) }}"><i class="fas fa-edit mr-1 text-info"></i>{{ trans('general.edit') }}</a>
                                <div class="dropdown-divider"></div>
                                <x-delete-link action="{{ route('users.destroy', $item->id) }}" id="{{ $item->id }}"/>
                            @endcan
                        </x-table-action>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <x-pagination :items="$users" />
    </div>

@endsection
