<?php

namespace App\Http\Repositories;

use App\Models\User;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Interfaces\AuthInterface;

class AuthRepository implements AuthInterface
{
    use ApiResponseTrait;

    private $user_model;

    public function __construct(User $user)
    {
        $this->user_model = $user;
    }

    public function HandleLogin()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return $this->ApiResponse(422, 'Unauthorized');
        }
        return $this->respondWithToken($token);
    }

    public function HandleLogout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function respondWithToken($token)
    {
//        $roleName = $this->user_model::where('id', auth()->user()->id)->with('roleName')->first();

//        $roleName = $this->user_model::where('id', auth()->user()->id)->whereHas('roleName', function ($q) {
//            $q->where('name', 'Admin');
//        })->first();

        $userData = $this->user_model::find(auth()->user()->id);
        $data = [
            'name' => $userData->name,
            'role' => auth()->user()->roleName->name,
            'email' => $userData->email,
            'phone' => $userData->phone,
            'access_token' => $token,
        ];
        return $this->ApiResponse(200, 'Done',null, $data);
    }
}
