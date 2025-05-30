<?php

namespace App\Http\Middleware;

use Illuminate\Support\Str;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson())
        {
            // Ambil guard dari route middleware (bisa lebih dari satu guard)
            if (Str::startsWith($request->path(), 'admin'))
            {
                return route('admin.login');
            }

            // Default guard (web)
            return route('login');
        }
    }
}
