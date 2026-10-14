<?php

namespace App\Http\Middleware;

use Closure;

class CheckOrdersStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $allowed_status = ['close', 'open'];
        if (!$request -> has('status') || empty($request -> status) || !in_array($request -> status, $allowed_status)) {
            abort(404);
        }
        return $next($request);
    }
}
