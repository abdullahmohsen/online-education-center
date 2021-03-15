<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Validator;

trait ValidationsTrait
{
    public function getValidationsGroup($request)
    {
        return Validator::make($request->all(),[
            'group_id' => 'required_without:id|exists:groups,id',
            'name' => 'required|min:3',
            'body' => 'required',
            'image' => 'required',
            'teacher_id' => 'required|exists:users,id',
        ]);
    }

    public function getValidationsUser($request)
    {
        return Validator::make($request->all(),[
            'student_id' => 'required_without:id|exists:users,id',
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users'.$request->student_id,
            'password' => 'required|min:8',
            'phone' => 'required',
            'role_id' => 'required|exists:roles,id'
        ]);
    }
}
