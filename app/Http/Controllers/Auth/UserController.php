<?php

namespace App\Http\Controllers\Auth;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Auth\UserRequest;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('permission:manage-users');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View|View
     */
    public function index()
    {
        $users = User::with( 'roles')->collect();

        return view('auth.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $roles = Role::notSuperAdmin()->get();

        return view('auth.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     *
     * @return RedirectResponse
     */
    public function store(UserRequest $request): RedirectResponse
    {
        try {
            $user = User::create($request->input());

            if ($request->has('permissions')) {
                $user->permissions()->attach($request->get('permissions'));
            }

            if ($request->has('roles')) {
                $user->roles()->attach($request->get('roles'));
            }

            flash(trans_choice('messages.success.added', 0, ['type' => trans_choice('general.users', 1)]))->success();
            return redirect()->route('users.index');
        } catch (Exception $e) {
            flash($e->getMessage())->error();
            return redirect()->route('users.create');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function edit(User $user)
    {

        $roles = Role::notSuperAdmin()->get();
        $userRolesIds = $user->roles->pluck('id');

        return view('auth.users.edit', compact('user', 'roles', 'userRolesIds'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param User $user
     * @param UserRequest $request
     *
     * @return RedirectResponse
     */
    public function update(User $user, UserRequest $request): RedirectResponse
    {
        try {
            // Can't disable yourself
            if (($request->get('enabled', 1) == 0) && ($user->id == user()->id)) {
                $message = trans('auth.error.self_disable');

                throw new Exception($message);
            }

            // Do not reset password if not entered/changed
            if (empty($request['password'])) {
                unset($request['password']);
                unset($request['password_confirmation']);
            }

            $user->update($request->input());

            if ($request->has('roles')) {
                $user->roles()->sync($request->get('roles'));
            }

            flash(trans_choice('messages.success.updated', 0, ['type' => trans_choice('general.users', 1)]))->success();
            return redirect()->route('users.index');

        } catch (Exception $e) {
            flash($e->getMessage())->error();
            return redirect()->route('users.edit');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     *
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        try {
            // Can't delete yourself
            if ($user->id == user()->id) {
                $message = trans('auth.error.self_delete');

                throw new Exception($message);
            }
            $user->delete();
            flash(trans_choice('messages.success.deleted', 0, ['type' => trans_choice('general.users', 1)]))->success();
        } catch (Exception $e) {
            flash($e->getMessage())->error();
        }

        return redirect()->route('users.index');
    }
}
