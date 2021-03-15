<?php

namespace App\Http\Repositories;

use App\Models\User;
use App\Http\Interfaces\AuthInterface;
use App\Http\Traits\{
    TokenTrait, ApiResponseTrait
};
use Illuminate\Support\Facades\{
    Hash, Validator
};
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthRepository implements AuthInterface
{
    use ApiResponseTrait, TokenTrait;

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
        // return response()->json(['message' => 'Successfully logged out']);
        return $this->ApiResponse(200, 'Successfully logged out');

    }

    public function respondWithToken($token)
    {
    //    $role = $this->user_model::where('id', auth()->user()->id)->with('roleName')->first();

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
        return $this->ApiResponse(200, 'Done', null, $data);
    }

    public function updatePassword($request)
    {
        // $this->getAuthenticatedUser();
        JWTAuth::parseToken()->authenticate();
        $validation = Validator::make($request->all(),[
            'password' => 'required|min:8',
        ]);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $user = $this->user_model::find(auth()->user()->id);

        if ($user) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
            return $this->ApiResponse(200,'Password Was Updated');
        }
        return $this->ApiResponse(422, 'This User is not found');
    }
}
