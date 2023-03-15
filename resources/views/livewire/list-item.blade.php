<div class="check-list-wrapper">
    @foreach($items as $item)
        <div>
            <div x-data="{
                         isEditing: false,
                         value: '{{ $item }}',
                         newValue: '{{ $item }}',
                         focus: function() {
                            const textInput = this.$refs.textInput;
                            textInput.focus();
                         }
                    }" x-cloak>
                <div x-show="!isEditing">
                    <div class="p-2 item d-flex justify-content-between align-items-center">
                        <div class="cursor-pointer" style="border-bottom-style: dashed;border-bottom-width: 1px;"
                             x-on:click="isEditing = true; $nextTick(() => focus())"
                             wire:click="$set('origValue', '{{ $item }}')">
                            <span x-text="value"></span>
                        </div>
                        <span wire:click="removeItem('{{ $item }}')" class="cursor-pointer trash"><i class="fas fa-trash text-danger"></i></span>
                    </div>
                </div>
                <div x-show=isEditing class="mb-2">
                    <form wire:submit.prevent="editItem">
                        <input
                                type="text"
                                class="form-control"
                                x-ref="textInput"
                                x-model="newValue"
                                wire:model="newValue"
                                x-on:keydown.enter="isEditing = false"
                                x-on:keydown.escape="isEditing = false"
                                x-on:click.away="isEditing = false; newValue = value"
                        >
                        <a href="javascript:void(0);" title="Cancelar"
                           class="btn btn-outline-danger btn-xs btn-icon border-0"
                           x-on:click="isEditing = false">
                            <i class="fal fa-times"></i>
                        </a>
                        <button
                                type="submit"
                                class="btn btn-outline-success btn-xs btn-icon border-0"
                                title="Guardar"
                                x-on:click="isEditing = false"><i class="fal fa-check"></i>
                        </button>
                    </form>
                    <small class="text-xs">Enter para guardar, Esc para cancelar</small>
                </div>
            </div>
        </div>
    @endforeach
</div>