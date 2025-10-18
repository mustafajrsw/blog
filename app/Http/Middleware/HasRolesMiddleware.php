<?php

namespace App\Http\Middleware;

use App\Traits\JsonResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasRolesMiddleware
{
    use JsonResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {

        $routeRoles = explode(',', $roles);
        $userRole = $request->user()->role;
        if (! in_array($userRole, $routeRoles, true)) {
            return $this->fail('Unauthorized', 401);
        }

        return $next($request);
    }
}
