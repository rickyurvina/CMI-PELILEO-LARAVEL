<?php

namespace App\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Models\CatalogDetail;
use App\Models\PlanElement;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class ObjectiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(PlanElement $objective)
    {
        if(!user()->can('view-plan'))
            abort(403);

        $objective->load('indicators');


        return view('tracking.objective.index', compact('objective'));
    }
}
