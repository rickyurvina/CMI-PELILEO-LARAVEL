<?php

namespace App\Http\Livewire;

use App\Models\CatalogDetail;
use App\Models\Indicator;
use App\Models\IndicatorGoal;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Component;
use phpDocumentor\Reflection\Types\Boolean;

class IndicatorEdit extends Component
{
    protected $listeners = ['open' => 'loadIndicator'];

    public $name, $information, $calculationFormula, $goalDescription, $source, $type, $frequency, $measureUnitId, $startDate, $endDate, $responsible, $isHigherValuesBest;

    public Indicator $indicator;

    public Collection $units;

    public array $plan = [];

    public array $data = [];

    public array $real = [];

    public array $dataF = [];

    public array $periods = [];

    public array $updatePeriod = [];

    public $hasProgress = false;

    protected $rules = [
        'name' => 'required',
        'type' => 'required',
        'frequency' => 'required',
        'measureUnitId' => 'required',
        'startDate' => 'required',
        'endDate' => 'required',
    ];


    public function mount()
    {
        $this->units = CatalogDetail::catalogByName('measure_units')->get();
    }

    public function loadIndicator($indicatorId)
    {
        $this->indicator = Indicator::find($indicatorId);
        $this->indicator->load('goals');
        $this->hasProgress = $this->indicator->hasProgress();
        $this->plan = [];
        $this->real = [];
        $this->updatePeriod = [];
        foreach ($this->indicator->goals as $goal) {
            $this->plan[$goal->period] = $goal->goal;
            $this->real[$goal->period] = $goal->actual;
            $this->updatePeriod[$goal->period] = $goal->canUpdateActual();
        }

        $this->name = $this->indicator->name;
        $this->information = $this->indicator->information;
        $this->calculationFormula = $this->indicator->calculation_formula;
        $this->goalDescription = $this->indicator->goal_description;
        $this->source = $this->indicator->source;
        $this->type = $this->indicator->type;
        $this->frequency = $this->indicator->frequency;
        $this->measureUnitId = $this->indicator->measure_unit_id;
        $this->startDate = $this->indicator->start_date;
        $this->endDate = $this->indicator->end_date;
        $this->responsible = $this->indicator->responsible;
        $this->isHigherValuesBest = $this->indicator->is_higher_values_best;
        $dates = $this->indicator->calcStartEndDateF($this->startDate, $this->endDate, $this->frequency);
        $periods = $this->indicator->calcNumberOfPeriods($this->frequency, $dates['f_start_date'], $dates['f_end_date']);
        $this->periods = $periods;
        $startDate = $this->startDate;
        $endDate = $this->endDate;
        $typeFrequency = $this->indicator->frequency;
        $startPeriod = $this->indicator->calcNumberOfPeriodStartC($startDate, $endDate, $typeFrequency);

        $this->validatePeriodGoal($startPeriod, $typeFrequency, $periods);

        $this->emit('toggleIndicatorEditModal');
    }


    public function update()
    {
        $indicator = new Indicator;
        $dates = $indicator->calcStartEndDateF($this->startDate, $this->endDate, $this->frequency);
        $typeFrequency = $this->frequency;
        $startDate = $this->startDate;
        $endDate = $this->endDate;
        $startPeriod = $this->indicator->calcNumberOfPeriodStartC($startDate, $endDate, $typeFrequency);
        $this->indicator->fill([
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
            'f_start_date' => $dates['f_start_date'],
            'f_end_date' => $dates['f_end_date'],
            'responsible' => $this->responsible,
            'is_higher_values_best' => $this->type == Indicator::TYPE_GOAL_ONLY ? $this->isHigherValuesBest : null,
        ]);

        $this->indicator->save();
        $this->indicator->goals()->delete();

        if ($this->frequency == 1) {//si es anual
            for ($i = 0; $i < count($this->periods); $i++) {
                $date = DateTime::createFromFormat("d-m-Y", $this->periods[$i]);
                $d = date("d-m-Y", strtotime($this->periods[$i] . "+ " . 11 . " month"));
                $d = date("d-m-Y", strtotime($d . "+ " . 30 . " day"));
                $this->createIndicatorGoal($i, $d, $date);
            }
        }
        if ($this->frequency == 2) {//si es semestral
            for ($i = 0; $i < count($this->periods); $i++) {
                $date = DateTime::createFromFormat("d-m-Y", $this->periods[$i]);
                $d = date("d-m-Y", strtotime($this->periods[$i] . "+ " . 6 . " month"));
                $d = date("d-m-Y", strtotime($d . "- " . 1 . " day"));
                $this->createIndicatorGoal($i, $d, $date);
            }
        }
        if ($this->frequency == 4) {//si es trimestral
            for ($i = 0; $i < count($this->periods); $i++) {
                $date = DateTime::createFromFormat("d-m-Y", $this->periods[$i]);
                $d = date("d-m-Y", strtotime($this->periods[$i] . "+ " . 3 . " month"));
                $d = date("d-m-Y", strtotime($d . "- " . 1 . " day"));
                $this->createIndicatorGoal($i, $d, $date);
            }
        }
        if ($this->frequency == 3) {//si es cuatrimestral
            for ($i = 0; $i < count($this->periods); $i++) {
                $date = DateTime::createFromFormat("d-m-Y", $this->periods[$i]);
                $d = date("d-m-Y", strtotime($this->periods[$i] . "+ " . 4 . " month"));
                $d = date("d-m-Y", strtotime($d . "- " . 1 . " day"));
                $this->createIndicatorGoal($i, $d, $date);
            }
        }
        if ($this->frequency == 12) {// {
            for ($i = 0; $i < count($this->periods); $i++) {
                $date = DateTime::createFromFormat("d-m-Y", $this->periods[$i]);
                $d = date("d-m-Y", strtotime($this->periods[$i] . "+ " . 1 . " month"));
                $d = date("d-m-Y", strtotime($d . "- " . 1 . " day"));
                $this->createIndicatorGoal($i, $d, $date);
            }
        }
        $this->indicator->load('goals');
        $this->validatePeriodGoal($startPeriod, $typeFrequency);
        $this->emit('toggleIndicatorEditModal');
        $this->emit('updateIndicatorList');
        $this->dispatchBrowserEvent('updateIndicatorChart-' . $this->indicator->id, ['data' => $this->data]);
        $this->dispatchBrowserEvent('alert', ['title' => trans('general.indicator.label') . '!', 'icon' => 'info', 'text' => trans('messages.success.indicator_updated')]);
    }

