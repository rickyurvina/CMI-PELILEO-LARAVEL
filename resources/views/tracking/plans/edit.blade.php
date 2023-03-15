@extends('layouts.admin')

@section('title', trans('general.start'))

@section('content')
    @include('flash::message')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <x-form action="{{ route('plans.update', $plan ? $plan->id: -1) }}" method="put" card="true">
                <div class='form-group required'>
                    <label class="form-label fw-400" for="mission" style="font-size: 1.1875rem;">{{ trans('general.mission') }}</label>
                    <textarea name="mission" id="mission" rows="4"
                              class="form-control @error('mission') is-invalid @enderror {{ ($errors->has('validated') && !$errors->has('mission')) ? 'is-valid':'' }}">{{ $plan ? $plan->mission : '' }}</textarea>
                    <div class="invalid-feedback">{{ $errors->first('mission') }}</div>
                </div>
                <div class='form-group required'>
                    <label class="form-label fw-400" for="vision" style="font-size: 1.1875rem;">{{ trans('general.vision') }}</label>
                    <textarea name="vision" id="vision" rows="4"
                              class="form-control @error('vision') is-invalid @enderror {{ ($errors->has('validated') && !$errors->has('vision')) ? 'is-valid':'' }}">{{ $plan ? $plan->vision : '' }}</textarea>
                    <div class="invalid-feedback">{{ $errors->first('vision') }}</div>
                </div>
                <input type="hidden" name="type" value="PEI">
            </x-form>
        </div>
        @if($plan)
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="card border shadow-5 mb-g">
                            <div class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top">
                                <h3>Principios</h3>
                                <livewire:list-item :id="$plan->id" type="principles"/>
                                <livewire:add-item :id="$plan->id" type="principles"/>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="card border shadow-5 mb-g">
                            <div class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top">
                                <h3>Valores</h3>
                                <livewire:list-item :id="$plan->id" type="values"/>
                                <livewire:add-item :id="$plan->id" type="values"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if($plan)
        <livewire:objectives :id="$plan->id"/>
    @endif

@endsection
