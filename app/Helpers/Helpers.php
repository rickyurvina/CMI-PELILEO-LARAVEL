<?php


use Illuminate\Contracts\Auth\Authenticatable;

if (!function_exists('user')) {
    /**
     * Get the authenticated user.
     *
     * @return Authenticatable
     */
    function user()
    {
        return auth()->user();
    }
}