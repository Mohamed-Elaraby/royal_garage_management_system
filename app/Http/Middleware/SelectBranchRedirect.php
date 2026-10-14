<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SelectBranchRedirect
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
        if (auth()->user()->hasRole(['super_owner', 'owner', 'general_manager', 'general_observer'])) {
            return redirect()->route('admin.selectBranch');
        }
        return $next($request);
    }
}
