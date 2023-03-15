<div id="updateModalObjective" class="modal fade default-example-modal-right"
     tabindex="-1" role="dialog" style="display: none;" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-right">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h4">Editar Objetivo</h5>
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
                                @foreach($pdot->elements as $index_ )
                                    <div class="custom-control custom-checkbox custom-checkbox-circle">
                                        <input type="checkbox" class="custom-control-input" id="pdot-obj-edit-{{$index_->id }}"
                                               wire:model="selectedPdotObjEdit.{{ $index_->id  }}" value="{{$index_->id}}" }}>
                                        <label class="custom-control-label" for="pdot-obj-edit-{{ $index_->id }}">{{ $index_->name }}</label>
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
                                        <input type="checkbox" class="custom-control-input" id="ods-obj-edit-{{ $el->id  }}"
                                               wire:model="selectedOdsObjEdit.{{$el->id }}" value="{{ $el->id }}">
                                        <label class="custom-control-label" for="ods-obj-edit-{{ $el->id  }}">{{$el->name }}</label>
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
                                        <input type="radio" class="custom-control-input"
                                               value="{{ $option->id }}" wire:model="selectedAxis">
                                        <label class="custom-control-label" for="axis-{{ $index }}">{{ $option->value }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <h5 class="text-info mt-1">
                            <span class="badge badge-warning">Enfoque</span>
                        </h5>
                        <div class="row fs-b fw-300">
                            <div class="col">
                                @foreach($focus  as $index => $option)
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input"
                                               value="{{ $option->id }}" wire:model="selectedFocus">
                                        <label class="custom-control-label" for="focus-{{ $index }}">{{ $option->value }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect waves-themed" data-dismiss="modal">{{ trans('general.cancel') }}</button>
                <button type="button" class="btn btn-primary waves-effect waves-themed" wire:click="update">{{ trans('general.save') }}</button>
            </div>
        </div>
    </div>
</div>

