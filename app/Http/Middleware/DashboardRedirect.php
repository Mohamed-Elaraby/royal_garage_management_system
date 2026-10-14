<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::user()->hasRole(['super_owner', 'owner', 'general_manager', 'general_observer']))
        {
            return redirect()->route('admin.check.index');
        }
        return $next($request);
    }
}
