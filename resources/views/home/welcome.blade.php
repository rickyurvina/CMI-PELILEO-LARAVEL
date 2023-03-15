@extends('layouts.admin')

@section('title', trans('general.start'))

@section('content')
    <div class="alert alert-danger alert-dismissible fade show">
        <div class="d-flex align-items-center">
            <div class="alert-icon width-8">
            <span class="icon-stack icon-stack-xl">
                <i class="base-7 icon-stack-3x color-danger-500"></i>
                <i class="base-7 icon-stack-1x color-danger-700"></i>
                <i class="ni ni-graph icon-stack-1x text-white"></i>
            </span>
            </div>
            <div class="flex-1 pl-1">
                <span class="h2">
                    Plan Estratégico
                </span>
                <br>
                No existe información para mostrar. Debe crear un <a href="{{ route('plans.edit') }}"><strong>Plan Estratégico</strong></a>
            </div>
        </div>
    </div>
@endsection