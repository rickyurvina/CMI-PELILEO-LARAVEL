<?php

namespace App\Http\Livewire;

use App\Models\PlanElement;
use App\Models\Project;
use Carbon\Carbon;
use Livewire\Component;

class Projects extends Component
{

    public $projectId, $name, $description, $components, $start_date, $end_date, $responsible_unit, $project_leader, $benefits, $risks, $state, $physic_advance, $referential_budget, $executed_budget, $location,
        $plan_element_id;

    public PlanElement $element;

    public $updateMode = false;

    protected $rules = [
        'name' => 'required',
        'start_date' =>'nullable|date',
        'end_date' =>'nullable|date|after:start_date'
    ];


    public function mount($id)
    {
        $this->element = PlanElement::find($id);
    }

    public function store()
    {
        $this->validate();

        $project = new Project;
        $project->fill([
            'name' => $this->name,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'responsible_unit' => $this->responsible_unit,
            'project_leader' => $this->project_leader,
            'benefits' => $this->benefits,
            'risks' => $this->risks,
            'state' => $this->state,
            'physic_advance' => $this->physic_advance,
            'referential_budget' => $this->referential_budget,
            'executed_budget' => $this->executed_budget,
            'location' => $this->location,
            'components' => $this->components,
            'plan_elements_id' => $this->element->id
        ]);
        $this->element->projects()->save($project);

        $this->element->load('projects');
        $this->resetInputs();
        $this->emit('projectAdded');
        $this->dispatchBrowserEvent('alert', ['title' => trans('general.project') . '!', 'icon' => 'success', 'text' => trans('messages.success.project_created')]);

    }

    public function edit($id)
    {
        $this->updateMode = true;
        $project = Project::find($id);
        $this->name = $project->name;
        $this->description = $project->description;
        $this->start_date = $project->start_date ? Carbon::parse($project->start_date)->format('Y-m-d'):null;
        $this->end_date = $project->end_date ? Carbon::parse($project->end_date)->format('Y-m-d'):null;
        $this->responsible_unit = $project->responsible_unit;
        $this->project_leader = $project->project_leader;
        $this->benefits = $project->benefits;
        $this->risks = $project->risks;
        $this->state = $project->state;
        $this->physic_advance = $project->physic_advance;
        $this->referential_budget = $project->referential_budget;
        $this->executed_budget = $project->executed_budget;
        $this->location = $project->location;
        $this->components = $project->components;
        $this->projectId = $id;
    }

    public function update()
    {
        if ($this->projectId) {
            $project = Project::find($this->projectId);
            $this->validate();
            $project->update([
                'name' => $this->name,
                'description' => $this->description,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'responsible_unit' => $this->responsible_unit,
                'project_leader' => $this->project_leader,
                'benefits' => $this->benefits,
                'risks' => $this->risks,
                'state' => $this->state,
                'physic_advance' => $this->physic_advance,
                'referential_budget' => $this->referential_budget,
                'executed_budget' => $this->executed_budget,
                'location' => $this->location,
                'components' => $this->components,
                'plan_elements_id' => $this->element->id
            ]);

            $this->updateMode = false;
            $this->resetInputs();
            $this->element->load('projects');
            $this->emit('projectUpdated');
            $this->dispatchBrowserEvent('alert', ['title' => trans('general.project') . '!', 'icon' => 'info', 'text' => trans('messages.success.project_updated')]);
        }
    }

    public function delete($id)
    {
        if ($id) {
            Project::where('id', $id)->delete();
            $this->resetInputs();
            $this->element->load('projects');
            $this->dispatchBrowserEvent('alert', ['title' => trans('general.project') . '!', 'icon' => 'info', 'text' => trans('messages.success.project_deleted')]);
        }
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputs();
    }

    public function render()
    {
        return view('livewire.projects');
    }

    private function resetInputs()
    {
        $this->name = '';
        $this->description = '';
        $this->start_date = null;
        $this->end_date = null;
        $this->responsible_unit = '';
        $this->project_leader = '';
        $this->benefits = '';
        $this->risks = '';
        $this->state = null;
        $this->physic_advance = null;
        $this->referential_budget = null;
        $this->executed_budget = null;
        $this->location = '';
        $this->components = '';
    }

}