    public function updated($name, $value)
    {
        $startDate = $this->startDate;
        $endDate = $this->endDate;
        $indicator = new Indicator;
        $dates = $indicator->calcStartEndDateF($startDate, $endDate, $this->frequency);
        $periods = $indicator->calcNumberOfPeriods($this->frequency, $dates['f_start_date'], $dates['f_end_date']);
        $startPeriod = $indicator->calcNumberOfPeriodStartC($startDate, $endDate, $this->frequency);
        $this->periods = $periods;
        if ($this->frequency == 2) {
            $this->calcSemester($startPeriod == 1 ? 1 : 2, $periods);
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
            $this->calcQuarterly($count, $periods);
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
            $this->calcFourMonths($count, $periods);
        }
        if ($this->frequency == 12) {
            $this->calcMonthly($startPeriod, $periods);
        }
        if ($this->frequency == 1) {
            $this->calcYear($periods);
        }
    }


    public function createIndicatorGoal($i, $d, $date)
    {
        if (empty($this->plan[$i])) {
            $this->plan[$i] = 0;
        }
        if (isset($this->real[$i])) {
            if ($this->real[$i] == "") {
                $this->real[$i] = null;
            }
        }
        IndicatorGoal::create([
            'indicator_id' => $this->indicator->id,
            'goal' => $this->plan[$i],
            'actual' => $this->real[$i] ?? null,
            'period' => $i,
            'start_date' => Carbon::createFromFormat('d-m-Y', $this->periods[$i])->format('Y-m-d'),
            'end_date' => Carbon::createFromFormat('d-m-Y', $d)->format('Y-m-d'),
            'year' => $date->format("Y"),
        ]);
    }

    public function validatePeriodGoal($startPeriod, $typeFrequency, $periods = null)
    {
        if ($typeFrequency == 2) {
            $this->calcSemester($startPeriod == 1 ? 1 : 2, $periods);
        }
        if ($typeFrequency == 4) {
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
            $this->calcQuarterly($count, $periods);
        }
        if ($typeFrequency == 3) {
            if ($startPeriod == 1) {
                $count = 1;
            }
            if ($startPeriod == 5) {
                $count = 2;
            }
            if ($startPeriod == 9) {
                $count = 3;
            }
            $this->calcFourMonths($count, $periods);
        }
        if ($typeFrequency == 12) {
            $this->calcMonthly($startPeriod, $periods);
        }
        if ($typeFrequency == 1) {
            $this->calcYear($periods);
        }
    }


    public function calcYear($periods = null)
    {
        $this->data = [];
        $count = 1;
        if (isset($periods)) {
            for ($i = 0; $i < count($this->periods); $i++) {
                $date = DateTime::createFromFormat("d-m-Y", $periods[$i]);
                $this->data[] = [
                    'frequency' => Indicator::FREQUENCIES[$this->frequency][$count] . " (" . ($date->format("Y")) . ")",
                ];
            }
        } else {
            foreach ($this->indicator->goals as $index => $goal) {
                $date = DateTime::createFromFormat("d-m-Y", $this->periods[$index]);
                $this->data[] = [
                    'frequency' => Indicator::FREQUENCIES[$this->frequency][$count] . " (" . ($date->format("Y")) . ")",
                    'value' => $goal->goal,
                    'actual' => $goal->actual,
                    'color' => $goal->colorChart(),
                    'year' => $goal->year,
                    'progress' => $goal->progress()
                ];
            }
        }
    }

