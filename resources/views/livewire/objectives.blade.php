<div>
    <div class="d-flex align-items-center my-2">
        <div class="mr-1"><h3 class="m-0">Objetivos</h3></div>
        <div>
            <a href="javascript:void(0);" data-toggle="modal" data-target="#objModal" wire:click="resetInputs"
               class="btn btn-outline-success btn-icon rounded-circle waves-effect waves-themed">
                <i class="fal fa-plus fs-md"></i>
            </a>
        </div>
    </div>

    @include('livewire.objectivesUpdate')
    <div class="row">
        <div class="col-12">
            <div class="accordion" id="js_demo_accordion-5">
                @foreach($plan->elements as $obj)
                    <div class="card border-primary" wire:key="{{ 'objective'.$loop->index }}">
                        <div class="card-header">
                            <div class="card-title collapsed" data-toggle="collapse" data-target="#obj-{{ $obj->id }}" aria-expanded="false" wire:ignore.self>
                                <i class="fal fa-cog width-2 fs-xl"></i>
                                <div class="d-inline-block">
                                    {{ $obj->code }} - {{ $obj->name }}
                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#updateModalObjective" wire:click="edit({{ $obj->id }})"
                                       class="fs-xl text-truncate text-truncate-lg text-info ml-3">
                                        <i class="fal fa-edit fs-md"></i>
                                    </a>

                                    <a href="javascript:void(0);" class="fs-xl text-truncate text-truncate-lg text-danger ml-1" aria-expanded="false"
                                       wire:click="$emit('triggerDelete', '{{ $obj->id }}')">
                                        <i class="fal fa-trash"></i>
                                    </a>
                                </div>

                                <span class="ml-auto">
                                    <span class="collapsed-reveal">
                                        <i class="fal fa-chevron-up fs-xl"></i>
                                    </span>
                                    <span class="collapsed-hidden">
                                        <i class="fal fa-chevron-down fs-xl"></i>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div id="obj-{{ $obj->id }}" class="collapse" data-parent="#obj-{{ $obj->id }}" style="">
                            <div class="card-body">
                                <ul class="nav nav-tabs nav-tabs-clean" role="tablist">
                                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab-home-{{ $obj->id }}" role="tab" aria-selected="true">Resumen</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-profile-{{ $obj->id }}" role="tab" aria-selected="false">Indicadores</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-time-{{ $obj->id }}" role="tab">Proyectos</a></li>
                                </ul>
                                <div class="tab-content p-3">
                                    <div class="tab-pane fade active show" id="tab-home-{{ $obj->id }}" role="tabpanel">
                                        <div class="row mb-1">
                                            <div class="col">
                                                <a href="javascript:void(0);" data-toggle="modal" data-target="#updateModalObjective" wire:click="edit({{ $obj->id }})"
                                                   class="fs-xl text-truncate text-truncate-lg text-info">
                                                    <i class="fal fa-edit fs-md"></i>
                                                </a>

                                                <a href="javascript:void(0);" class="fs-xl text-truncate text-truncate-lg text-danger ml-1" aria-expanded="false"
                                                   wire:click="$emit('triggerDelete', '{{ $obj->id }}')">
                                                    <i class="fal fa-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <dl class="row">
                                            <dt class="col-sm-2"><h5><strong>{{ trans('general.name') }}</strong></h5></dt>
                                            <dd class="col-sm-4">
                                                {{ $obj->code }} - {{ $obj->name }}
                                            </dd>

                                            <dt class="col-sm-2"><h5><strong>{{ trans('general.description') }}</strong></h5></dt>
                                            <dd class="col-sm-4">
                                                {{ $obj->description }}
                                            </dd>

                                            <dt class="col-sm-2"><h5><strong>Eje</strong></h5></dt>
                                            <dd class="col-sm-4">

                                                @if(isset( $obj->axis->value))
                                                    {{ $obj->axis->value}}
                                                @endif
                                            </dd>

                                            <dt class="col-sm-2"><h5><strong>Enfoque</strong></h5></dt>
                                            <dd class="col-sm-4">
                                                @if(isset( $obj->focus->value))
                                                    {{ $obj->focus->value }}
                                                @endif
                                            </dd>
                                        </dl>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <span class="badge badge-danger">PDOT</span>
                                                @foreach($obj->linksType('PDOT')->get() as $link)
                                                    <div class="panel-tag my-1">
                                                        {{ $link->code }} - {{ $link->name }}
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="col-md-4">
                                                <span class="badge badge-success">ODS</span>
                                                @foreach($obj->linksType('ODS')->get() as $link)
                                                    <div class="panel-tag my-1">
                                                        {{ $link->code }} - {{ $link->name }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="tab-profile-{{ $obj->id }}" role="tabpanel">
                                        <livewire:indicators :id="$obj->id" :key="'indicator-'.$loop->index"/>
                                    </div>
                                    <div class="tab-pane fade" id="tab-time-{{ $obj->id }}" role="tabpanel">
                                        <livewire:projects :id="$obj->id" :key="'project-'.$loop->index"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div id="objModal" class="modal fade default-example-modal-right" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-right">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4">Crear Objetivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card mb-3">
                        <div class="card-body p-3">
                            <div class="form-group">
                                <label for="code" class="form-label">{{ trans('general.code') }}</label>
                                <input type="text" id="code"
                                       class="form-control form-control-lg rounded-0 border-top-0 border-left-0 border-right-0 px-0 bg-transparent
                                        @error('code') is-invalid @enderror" placeholder="Ej: OE1" wire:model="code">
                                <div class="invalid-feedback">{{ $errors->first('code') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="form-label">{{ trans('general.name') }}</label>
                                <input type="text" id="name"
                                       class="form-control form-control-lg rounded-0 border-top-0 border-left-0 border-right-0 px-0 bg-transparent
                                        @error('code') is-invalid @enderror" placeholder="Nombre reducido del objetivo" wire:model="name">
                                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-label">{{ trans('general.description') }}</label>
                                <textarea id="decription" class="form-control form-control-lg rounded-0 border-top-0 border-left-0 border-right-0 px-0 bg-transparent"
                                          placeholder="Descripción o nombre completo del objetivo" rows="2" wire:model="description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body p-3">
                            <h5 class="text-danger">
                                Objetivos Provinciales
                                <small class="mt-0 mb-3 text-muted">
                                    Seleccione la vinculación con el PDOT
                                </small>
                                <span class="badge badge-danger fw-n position-absolute pos-top pos-right mt-3 mr-3">PDOT</span>
                            </h5>
                            <div class="row fs-b fw-300">
                                <div class="col">
                                    @foreach($pdot->elements as $index => $el)
                                        <div class="custom-control custom-checkbox custom-checkbox-circle">
                                            <input name="pdot-obj[]" id="pdot-obj-{{ $el->id }}" type="checkbox" class="custom-control-input"
                                                   wire:model="selectedPdotObj.{{ $index }}" value="{{ $el->id }}">
                                            <label class="custom-control-label" for="pdot-obj-{{ $el->id }}">{{ $el->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body p-3">
                            <h5 class="text-success">
                                Objetivos de Desarrollo Sostenible
                                <small class="mt-0 mb-3 text-muted">
                                    Seleccione la vinculación con los ODS
                                </small>
                                <span class="badge badge-success fw-n position-absolute pos-top pos-right mt-3 mr-3">ODS</span>
                            </h5>
                            <div class="row fs-b fw-300">
                                <div class="col">
                                    @foreach($ods->elements as $index => $el)
                                        <div class="custom-control custom-checkbox custom-checkbox-circle">
                                            <input name="ods-obj[]" id="ods-obj-{{ $el->id }}" type="checkbox" class="custom-control-input"
                                                   wire:model="selectedOdsObj.{{ $index }}" value="{{ $el->id }}">
                                            <label class="custom-control-label" for="ods-obj-{{ $el->id }}">{{ $el->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body p-3">
                            <h5 class="text-info">
                                <span class="badge badge-info">EJE</span>
                            </h5>
                            <div class="row fs-b fw-300">
                                <div class="col">
                                    @foreach($axis as $index => $option)
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="axis-{{ $index }}"
                                                   value="{{ $option->id }}" wire:model="selectedAxis">
                                            <label class="custom-control-label" for="axis-{{ $index }}">{{ $option->value }}</label>
                                        </div>
                                    @endforeach
                                    @error('selectedAxis')
                                    <div class="invalid-feedback" style="display: block">El Eje es requerido</div>
                                    @enderror
                                </div>
                            </div>

                            <h5 class="text-info mt-1">
                                <span class="badge badge-warning">Enfoque</span>
                            </h5>
                            <div class="row fs-b fw-300">
                                <div class="col">
                                    @foreach($focus  as $index => $option)
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="focus-{{ $index }}"
                                                   value="{{ $option->id }}" wire:model="selectedFocus">
                                            <label class="custom-control-label" for="focus-{{ $index }}">{{ $option->value }}</label>
                                        </div>
                                    @endforeach
                                    @error('selectedFocus')
                                    <div class="invalid-feedback" style="display: block">El Enfoque es requerido</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-faded">
                    <button type="button" class="btn btn-secondary waves-effect waves-themed" data-dismiss="modal">{{ trans('general.cancel') }}</button>
                    <button type="button" class="btn btn-primary waves-effect waves-themed" wire:click="save">{{ trans('general.save') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore>
        <div class="modal fade fade" id="indicator-edit-modal" tabindex="-1" style="display: none;" role="dialog" aria-hidden="true">
            <livewire:indicator-edit/>
        </div>
    </div>

    @push('page_script')
        <script>
            Livewire.on('objectiveAdded', () => {
                $('#objModal').modal('toggle')
            })

            Livewire.on('objectiveUpdated', () => {
                $('#updateModalObjective').modal('hide');
            })
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

            Livewire.on('toggleIndicatorEditModal', () => $('#indicator-edit-modal').modal('toggle'));

            $('.collapse').on('shown.bs.collapse', function(e) {
                let $card = $(this).closest('.card');
                $('html,body').animate({
                    scrollTop: $card.offset().top - 110
                }, 500);
            });

            window.addEventListener('alert', event => {
                Toast.fire({
                    icon: event.detail.icon,
                    title: event.detail.text,
                })
            });
        </script>
    @endpush
</div>
