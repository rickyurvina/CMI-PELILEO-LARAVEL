<!-- project EditModal Right Large -->
<div class="modal fade default-example-modal-right-lg updateModal-modal-right" data-toggle="updateModal"
     id="updateModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-right modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h4"><i class="fas fa-plus-circle text-success"></i> Editar Proyecto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-6 col-sm-12 required">
                        <label class="form-label" for="name-url">{{ trans('general.name') }}</label>
                        <div class="input-group">
                            <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" aria-label="{{ trans('general.name') }}" wire:model="name">

                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fal fa-project-diagram"></i></span>
                            </div>
                            <div class="invalid-feedback">{{ $errors->first('name',':message') }}</div>

                        </div>
                    </div>
                    <x-form.inputs.select id="state" label="{{ trans('general.state') }}" class="col-md-6 col-sm-12 required" model="state">
                        <option value="">{{ trans('general.select') }}</option>
                        <option value="execution">Ejecución</option>
                        <option value="canceled">Cancelado</option>
                        <option value="completed">Completado</option>
                        <option value="closed">Cerrado</option>
                        <option value="suspended">Suspendido</option>
                    </x-form.inputs.select>
                    <x-form.inputs.text type="datetime" id="start_date" label="{{ trans('general.start_date') }}" class="col-md-6 col-sm-12 required" model="start_date"/>
                    <x-form.inputs.text type="datetime" id="end_date" label="{{ trans('general.end_date') }}" class="col-md-6 col-sm-12 required" model="end_date"/>
                    <div class="form-group col-md-6 col-sm-12 required">
                        <label class="form-label" for="responsible_unit-url">{{ trans('general.responsible_unit') }}</label>
                        <div class="input-group">
                            <input type="text" id="responsible_unit" class="form-control  @error('responsible_unit') is-invalid @enderror"
                                   aria-label="{{ trans('general.responsible_unit') }}" wire:model="responsible_unit">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fal fa-user-check"></i></span>
                            </div>
                            <div class="invalid-feedback">{{ $errors->first('responsible_unit',':message') }}</div>
                        </div>
                    </div>
                    <div class="form-group col-md-6 col-sm-12 required">
                        <label class="form-label" for=project_leader">{{ trans('general.project_leader') }}</label>
                        <div class="input-group">
                            <input type="text" id="project_leader" class="form-control  @error('project_leader') is-invalid @enderror"
                                   aria-label="{{ trans('general.project_leader') }}" wire:model="project_leader">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fal fa-user"></i></span>
                            </div>
                            <div class="invalid-feedback">{{ $errors->first('project_leader',':message') }}</div>
                        </div>
                    </div>
                    <div class="form-group col-md-12 col-sm-12 required">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">{{ trans('general.benefits') }}</span>
                            </div>
                            <textarea class="form-control  @error('benefits') is-invalid @enderror" id="benefits" rows="3" aria-label="{{ trans('general.description') }}"
                                      wire:model="benefits"></textarea>
                            <div class="invalid-feedback">{{ $errors->first('benefits',':message') }}</div>
                        </div>
                    </div>
                    <div class="form-group col-md-12 col-sm-12 required">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">{{ trans('general.risks') }}</span>
                            </div>
                            <textarea class="form-control  @error('risks') is-invalid @enderror" id="description" rows="3" aria-label="{{ trans('general.risks') }}"
                                      wire:model="risks"></textarea>
                            <div class="invalid-feedback">{{ $errors->first('risks',':message') }}</div>
                        </div>
                    </div>
                    <div class="form-group col-md-12 col-sm-12 required">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">{{ trans('general.components') }}</span>
                            </div>
                            <textarea class="form-control  @error('components') is-invalid @enderror" id="components" rows="3" aria-label="{{ trans('general.components') }}"
                                      wire:model="components"></textarea>
                            <div class="invalid-feedback">{{ $errors->first('components',':message') }}</div>
                        </div>
                    </div>
                    <div class="form-group col-md-12 col-sm-12 required">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">{{ trans('general.description') }}</span>
                            </div>
                            <textarea class="form-control  @error('description') is-invalid @enderror" id="description" rows="3" aria-label="{{ trans('general.description') }}"
                                      wire:model="description"></textarea>
                            <div class="invalid-feedback">{{ $errors->first('description',':message') }}</div>
                        </div>
                    </div>
                    <div class="form-group col-md-6 col-sm-12 required">
                        <label class="form-label" for="basic-url">{{ trans('general.location') }}</label>
                        <div class="input-group">
                            <input type="text" id="location" class="form-control  @error('location') is-invalid @enderror" aria-label="{{ trans('general.location') }}"
                                   wire:model="location">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fal fa-location-arrow"></i></span>
                            </div>
                            <div class="invalid-feedback">{{ $errors->first('location',':message') }}</div>
                        </div>
                    </div>
                    <div class="form-group col-md-6 col-sm-12">
                        <label class="form-label" for="basic-url">{{ trans('general.physic_advance') }}</label>
                        <div class="input-group">
                            <input type="number" id="physic_advance" class="form-control  @error('physic_advance') is-invalid @enderror"
                                   aria-label="{{ trans('general.physic_advance') }}" wire:model="physic_advance">
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="invalid-feedback">{{ $errors->first('physic_advance',':message') }}</div>
                        </div>
                    </div>
                    <div class="form-group col-md-6 col-sm-12">
                        <label class="form-label" for="basic-url">{{ trans('general.referential_budget') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="number" id="referential_budget" class="form-control  @error('referential_budget') is-invalid @enderror"
                                   aria-label="{{ trans('general.referential_budget') }}"
                                   wire:model="referential_budget">
                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>
                            <div class="invalid-feedback">{{ $errors->first('referential_budget',':message') }}</div>
                        </div>
                    </div>
                    <div class="form-group col-md-6 col-sm-12">
                        <label class="form-label" for="basic-url">{{ trans('general.executed_budget') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="number" id="executed_budget" class="form-control  @error('executed_budget') is-invalid @enderror"
                                   aria-label="{{ trans('general.executed_budget') }}" wire:model="executed_budget">
                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>
                            <div class="invalid-feedback">{{ $errors->first('executed_budget',':message') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect waves-themed" data-dismiss="modal" wire:click="cancel">{{ trans('general.cancel') }}</button>
                <button type="button" class="btn btn-primary waves-effect waves-themed" wire:click="update">{{ trans('general.save') }}</button>
            </div>
        </div>

    </div>
</div>