<?php

namespace App\Http\Livewire;

use App\Models\Plan;
use Illuminate\Support\Str;
use Livewire\Component;

class ListItem extends Component
{
    protected $listeners = ['itemAdded'];

    public array $items = [];

    public string $type;

    public string $origValue;

    public string $newValue;

    public Plan $plan;

    public function mount($id, $type)
    {
        $this->plan = Plan::find($id);
        $this->items = $this->plan->{$type};
        $this->type = $type;

        $this->init();
    }

    public function itemAdded(){
        $this->plan = $this->plan->fresh();
        $this->items = $this->plan->{$this->type};
    }

    public function editItem()
    {
        $newName = (string)Str::of($this->newValue)->trim();

        if ($newName != '' && $newName != $this->origValue) {
            foreach ($this->items as $index => $item) {
                if ($item == $this->origValue) {
                    $this->items[$index] = $newName;
                    break;
                }
            }
            $this->storeItems();
        }

        $this->init();
    }

    private function init()
    {
        $this->origValue = '';
        $this->newValue = '';
    }

    public function removeItem($item)
    {
        $this->items = array_diff($this->items, [$item]);
        $this->storeItems();
    }

    private function storeItems()
    {
        $this->plan->{$this->type} = $this->items;
        $this->plan->save();
    }

    public function render()
    {
        return view('livewire.list-item');
    }
}
