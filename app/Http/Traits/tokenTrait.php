<?php

namespace App\Http\Traits;

use Tymon\JWTAuth\Facades\JWTAuth;

trait TokenTrait
{

    public function getAuthenticatedUser()
    {

        /** Build:
         * check if user token found or not.
         * check if token expired or not.
         * check if token invalid or not.
         * check if token absent or not.
         */

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                // return response()->json(['user_not_found'], 404);
                return $this->apiResponse(404, "user_not_found");
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            // return response()->json(['token_expired'], $e->getStatusCode());
            return $this->apiResponse(422, "token_expired", $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            // return response()->json(['token_invalid'], $e->getStatusCode());
            return $this->apiResponse(425, "token_invalid", $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            // return response()->json(['token_absent'], $e->getStatusCode());
            return $this->apiResponse(422, "token_absent", $e->getStatusCode());
        }
        return $user;
    }
}
