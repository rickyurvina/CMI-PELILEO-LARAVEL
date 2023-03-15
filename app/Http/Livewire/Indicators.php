<?php

namespace App\Http\Livewire;

use App\Models\CatalogDetail;
use App\Models\Indicator;
use App\Models\IndicatorGoal;
use App\Models\PlanElement;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Collection;
use Livewire\Component;

class Indicators extends Component
{

    public $name, $information, $calculationFormula, $goalDescription, $source, $type, $frequency = 1, $measureUnitId, $startDate, $endDate, $responsible, $isHigherValuesBest;

    public PlanElement $element;

    public Collection $units;

    public array $frequencies = [];

    public array $data = [];
    public array $periods = [];


    protected $rules = [
        'name' => 'required',
        'type' => 'required',
        'frequency' => 'required',
        'measureUnitId' => 'required',
        'startDate' => 'required',
        'endDate' => 'required',
    ];

    protected $listeners = ['updateIndicatorList'];


    public function mount($id)
    {
        $this->element = PlanElement::find($id);
        $this->units = CatalogDetail::catalogByName('measure_units')->get();
    }

    public function store()
    {
        $this->dispatchBrowserEvent('updateChartData', ['data' => $this->data]);
        $this->validate();
        $indicator = new Indicator;
        $dates = $indicator->calcStartEndDateF($this->startDate, $this->endDate, $this->frequency);
        $indicator->fill([
            'name' => $this->name,
            'information' => $this->information,
            'calculation_formula' => $this->calculationFormula,
            'goal_description' => $this->goalDescription,
            'source' => $this->source,
            'type' => $this->type,
            'frequency' => $this->frequency,
            'measure_unit_id' => $this->measureUnitId,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'f_start_date' => date("Y-m-d", strtotime($dates['f_start_date'])),
            'f_end_date' => date("Y-m-d", strtotime($dates['f_end_date'])),
            'responsible' => $this->responsible,
            'is_higher_values_best' => $this->type == Indicator::TYPE_GOAL_ONLY ? $this->isHigherValuesBest : null,
        ]);

        $indicator = $this->element->indicators()->save($indicator);
        if ($this->frequency == 1) {//si es anual
            $periods = $indicator->numberOfPeriods('P1Y', '+0 year');
            foreach ($this->frequencies as $index => $value) {
                $date = DateTime::createFromFormat("d-m-Y", $periods[$index]);
                $d = date("d-m-Y", strtotime($periods[$index] . "+ " . 11 . " month"));
                $d = date("d-m-Y", strtotime($d . "+ " . 30 . " day"));
                $this->createIndicatorGoal($indicator->id, $value, $index, $periods, $date, $d);
            }
        }
        if ($this->frequency == 2) {//si es semestral
            $periods = $indicator->numberOfPeriods('P6M', '+0 month');
            foreach ($this->frequencies as $index => $value) {
                $date = DateTime::createFromFormat("d-m-Y", $periods[$index]);
                $d = date("d-m-Y", strtotime($periods[$index] . "+ " . 6 . " month"));
                $d = date("d-m-Y", strtotime($d . "- " . 1 . " day"));
                $this->createIndicatorGoal($indicator->id, $value, $index, $periods, $date, $d);
            }
        }
        if ($this->frequency == 4) {//si es trimestral
            $periods = $indicator->numberOfPeriods('P3M', '+0 month');
            foreach ($this->frequencies as $index => $value) {
                $date = DateTime::createFromFormat("d-m-Y", $periods[$index]);
                $d = date("d-m-Y", strtotime($periods[$index] . "+ " . 3 . " month"));
                $d = date("d-m-Y", strtotime($d . "- " . 1 . " day"));
                $this->createIndicatorGoal($indicator->id, $value, $index, $periods, $date, $d);
            }
        }
        if ($this->frequency == 3) {//si es cuatrimestral
            $periods = $indicator->numberOfPeriods('P4M', '+0 month');
            foreach ($this->frequencies as $index => $value) {
                $date = DateTime::createFromFormat("d-m-Y", $periods[$index]);
                $d = date("d-m-Y", strtotime($periods[$index] . "+ " . 4 . " month"));
                $d = date("d-m-Y", strtotime($d . "- " . 1 . " day"));
                $this->createIndicatorGoal($indicator->id, $value, $index, $periods, $date, $d);
            }
        }
        if ($this->frequency == 12) {
            $periods = $indicator->numberOfPeriods('P1M', '+0 month');
            foreach ($this->frequencies as $index => $value) {
                $date = DateTime::createFromFormat("d-m-Y", $periods[$index]);
                $d = date("d-m-Y", strtotime($periods[$index] . "+ " . 1 . " month"));
                $d = date("d-m-Y", strtotime($d . "- " . 1 . " day"));
                $this->createIndicatorGoal($indicator->id, $value, $index, $periods, $date, $d);
            }
        }
        $this->element->load('indicators');
        $this->resetInputs();
        $this->emit('closeIndicatorModal-' . $this->element->id);
        $this->dispatchBrowserEvent('alert', ['title' => trans('general.indicator.label') . '!', 'icon' => 'success', 'text' => trans('messages.success.indicator_created')]);
    }

