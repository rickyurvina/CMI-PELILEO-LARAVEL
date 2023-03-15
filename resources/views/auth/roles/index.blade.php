@extends('layouts.admin')

@section('title', trans_choice('general.roles', 2))

@section('subheader-title')
    <i class="fal fa-tasks text-primary"></i> {{ trans_choice('general.roles', 2) }}
@endsection

@section('subheader-right')
    @can('create-auth-roles')
        <a href="{{ route('roles.create') }}" class="btn btn-sm btn-outline-success"><span class="fas fa-plus mr-1"></span> &nbsp;{{ trans('general.create') }}</a>
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
                    <th>@sortablelink('created_at', trans('general.created'))</th>
                    <th class="text-center color-primary-500">{{ trans('general.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($roles as $item)
                    <tr>
                        <td>
                            <x-link route="{{ route('roles.edit', $item->id) }}">{{ $item->name }}</x-link>
                        </td>
                        <td>{{ $item->created_at }}</td>
                        <x-table-action>
                            @can('update-auth-roles')
                                <a class="dropdown-item" href="{{ route('roles.edit', $item->id) }}"><i class="fas fa-edit mr-1 text-info"></i>{{ trans('general.edit') }}</a>
                            @endcan
                            <div class="dropdown-divider"></div>
                            @can('delete-auth-roles')
                                <x-delete-link action="{{ route('roles.destroy', $item->id) }}" id="{{ $item->id }}"/>
                            @endcan
                        </x-table-action>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <x-pagination :items="$roles" />
    </div>

@endsection