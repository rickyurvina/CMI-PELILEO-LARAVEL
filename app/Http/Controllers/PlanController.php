<?php

namespace App\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Http\Requests\PlanRequest;
use App\Models\Plan;
use App\Models\PlanElement;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PlanController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        if(!user()->can('view-plan'))
            abort(403);

        $plan = Plan::strategic()->first();

        if (!$plan) {
            return view('home.welcome');
        }

        $objectives = PlanElement::where('plan_id', $plan->id)->with('axis')->get()->groupBy('axis_id')->all();

        return view('plan.index', compact('plan', 'objectives'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Application|Factory|View
     */
    public function edit()
    {
        if(!user()->can('manage-plans'))
            abort(403);

        $plan = Plan::strategic()->first();
        return view('tracking.plans.edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param PlanRequest $request
     *
     * @return RedirectResponse
     */
    public function update(int $id, PlanRequest $request): RedirectResponse
    {
        if(!user()->can('manage-plans'))
            abort(403);

        Plan::updateOrCreate(
            ['id' => $id],
            $request->all()
        );

        flash(trans('messages.success.plan_updated'))->success();
        return redirect()->route('plans.edit');
    }
}
