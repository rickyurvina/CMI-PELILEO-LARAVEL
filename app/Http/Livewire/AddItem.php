<?php

namespace App\Http\Livewire;

use App\Models\Plan;
use Illuminate\Support\Str;
use Livewire\Component;

class AddItem extends Component
{

    public string $itemName = '';

    public string $type;

    public Plan $plan;

    public function mount($id, $type)
    {
        $this->plan = Plan::find($id);
        $this->type = $type;
    }

    public function save()
    {
        if (Str::of($this->itemName)->trim() != '') {
            $items = $this->plan->{$this->type};
            $items[] = $this->itemName;
            $this->plan->{$this->type} = $items;
            $this->plan->save();
            $this->itemName = '';

            $this->emit('itemAdded');
        }
    }


    public function render()
    {
        return view('livewire.add-item');
    }
}
