<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class JwtVerify extends BaseMiddleware
{
    use ApiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();

        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException)
                return $this->apiResponse(422, "Token is invalid");
            elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException)
                return $this->apiResponse(422, "Token is Expired");
            else
                return $this->apiResponse(404, "Authorization Token not found");
        }
        return $next($request);
    }
}
