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
                         <span class="h4">
                            {{ $objective->description }}
                         </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <x-score id="{{ $objective->id }}" score="{{ $objective->score() }}"></x-score>
        </div>
    </div>

    <h3>Indicadores</h3>

    @foreach($objective->indicators as $indicator)
        @include('tracking.objective.indicator', ['indicator' => $indicator])
    @endforeach

    <div class="row d-inline-flex align-items-center justify-content-between">
        <h3>Proyectos</h3>
        <div class="px-3 py-2 d-flex align-items-center">
            <span class="js-percent d-block text-dark mr-2">Avance Total: </span>
            <div class="js-easy-pie-chart color-success-300 position-relative d-inline-flex align-items-center justify-content-center"
                 data-percent="{{ $objective->progress()['physical'] }}"
                 data-piesize="50"
                 data-linewidth="5" data-linecap="butt" data-scalelength="0">
                <div class="d-flex flex-column align-items-center justify-content-center position-absolute pos-left pos-right pos-top pos-bottom fw-300 fs-lg">
                    <span class="js-percent d-block text-dark">{{ $objective->progress()['physical'] }}</span>
                </div>
            </div>
            <span class="d-inline-block ml-2 text-muted mr-2">
                Físico
            </span>
            <div class="ml-auto d-inline-flex align-items-center">
                <div class="js-easy-pie-chart color-primary-300 position-relative d-inline-flex align-items-center justify-content-center"
                     data-percent="{{ $objective->progress()['budget'] }}" data-piesize="50"
                     data-linewidth="5" data-linecap="butt" data-scalelength="0">
                    <div class="d-flex flex-column align-items-center justify-content-center position-absolute pos-left pos-right pos-top pos-bottom fw-300 fs-lg">
                        <span class="js-percent d-block text-dark">{{ $objective->progress()['budget'] }}</span>
                    </div>
                </div>
                <span class="d-inline-block ml-2 text-muted">
                    Presupuestario
                </span>
            </div>
        </div>
    </div>


    <div class="row">
        @foreach($objective->projects as $project)
            @include('tracking.projects.project', ['project' => $project])
        @endforeach
    </div>

@endsection
