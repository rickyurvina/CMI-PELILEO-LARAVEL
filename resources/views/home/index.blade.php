@extends('layouts.admin')

@section('title', trans('general.start'))

@section('subheader-title')
    @if(user()->can('view-dashboard'))
        AÑO: {{ now()->format('Y') }}
    @endif
@endsection

@section('content')
    @if(user()->can('view-dashboard'))
        <div class="row mb-4">
            <div class="col-md-4 col-sm-12">
                <div class="card">
                    <div class="card-header bg-primary-500">
                        Estado Institucional
                    </div>
                    <div class="card-body">
                        <x-score id="{{ $plan->id }}" score="{{ $plan->score() }}" style="height: 300px;"></x-score>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-12">
                <div class="card">
                    <div class="card-header bg-primary-500">
                        Estado Institucional por Años
                    </div>
                    <div class="card-body">
                        @include('home.chart_score_historical', ['historicalScore' => $historicalScore])
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-4 col-sm-12">
                <div class="card">
                    <div class="card-header bg-primary-500">
                        Estado de Objetivos
                    </div>
                    <div class="card-body">
                        @include('home.chart_objective_score', ['objectiveScore' => $objectiveScore])
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-12">
                <div class="card">
                    <div class="card-header bg-primary-500">
                        Indicadores por Objetivos
                    </div>
                    <div class="card-body">
                        @include('home.chart_indicator_progress', ['plan' => $plan])
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-sm-12">
                <div class="card">
                    <div class="card-header bg-primary-500">
                        Avance Total de Proyectos
                    </div>
                    <div class="card-body">
                        @include('home.chart_project_progress', ['physical' => $projectsProgress['physical'], 'budget' => $projectsProgress['budget']])
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-12">
                <div class="card">
                    <div class="card-header bg-primary-500">
                        Avance por Proyectos
                    </div>
                    <div class="card-body">
                        @include('home.chart_project_progress_all', ['projectProgress' => $projectProgress])
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="h-alt-hf d-flex flex-column align-items-center justify-content-center text-center">
            <h1 class="page-error color-warning-300">
                {{ trans('errors.header.403') }}
                <small class="fw-500">
                    {{ trans('errors.message.403') }}
                </small>
            </h1>
        </div>
    @endif
@endsection