    public function calcSemester($count, $periods = null)
    {
        $this->data = [];
        if (isset($periods)) {
            for ($i = 0; $i < count($this->periods); $i++) {
                $date = DateTime::createFromFormat("d-m-Y", $periods[$i]);
                $this->data[] = [
                    'frequency' => Indicator::FREQUENCIES[$this->frequency][$count] . " (" . ($date->format("Y")) . ")",
                ];
                $count++;
                if ($count > 2) {
                    $count = 1;
                }
            }
        } else {
            foreach ($this->indicator->goals as $index => $goal) {
                $date = DateTime::createFromFormat("d-m-Y", $this->periods[$index]);
                $this->data[] = [
                    'frequency' => Indicator::FREQUENCIES[$this->frequency][$count] . " (" . ($date->format("Y")) . ")",
                    'value' => $goal->goal,
                    'actual' => $goal->actual,
                    'color' => $goal->colorChart(),
                    'year' => $goal->year,
                    'progress' => $goal->progress()
                ];
                $count++;
                if ($count > 2) {
                    $count = 1;
                }
            }
        }
    }

    public function calcMonthly($count, $periods = null)
    {
        $this->data = [];
        if (isset($periods)) {
            for ($i = 0; $i < count($this->periods); $i++) {
                $date = DateTime::createFromFormat("d-m-Y", $periods[$i]);
                $this->data[] = [
                    'frequency' => Indicator::FREQUENCIES[$this->frequency][$count] . " (" . ($date->format("Y")) . ")",
                ];
                $count++;
                if ($count > 12) {
                    $count = 1;
                }
            }
        } else {
            foreach ($this->indicator->goals as $index => $goal) {
                $date = DateTime::createFromFormat("d-m-Y", $this->periods[$index]);
                $this->data[] = [
                    'frequency' => Indicator::FREQUENCIES[$this->frequency][$count] . " (" . ($date->format("Y")) . ")",
                    'value' => $goal->goal,
                    'actual' => $goal->actual,
                    'color' => $goal->colorChart(),
                    'year' => $goal->year,
                    'progress' => $goal->progress()
                ];
                $count++;
                if ($count > 12) {
                    $count = 1;
                }
            }
        }
    }

    public function calcFourMonths($count, $periods = null)
    {
        $this->data = [];

        if (isset($periods)) {
            for ($i = 0; $i < count($this->periods); $i++) {
                $date = DateTime::createFromFormat("d-m-Y", $periods[$i]);
                $this->data[] = [
                    'frequency' => Indicator::FREQUENCIES[$this->frequency][$count] . " (" . ($date->format("Y")) . ")",
                ];
                $count++;
                if ($count > 3) {
                    $count = 1;
                }
            }
        } else {
            foreach ($this->indicator->goals as $index => $goal) {
                $date = DateTime::createFromFormat("d-m-Y", $this->periods[$index]);
                $this->data[] = [
                    'frequency' => Indicator::FREQUENCIES[$this->frequency][$count] . " (" . ($date->format("Y")) . ")",
                    'value' => $goal->goal,
                    'actual' => $goal->actual,
                    'color' => $goal->colorChart(),
                    'year' => $goal->year,
                    'progress' => $goal->progress()
                ];
                $count++;
                if ($count > 3) {
                    $count = 1;
                }
            }
        }
    }

    public function calcQuarterly($count, $periods = null)
    {
        $this->data = [];

        if (isset($periods)) {
            for ($i = 0; $i < count($this->periods); $i++) {
                $date = DateTime::createFromFormat("d-m-Y", $periods[$i]);
                $this->data[] = [
                    'frequency' => Indicator::FREQUENCIES[$this->frequency][$count] . " (" . ($date->format("Y")) . ")",
                ];
                $count++;
                if ($count > 4) {
                    $count = 1;
                }
            }
        } else {
            foreach ($this->indicator->goals as $index => $goal) {
                $date = DateTime::createFromFormat("d-m-Y", $this->periods[$index]);
                $this->data[] = [
                    'frequency' => Indicator::FREQUENCIES[$this->frequency][$count] . " (" . ($date->format("Y")) . ")",
                    'value' => $goal->goal,
                    'actual' => $goal->actual,
                    'color' => $goal->colorChart(),
                    'year' => $goal->year,
                    'progress' => $goal->progress()
                ];
                $count++;
                if ($count > 4) {
                    $count = 1;
                }
            }
        }
    }


    public function render()
    {
        return view('livewire.indicator-edit');
    }
}
