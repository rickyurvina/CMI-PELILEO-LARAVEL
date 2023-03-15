<div class="modal-dialog modal-dialog-right modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title h4"><i class="fas fa-edit text-success"></i> Editar Indicador</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fal fa-times"></i></span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <x-form.inputs.text id="name" label="{{ trans('general.name') }}" class="col-md-6 col-sm-12" model="name"/>

                <x-form.inputs.select id="type" label="Tipo de indicador" class="col-md-6 col-sm-12" model="type">
                    <option value="ascending">Ascendente</option>
                    <option value="goal_only">Valor Objetivo</option>
                </x-form.inputs.select>

                @if($type == \App\Models\Indicator::TYPE_GOAL_ONLY)
                    <x-form.inputs.select id="isHigherValuesBest" label="Valor mayor es mejor?" class="col-md-6 col-sm-12" model="isHigherValuesBest">
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </x-form.inputs.select>
                @endif

                <x-form.inputs.text-area id="formula" label="Fórmula del indicador" class="col-md-6 col-sm-12" model="calculationFormula" rows="2"/>

                <x-form.inputs.select id="measureUnitId" label="Unidad de medida" class="col-md-6 col-sm-12" model="measureUnitId">
                    <option value="">{{ trans('general.select') }}</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->value }}</option>
                    @endforeach
                </x-form.inputs.select>

                <x-form.inputs.text-area id="information" label="Información disponible" class="col-md-6 col-sm-12" model="information" rows="2"/>

                <x-form.inputs.text id="source" label="Medio de verificación" class="col-md-6 col-sm-12" model="source"/>


                <x-form.inputs.text-area id="goal_description" label="Meta" class="col-md-6 col-sm-12" model="goalDescription" rows="2"/>

                <div class="form-group col-md-3 col-sm-6">
                    <label class="form-label" for="start-date">Inicio</label>
                    <input class="form-control" id="start-date" type="date" wire:model="startDate" @if($hasProgress) disabled @endif>
                </div>

                <div class="form-group col-md-3 col-sm-6">
                    <label class="form-label" for="end-date">Fin</label>
                    <input class="form-control" id="end-date" type="date" wire:model="endDate" @if($hasProgress) disabled @endif>
                </div>

                <x-form.inputs.text id="responsible" label="Responsable" class="col-md-6 col-sm-12" model="responsible"/>

                <x-form.inputs.select id="frequency" label="Periodo" class="col-md-6 col-sm-12" model="frequency" disabled="{{$hasProgress}}">
                    <option value="1">Anual</option>
                    <option value="12">Mensual</option>
                    <option value="4">Trimestral</option>
                    <option value="3">Cuatrimestral</option>
                    <option value="2">Semestral</option>
                </x-form.inputs.select>
            </div>
            <div class="row">
                @for($i =0; $i < count(($this->periods)); $i++)
                    <div class="col-2 mb-1">
                        <x-form.inputs.text type="number" id="freq-{{ $i }}" label="{{ $data[$i]['frequency'] }}" class="mb-0"
                                            model="plan.{{ $i }}"/>
{{--                        <x-form.inputs.text type="number" id="real-{{ $i }}" model="real.{{ $i  }}"/>--}}
                        <x-form.inputs.text type="number" id="freq-{{ $i }}" model="real.{{ $i  }}" disabled="{{ isset($updatePeriod[$i]) && !$updatePeriod[$i] }}"/>
                    </div>
                @endfor
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary waves-effect waves-themed" data-dismiss="modal">{{ trans('general.cancel') }}</button>
            <button type="button" class="btn btn-primary waves-effect waves-themed" wire:click="update">{{ trans('general.save') }}</button>
        </div>
    </div>
</div>
