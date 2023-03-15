<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Lavary\Menu\Menu as MenuItem;

class Menu
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if logged in
        if (!auth()->check()) {
            return $next($request);
        }

        $menu = (new MenuItem)->make('Menu', function () {
        });

        // Home
        $menu->add(trans('general.control_panel'), ['route' => 'home'])
            ->append('</span>')
            ->prepend('<i class="fal fa-home"></i> <span class="nav-link-text">')
            ->link->attr(['title' => trans('general.start')]);

        if (user()->can(['view-plan'])) {
            $menu->add(trans('general.strategic'), ['route' => 'plans.index'])
                ->append('</span>')
                ->prepend('<i class="far fa-arrow-alt-circle-right"></i> <span class="nav-link-text">');
        }

        if (user()->canany(['manage-roles', 'manage-users', 'manage-plans'])) {
            $menu->add(trans('general.config'), [])
                ->append('</span>')
                ->prepend('<i class="fas fa-cogs"></i> <span class="nav-link-text">');
        }

        if (user()->can(['manage-plans'])) {
            $menu->get('configuracion')->add(trans('general.strategic'), ['route' => 'plans.edit']);
        }
        if (user()->can('manage-roles')) {
            $menu->get('configuracion')->add(trans_choice('general.roles', 2), ['route' => 'roles.index']);
        }
        if (user()->can('manage-users')) {
            $menu->get('configuracion')->add(trans_choice('general.users', 2), ['route' => 'users.index']);
        }

        return $next($request);
    }
}
