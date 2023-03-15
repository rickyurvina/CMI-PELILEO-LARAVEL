<?php

namespace App\Http\Livewire;

use App\Models\Indicator;
use Livewire\Component;

class IndicatorChart extends Component
{

    public Indicator $indicator;

    public array $data;

    public function mount($id)
    {
        $this->indicator = Indicator::find($id);
        $typeFrequency = $this->indicator->frequency;
        $startPeriod = $this->indicator->calcNumberOfPeriodStart();
        if ($typeFrequency == 2) {
            $this->showIfSemester($startPeriod == 1 ? 1 : 2);
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
            $this->saveIfQuarterly($count);
        }
        if ($typeFrequency== 3) {
            if ($startPeriod == 1) {
                $count = 1;
            }
            if ($startPeriod == 5) {
                $count = 2;
            }
            if ($startPeriod == 9) {
                $count = 3;
            }
            $this->saveIfFourMonths($count);
        }
        if ($typeFrequency == 12) {
            $this->showIfMonthly($startPeriod);
        }
        if ($typeFrequency == 1) {
            $this->showIfYearly();
        }

    }

    public function showIfYearly()
    {
        foreach ($this->indicator->goals as $goal) {
            $count = 1;
            $this->data[] = [
                'frequency' => Indicator::FREQUENCIES[$this->indicator->frequency][$count] . " (" . ($goal->year) . ")",
                'value' => $goal->goal,
                'actual' => $goal->actual,
                'color' => $goal->colorChart(),
                'year' => $goal->year,
                'progress' => $goal->progress()
            ];

        }
    }

    public function showIfMonthly($count)
    {
        foreach ($this->indicator->goals as $goal) {
            $this->data[] = [
                'frequency' => Indicator::FREQUENCIES[$this->indicator->frequency][$count] . " (" . ($goal->year) . ")",
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

    public function showIfSemester($count)
    {
        foreach ($this->indicator->goals as $goal) {
            $this->data[] = [
                'frequency' => Indicator::FREQUENCIES[$this->indicator->frequency][$count] . " (" . ($goal->year) . ")",
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

    public function saveIfQuarterly($count)
    {
        foreach ($this->indicator->goals as $goal) {
            $this->data[] = [
                'frequency' => Indicator::FREQUENCIES[$this->indicator->frequency][$count] . " (" . ($goal->year) . ")",
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

    public function saveIfFourMonths($count)
    {
        foreach ($this->indicator->goals as $goal) {
            $this->data[] = [
                'frequency' => Indicator::FREQUENCIES[$this->indicator->frequency][$count] . " (" . ($goal->year) . ")",
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

    public function render()
    {
        return view('livewire.indicator-chart');
    }
}
