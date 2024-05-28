<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeUserFlag
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$params): Response
    {
        $user = $request->user();
        if ($user === null) {
            throw new AuthorizationException();
        }
        foreach ($params as $param) {
            $param = strtoupper($param);
            if ($user['user_flg'] == intval(getConstToValue("user.user_flg.{$param}"))) {
                return $next($request);
            }
        }
        throw new AuthorizationException();
    }
}
