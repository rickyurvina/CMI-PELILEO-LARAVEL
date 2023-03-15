@extends('layouts.admin')

@section('title', trans('general.start'))

@section('content')
    <div class="row">
        <div class="col-md-8 col-sm-12">
            <div class="alert alert-info fade show" role="alert">
                <div class="d-flex align-items-center">
                    <div class="alert-icon">
                    <span class="icon-stack icon-stack-md">
                        <i class="base-2 icon-stack-3x color-info-400"></i>
                        <i class="base-10 text-white icon-stack-1x"></i>
                        <i class="far fa-star color-info-800 icon-stack-2x"></i>
                    </span>
                    </div>
                    <div class="flex-1">
                        <span class="h4">Misión</span>
                        <br>
                        {{ $plan->mission }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-12">
            <div class="alert alert-info bg-transparent fade show">
                <div class="d-flex align-items-center">
                    <div class="flex-1">
                        <span class="h3">Principios</span>
                        <br>
                        @foreach($plan->principles as $p)
                            <span class="h5"><span class="badge badge-info">{{ $p }}</span></span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-12">
            <div class="alert alert-info bg-transparent fade show">
                <div class="d-flex align-items-center">
                    <div class="flex-1">
                        <span class="h3">Valores</span>
                        <br>
                        @foreach($plan->values as $v)
                            <span class="h5"><span class="badge badge-info">{{ $v }}</span></span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info fade show" role="alert">
                <div class="d-flex align-items-center">
                    <div class="alert-icon">
                    <span class="icon-stack icon-stack-md">
                        <i class="base-2 icon-stack-3x color-info-400"></i>
                        <i class="base-10 text-white icon-stack-1x"></i>
                        <i class="fas fa-eye color-info-800 icon-stack-2x"></i>
                    </span>
                    </div>
                    <div class="flex-1">
                        <span class="h4">Visión</span>
                        <br>
                        {{ $plan->vision }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h2>Objetivos Estratégicos</h2>
    @foreach($objectives as $axis)
        <div class="row mb-4">
            <div class="card col-12">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <div class="d-flex flex-row align-items-center">
                                <div class="icon-stack display-3 flex-shrink-0">
                                    <i class="fal fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                    <i class="fas fa-{{ $axis->first()->axis->icon }} icon-stack-1x opacity-100 color-primary-500"></i>
                                </div>
                                <div class="ml-3">
                                    <h3> Eje {{ $loop->index + 1 }}: {{ $axis->first()->axis->value }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($axis as $obj)
                            <div class="col-md-4">
                                <div class="card shadow-hover">
                                    <a href="{{ route('objectives.index', $obj->id) }}" class="cursor-pointer">
                                        <div class="card-header bg-fusion-200 align-items-center">
                                            <!-- we wrap header title inside a span tag with utility padding -->
                                            <h4 class="mb-2"><span class="badge badge-info">{{ $obj->focus->value }}</span></h4>
                                            <h5 class="card-title">{{ $obj->code }} {{ $obj->name }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <x-score id="{{ $obj->id }}" score="{{ $obj->score() }}"></x-score>
                                        </div>
                                        <div class="card-footer py-2">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    @if(count($obj->indicatorsByStatus()))
                                                        <a href="javascript:void(0);"
                                                           class="btn btn-default btn-sm btn-icon rounded-circle waves-effect waves-themed cursor-default">
                                                            {{ isset($obj->indicatorsByStatus()['Pending']) ? count($obj->indicatorsByStatus()['Pending']):0 }}
                                                        </a>
                                                        <a href="javascript:void(0);"
                                                           class="btn btn-danger btn-sm btn-icon rounded-circle waves-effect waves-themed cursor-default">
                                                            {{ isset($obj->indicatorsByStatus()['Danger']) ? count($obj->indicatorsByStatus()['Danger']):0 }}
                                                        </a>
                                                        <a href="javascript:void(0);"
                                                           class="btn bg-warning-900 btn-sm color-white btn-icon rounded-circle waves-effect waves-themed cursor-default">
                                                            {{ isset($obj->indicatorsByStatus()['Warning']) ? count($obj->indicatorsByStatus()['Warning']):0 }}
                                                        </a>
                                                        <a href="javascript:void(0);"
                                                           class="btn btn-success btn-sm btn-icon rounded-circle waves-effect waves-themed cursor-default">
                                                            {{ isset($obj->indicatorsByStatus()['Success']) ? count($obj->indicatorsByStatus()['Success']):0 }}
                                                        </a>
                                                    @endif
                                                </div>
                                                <div>
                                                    <a href="{{ route('objectives.index', $obj->id) }}" class="btn btn-outline-info btn-sm btn-icon rounded-circle waves-effect waves-themed ml-auto">
                                                        <i class="fas fa-arrow-right"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    @endforeach
@endsection
