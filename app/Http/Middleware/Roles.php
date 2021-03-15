<?php

namespace App\Http\Middleware;

use App\Http\Traits\ApiResponseTrait;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class Roles
{
    use ApiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        $userRole = auth()->user()->roleName->name;
        $allowRoles = explode(',', $roles);
        if (!in_array($userRole, $allowRoles))
            return $this->ApiResponse(422, 'Don\'t have permission');
        return $next($request);
    }
}
