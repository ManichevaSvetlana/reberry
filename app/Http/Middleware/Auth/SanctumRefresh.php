<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Http\Request;

class SanctumRefresh
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->user()->checkTokenForRefresh();
        if($token !== false) return response()->json(['message' => 'Your token needed a refresh. Please retry the request with a new token.', 'token' => $token], 577);

        return $next($request);
    }
}
