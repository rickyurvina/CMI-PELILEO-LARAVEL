<div>
    <div x-data="{
                    isAdding: false,
                    focus: function() {
                            const textInput = this.$refs.textInput;
                            textInput.focus();
                         }
                }" x-cloak>
        <div x-show=!isAdding class="mt-1 p-2 cursor-pointer"
             x-on:click="isAdding = true; $nextTick(() => focus())">
            <span class="fs-md"><i class="fal fa-plus text-success"></i>  Nuevo</span>
        </div>
        <input type="text" class="form-control mt-1" x-show=isAdding
               placeholder="Nuevo"
               x-on:click.away="isAdding = false"
               x-ref="textInput"
               x-on:keydown.enter="isAdding = false"
               x-on:keydown.escape="isAdding = false"
               wire:model="itemName"
               wire:keydown.enter="save">
    </div>
</div>