    public function createIndicatorGoal($indicatorId, $value, $index, $periods, $date, $d){
        IndicatorGoal::create([
            'indicator_id' => $indicatorId,
            'goal' => $value,
            'period' => $index,
            'start_date' => date("Y-m-d", strtotime($periods[$index])),
            'end_date' => Carbon::createFromFormat('d-m-Y', $d)->format('Y-m-d'),
            'year' => $date->format("Y"),
        ]);
    }

    public function updated($name, $value)
    {
        if ((in_array($name, ['frequency', 'startDate', 'endDate']) || str_starts_with($name, 'frequencies')) && $this->startDate && $this->endDate) {
            $startDate = $this->startDate;
            $endDate = $this->endDate;
            $indicator = new Indicator;
            $dates = $indicator->calcStartEndDateF($startDate, $endDate, $this->frequency);
            $periods = $indicator->calcNumberOfPeriods($this->frequency, $dates['f_start_date'], $dates['f_end_date']);
            $startPeriod = $indicator->calcNumberOfPeriodStartC($startDate, $endDate, $this->frequency);
            $this->periods = $periods;
            if ($this->frequency == 2) {
                $this->calcSemester($name, $periods, $this->frequency == 1 ? 1 : 2);
            }
            if ($this->frequency == 4) {
                if ($startPeriod == 1) {
                    $count = 1;
                }
                if ($startPeriod == 4) {
                    $count = 2;
                }
                if ($startPeriod == 7) {
                    $count = 3;
                } else {
                    $count = 4;
                }
                $this->calcQuarterly($name, $periods, $count);
            }

            if ($this->frequency == 3) {
                if ($startPeriod == 1) {
                    $count = 1;
                }
                if ($startPeriod == 5) {
                    $count = 2;
                }
                if ($startPeriod == 9) {
                    $count = 3;
                }
                $this->calcFourMonths($name, $periods, $count);
            }
            if ($this->frequency == 12) {
                $this->calcMonthly($name, $periods, $this->frequency);
            }
            if ($this->frequency == 1) {
                $this->calcYear($name, $periods);
            }
        }
        $this->dispatchBrowserEvent('updateChartData-' . $this->element->id, ['data' => $this->data]);
    }
    public function calcYear($name, $periods)
    {
        if (in_array($name, ['frequency', 'startDate', 'endDate'])) {
            $this->frequencies = [];
            $this->data = [];
            for ($i = 0; $i < count($periods); $i++) {
                $date = DateTime::createFromFormat("d-m-Y", $periods[$i]);
                $count = 1;
                $this->data[] = [
                    'frequency' => Indicator::FREQUENCIES[$this->frequency][$count] . " (" . ($date->format("Y")) . ")",
                    'value' => 0
                ];
            }
        } else {
            if (str_starts_with($name, 'frequencies')) {
                foreach ($this->frequencies as $index => $val) {
                    $date = DateTime::createFromFormat("d-m-Y", $periods[$index]);
                    $count = 1;
                    $this->data[$index] = [
                        'frequency' => Indicator::FREQUENCIES[$this->frequency][$count] . " (" . ($date->format("Y")) . ")",
                        'value' => $val
                    ];
                }
            }
        }
    }
    public function calcSemester($name, $periods, $count)
    {
        if (in_array($name, ['frequency', 'startDate', 'endDate'])) {
            $this->frequencies = [];
            $this->data = [];
            for ($i = 0; $i < count($periods); $i++) {
                $date = DateTime::createFromFormat("d-m-Y", $periods[$i]);
                $this->data[] = [
                    'frequency' => Indicator::FREQUENCIES[$this->frequency][$count] . " (" . ($date->format("Y")) . ")",
                    'value' => 0
                ];
                $count++;
                if ($count > 2) {
                    $count = 1;
                }
            }
        } else {
            if (str_starts_with($name, 'frequencies')) {
                $count = 1;
                foreach ($this->frequencies as $index => $val) {
                    $date = DateTime::createFromFormat("d-m-Y", $periods[$index]);
                    $this->data[$index] = [
                        'frequency' => Indicator::FREQUENCIES[$this->frequency][$count] . " (" . ($date->format("Y")) . ")",
                        'value' => $val
                    ];
                    $count++;
                    if ($count > 2) {
                        $count = 1;
                    }
                }
            }
        }
    }
    public function calcMonthly($name, $periods, $count)
    {
        if (in_array($name, ['frequency', 'startDate', 'endDate'])) {
            $this->frequencies = [];
            $this->data = [];
            for ($i = 0; $i < count($periods); $i++) {
                $date = DateTime::createFromFormat("d-m-Y", $periods[$i]);
                $this->data[] = [
                    'frequency' => Indicator::FREQUENCIES[$this->frequency][$count] . " (" . (($date->format("Y"))) . ")",
                    'value' => 0
                ];
                $count++;
                if ($count > 12) {
                    $count = 1;
                }
            }
        } else {
            if (str_starts_with($name, 'frequencies')) {
                foreach ($this->frequencies as $index => $val) {
                    $date = DateTime::createFromFormat("d-m-Y", $periods[$index]);
                    $this->data[$index] = [
                        'frequency' => Indicator::FREQUENCIES[$this->frequency][$count] . " (" . (($date->format("Y"))) . ")",
                        'value' => $val
                    ];
                    $count++;
                    if ($count > 12) {
                        $count = 1;
                    }
                }
            }
        }
    }
    public function calcFourMonths($name, $periods, $count)
    {
        if (in_array($name, ['frequency', 'startDate', 'endDate'])) {
            $this->frequencies = [];
            $this->data = [];
            for ($i = 0; $i < count($periods); $i++) {
                $date = DateTime::createFromFormat("d-m-Y", $periods[$i]);
                $this->data[] = [
                    'frequency' => Indicator::FREQUENCIES[$this->frequency][$count] . " (" . (($date->format("Y"))) . ")",
                    'value' => 0
                ];
                $count++;
                if ($count > 3) {
                    $count = 1;
                }
            }
        } else {
            if (str_starts_with($name, 'frequencies')) {
                foreach ($this->frequencies as $index => $val) {
                    $date = DateTime::createFromFormat("d-m-Y", $periods[$index]);
                    $this->data[$index] = [
                        'frequency' => Indicator::FREQUENCIES[$this->frequency][$count] . " (" . (($date->format("Y"))) . ")",
                        'value' => $val
                    ];
                    $count++;
                    if ($count > 3) {
                        $count = 1;
                    }
                }
            }
        }
    }
    public function calcQuarterly($name, $periods, $count)
    {
        if (in_array($name, ['frequency', 'startDate', 'endDate'])) {
            $this->frequencies = [];
            $this->data = [];
            for ($i = 0; $i < count($periods); $i++) {
                $date = DateTime::createFromFormat("d-m-Y", $periods[$i]);
                $this->data[] = [
                    'frequency' => Indicator::FREQUENCIES[$this->frequency][$count] . " (" . (($date->format("Y"))) . ")",
                    'value' => 0
                ];
                $count++;
                if ($count > 4) {
                    $count = 1;
                }
            }
        } else {
            if (str_starts_with($name, 'frequencies')) {
                foreach ($this->frequencies as $index => $val) {
                    $date = DateTime::createFromFormat("d-m-Y", $periods[$index]);
                    $this->data[$index] = [
                        'frequency' => Indicator::FREQUENCIES[$this->frequency][$count] . " (" . (($date->format("Y"))) . ")",
                        'value' => $val
                    ];
                    $count++;
                    if ($count > 4) {
                        $count = 1;
                    }
                }
            }
        }
    }

    public function updateIndicatorList()
    {
        $this->element->load('indicators');
    }

    public function delete($indicatorId)
    {
        $indicator = Indicator::find($indicatorId);
        $indicator->delete();
        $this->element->load('indicators');
        $this->dispatchBrowserEvent('alert', ['title' => trans('general.indicator.label') . '!', 'icon' => 'info', 'text' => trans('messages.success.indicator_deleted')]);
    }

    public function render()
    {
        return view('livewire.indicators');
    }

    public function resetInputs()
    {
        $this->name = '';
        $this->information = '';
        $this->calculationFormula = '';
        $this->goalDescription = '';
        $this->source = '';
        $this->type = null;
        $this->frequency = 1;
        $this->measureUnitId = null;
        $this->startDate = null;
        $this->endDate = null;
        $this->responsible = '';
        $this->frequencies = [];
        $this->data = [];
        $this->periods = [];
    }
}
