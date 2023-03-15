<div>
    <a href="javascript:void(0);" data-toggle="modal" data-target=".indicator-modal-right-{{ $element->id }}"
       class="btn btn-outline-success btn-sm btn-icon rounded-circle waves-effect waves-themed">
        <i class="fal fa-plus fs-md"></i>
    </a>

    <div class="row pt-1">
        @foreach($element->indicators as $indicator)
            <div class="col-xl-6" :key="{{ 'indicator-'.$loop->index }}">
                <div class="card border shadow-0 shadow-sm-hover mb-g">
                    <div class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top">
                        <div class="info-card-text">
                            <a href="javascript:void(0);" class="fs-xl text-info" aria-expanded="false"
                               wire:click="$emitTo('indicator-edit', 'open', {{ $indicator->id }})">
                                {{ $indicator->name }} <i class="fal fa-edit"></i>
                            </a>

                            <a href="javascript:void(0);" class="fs-xl text-danger ml-1" aria-expanded="false"
                               wire:click="$emit('triggerDelete', '{{ $indicator->id }}')">
                                <i class="fal fa-trash"></i>
                            </a>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <b>Meta:</b> {{ $indicator->goal_description }}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <b>Unidad de Medida:</b> {{ $indicator->measureUnit->value }}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <b>Responsable:</b> {{ $indicator->responsible }}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <b>Tipo: </b> {{ trans('general.indicator.' . $indicator->type) }}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <b>Frecuencia: </b> {{ trans('general.indicator.frequency.' . $indicator->frequency) }}
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="p-3">
                            <livewire:indicator-chart :id="$indicator->id" key="'indicatorchart'.{{$loop->index}}"/>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Indicator Modal Right Large -->
    <div class="modal fade default-example-modal-right-lg indicator-modal-right-{{ $element->id }}"
         id="indicatorModal-{{ $element->id }}" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-right modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4"><i class="fas fa-plus-circle text-success"></i> Crear Indicador</h5>
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
                                <option value="1" selected>Sí</option>
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
                            <input class="form-control @error('startDate') is-invalid @enderror" id="startDate" type="date" wire:model="startDate">
                            <div class="invalid-feedback">{{ $errors->first('startDate') }}</div>
                        </div>

                        <div class="form-group col-md-3 col-sm-6">
                            <label class="form-label" for="end-date">Fin</label>
                            <input class="form-control @error('endDate') is-invalid @enderror" id="endDate" type="date" wire:model="endDate">
                            <div class="invalid-feedback">{{ $errors->first('endDate') }}</div>
                        </div>

                        <x-form.inputs.text id="responsible" label="Responsable" class="col-md-6 col-sm-12" model="responsible"/>

                        <x-form.inputs.select id="frequency" label="Periodo" class="col-md-6 col-sm-12" model="frequency">
                            <option value="1">Anual</option>
                            <option value="12">Mensual</option>
                            <option value="4">Trimestral</option>
                            <option value="3">Cuatrimestral</option>
                            <option value="2">Semestral</option>
                        </x-form.inputs.select>

                        @for($i = 0; $i < count($this->periods); $i++)
                            <x-form.inputs.text type="number" id="freq-{{ $i }}" label="{{ $data[$i]['frequency'] }}" class="col-2"
                                                model="frequencies.{{ $i }}"/>
                        @endfor

                        <div id="create-indicator-chart-{{ $element->id }}" class="w-100 height-lg" wire:ignore.self>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect waves-themed" data-dismiss="modal">{{ trans('general.cancel') }}</button>
                    <button type="button" class="btn btn-primary waves-effect waves-themed" wire:click="store">{{ trans('general.save') }}</button>
                </div>
            </div>
        </div>
    </div>

    @push('page_script')
        <script>
            Livewire.on('closeIndicatorModal-{{ $element->id }}', () => {
                $('#indicatorModal-{{ $element->id }}').modal('hide');
            })

            window.addEventListener('updateChartData-{{ $element->id }}', event => {

                var chart_{{ $element->id }} = am4core.create("create-indicator-chart-{{ $element->id }}", am4charts.XYChart);

                chart_{{ $element->id }}.data = event.detail.data;

                // Create axes
                var categoryAxis = chart_{{ $element->id }}.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "frequency";
                categoryAxis.title.text = "Frecuencia";
                categoryAxis.renderer.minGridDistance = 50;
                categoryAxis.renderer.grid.template.location = 0.5;

                var valueAxis = chart_{{ $element->id }}.yAxes.push(new am4charts.ValueAxis());

                // Create series
                var series = chart_{{ $element->id }}.series.push(new am4charts.LineSeries());
                series.dataFields.valueY = "value";
                series.dataFields.categoryX = "frequency";
                series.tooltipText = "{value}"
                series.strokeWidth = 3;
                series.bullets.push(new am4charts.CircleBullet());

                series.tooltip.pointerOrientation = "vertical";

                chart_{{ $element->id }}.cursor = new am4charts.XYCursor();
                chart_{{ $element->id }}.cursor.snapToSeries = series;
                chart_{{ $element->id }}.cursor.categoryX = categoryAxis;

                chart_{{ $element->id }}.legend = new am4charts.Legend();
            });

            document.addEventListener('DOMContentLoaded', function () {
            @this.on('triggerDelete', id => {
                Swal.fire({
                    title: '{{ trans('messages.warning.sure') }}',
                    text: '{{ trans_choice('messages.warning.delete', 1, ['type' => strtolower(trans_choice('general.invitations', 1))])}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'var(--danger)',
                    confirmButtonText: '<i class="fas fa-trash"></i> {{ trans('general.yes') . ', ' . trans('general.delete') }}',
                    cancelButtonText: '<i class="fas fa-times"></i> {{ trans('general.no') . ', ' . trans('general.cancel') }}'
                }).then((result) => {
                    //if user clicks on delete
                    if (result.value) {
                        // calling destroy method to delete
                    @this.call('delete', id);
                    }
                });
            });
            })

        </script>
    @endpush
</div>
