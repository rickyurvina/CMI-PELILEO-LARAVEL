<?php

namespace App\Http\Livewire;

use App\Models\CatalogDetail;
use App\Models\Plan;
use App\Models\PlanElement;
use Illuminate\Support\Collection;
use Livewire\Component;

class Objectives extends Component
{

    public Plan $plan;

    public string $code = '';

    public string $name = '';

    public string $description = '';

    public Plan $ods;

    public Plan $pdot;

    public array $selectedPdotObj = [];

    public array $selectedOdsObj = [];

    public array $selectedPdotObjEdit = [];

    public array $selectedOdsObjEdit = [];

    public Collection $axis;

    public $selectedAxis;

    public Collection $focus;

    public $selectedFocus, $planElementId;

    public $updateMode = false;

    protected $rules = [
        'code' => 'required',
        'name' => 'required|max:80',
        'selectedAxis' => 'required',
        'selectedFocus' => 'required',
    ];

    public function mount($id)
    {
        $this->plan = Plan::with('elements')->find($id);
        $this->ods = Plan::ods()->with('elements')->first();
        $this->pdot = Plan::pdot()->with('elements')->first();
        $this->axis = CatalogDetail::catalogByName('axis')->get();
        $this->focus = CatalogDetail::catalogByName('focus')->get();
    }

    public function save()
    {
        $this->validate();
        $element = PlanElement::create([
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'plan_id' => $this->plan->id,
            'axis_id' => intval($this->selectedAxis) ?: null,
            'focus_id' => intval($this->selectedFocus) ?: null,
        ]);
        $element->links()->attach(array_merge($this->selectedOdsObj, $this->selectedPdotObj));
        $this->resetInputs();
        $this->plan = $this->plan->load('elements');
        $this->emit('objectiveAdded');
        $this->dispatchBrowserEvent('alert', ['title' => trans('general.objective') . '!', 'icon' => 'success', 'text' => trans('messages.success.objective_created')]);

    }

    public function edit($id)
    {
        $this->resetInputs();
        $this->updateMode = true;
        $objective = PlanElement::find($id);
        $pdot = array();
        $ods = array();

        foreach ($objective->linksType('PDOT')->get()->pluck('id') as $item1) {
            $pdot[$item1] = $item1;
        }

        foreach ($objective->linksType('ODS')->get()->pluck('id') as $item) {
            $ods[$item] = $item;
        }
        $this->code = $objective->code;
        $this->name = $objective->name;
        $this->description = $objective->description;
        $this->planElementId = $id;
        $this->selectedPdotObjEdit = $pdot;
        $this->selectedOdsObjEdit = $ods;

        $this->selectedAxis = $objective->axis_id;
        $this->selectedFocus = $objective->focus_id;
    }

    public function update()
    {
        if ($this->planElementId) {
            $objective = PlanElement::find($this->planElementId);
            $this->validate();
            $objective->update([
                'code' => $this->code,
                'name' => $this->name,
                'description' => $this->description,
                'axis_id' => $this->selectedAxis,
                'focus_id' => $this->selectedFocus,
            ]);
            $this->selectedOdsObjEdit = array_filter($this->selectedOdsObjEdit);
            $this->selectedPdotObjEdit = array_filter($this->selectedPdotObjEdit);
            $objective->links()->sync(array_merge($this->selectedOdsObjEdit, $this->selectedPdotObjEdit));
            $this->resetInputs();
            $this->plan = $this->plan->load('elements');
        }
        $this->emit('objectiveUpdated');
        $this->dispatchBrowserEvent('alert', ['title' => trans('general.objective') . '!', 'icon' => 'info', 'text' => trans('messages.success.objective_updated')]);

    }

    public function delete($id)
    {
        $this->resetInputs();
        if ($id) {
            $obj = PlanElement::find($id);
            $obj->links()->detach();
            $obj->delete();
            $this->plan = $this->plan->load('elements');
            $this->dispatchBrowserEvent('alert', ['title' => trans('general.objective') . '!', 'icon' => 'info', 'text' => trans('messages.success.objective_deleted')]);
        }
    }

    public function updated($propertyName)
    {
//        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.objectives');
    }

    public function resetInputs()
    {
        $this->code = '';
        $this->name = '';
        $this->description = '';
        $this->selectedPdotObj = [];
        $this->selectedOdsObj = [];
        $this->selectedPdotObjEdit = [];
        $this->selectedOdsObjEdit = [];
        $this->selectedAxis = '';
        $this->selectedFocus = '';
    }
}
