<?php

namespace App\Http\Controllers\Auth;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Auth\RoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class RoleController extends Controller
{

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('permission:manage-roles');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View|View
     */
    public function index()
    {
        $roles = Role::notSuperAdmin()->collect();

        return view('auth.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View|View
     */
    public function create()
    {
        $permissions = Permission::all();

        return view('auth.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleRequest $request
     *
     * @return RedirectResponse
     */
    public function store(RoleRequest $request): RedirectResponse
    {
        $role = Role::create($request->all());

        if ($request->has('permissions')) {
            $role->givePermissionTo($request->get('permissions'));
        }

        flash(trans_choice('messages.success.added', 0, ['type' => trans_choice('general.roles', 1)]))->success();
        return redirect()->route('roles.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Role $role
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View|Response
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();

        return view('auth.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Role $role
     * @param RoleRequest $request
     *
     * @return RedirectResponse
     */
    public function update(Role $role, RoleRequest $request): RedirectResponse
    {
        $role->update($request->all());

        if ($request->has('permissions')) {
            $role->syncPermissions($request->get('permissions'));
        }

        flash(trans_choice('messages.success.updated', 0, ['type' => trans_choice('general.roles', 1)]))->success();
        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     *
     * @return RedirectResponse
     */
    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        flash(trans_choice('messages.success.deleted', 0, ['type' => trans_choice('general.roles', 1)]))->success();

        return redirect()->route('roles.index');
    }
}
