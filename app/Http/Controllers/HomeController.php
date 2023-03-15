<?php

namespace App\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Models\IndicatorGoal;
use App\Models\Plan;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $plan = Plan::strategic()->with('elements.indicators.goals')->first();

        if (!$plan) {
            return view('home.welcome');
        }

        return view('home.index', [
            'plan' => $plan,
            'projectsProgress' => $this->projectsProgress($plan),
            'historicalScore' => $this->historicalScore($plan),
            'objectiveScore' => $this->objectiveScore($plan),
            'projectProgress' => $this->projectProgress($plan),

        ]);
    }

    private function objectiveScore($plan): array
    {
        $obj = [];
        foreach ($plan->elements as $item) {
            $obj[] = [
                'name' => $item->code,
                'value' => $item->score()
            ];
        }

        return $obj;
    }

    private function projectsProgress($plan): array
    {
        $progress = [
            'physical' => 0,
            'budget' => 0
        ];
        $physic_advance = 0;
        $executedBudget = 0;
        foreach ($plan->elements as $obj) {
            $result = $obj->progress();
            $physic_advance += $result['physical'];
            $executedBudget += $result['budget'];
        }

        $progress['physical'] = count($plan->elements) != 0 ? number_format($physic_advance / count($plan->elements), 2) : 0;
        $progress['budget'] = count($plan->elements) != 0 ? number_format(($executedBudget / count($plan->elements)), 2) : 0;

        return $progress;
    }

    private function projectProgress($plan): array
    {
        $projects = [];
        foreach ($plan->elements as $obj) {
            foreach ($obj->projects as $project) {
                $projects[] = [
                    'name' => $project->name,
                    'physical' => $project->physic_advance,
                    'budget' => $project->executed_budget
                ];
            }
        }

        return $projects;
    }

    private function historicalScore($plan): array
    {
        $scores = [];
        $scoresAvg = [];
        foreach ($plan->elements as $obj) {
            foreach ($obj->indicators as $indicator) {
                foreach ($indicator->goals as $goal) {
                    $scores[$goal->year][] = $goal->score();
                }
            }
        }

        foreach ($scores as $period => $score) {
            $avg = round(collect($score)->avg(), 2);
            $scoresAvg[] = [
                'frequency' => $period,
                'value' => $avg,
                'color' => ($avg >= IndicatorGoal::RED_FLAG && $avg < IndicatorGoal::GOAL_FLAG) ? '#fdae19' : ($avg >= IndicatorGoal::GOAL_FLAG ? '#0f9747' : '#ee1f25')
            ];
        }

        return $scoresAvg;
    }

    private function indicatorsByStatus($plan): array
    {
        $danger = 0;
        $alert = 0;
        $success = 0;
        foreach ($plan->elements as $obj) {
            $danger += isset($obj->indicatorsByStatus()['Danger']) ? count($obj->indicatorsByStatus()['Danger']) : 0;
            $alert += isset($obj->indicatorsByStatus()['Warning']) ? count($obj->indicatorsByStatus()['Warning']) : 0;
            $success += isset($obj->indicatorsByStatus()['Success']) ? count($obj->indicatorsByStatus()['Success']) : 0;
        }
        return [
            [
                'status' => 'Inaceptable',
                'count' => $danger
            ],
            [
                'status' => 'Alerta',
                'count' => $alert
            ],
            [
                'status' => 'Aceptable',
                'count' => $success
            ]
        ];
    }

}
