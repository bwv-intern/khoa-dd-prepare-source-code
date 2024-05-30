<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoCache
{
    /**
     * Issue no cache header and its equivalents when generating responses
     *
     * @param Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @param Request $request
     */
    public function handle(Request $request, Closure $next): Response {
        $response = $next($request);
        $response->header(
            'Cache-Control',
            'nocache, no-store, max-age=0, must-revalidate',
        )->header(
            'Pragma',
            'no-cache',
        )->header(
            'Expires',
            'Fri, 01 Jan 1990 00:00:00 GMT',
        );

        return $response;
    }
}
