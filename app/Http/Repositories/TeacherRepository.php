<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\TeacherInterface;
use App\Http\Traits\{
    ApiResponseTrait, UserRoleTrait
};
use App\Models\{
    User
};
use Illuminate\Support\Facades\{
    Hash, Validator
};

class TeacherRepository implements TeacherInterface
{
    use ApiResponseTrait, UserRoleTrait;

    private $user_model;

    public function __construct(User $user)
    {
        $this->user_model = $user;
    }

    public function allTeachers()
    {
        $teachers = $this->user_role('is_teacher', 1)->with('roleName')->get();

        return $this->ApiResponse(200, 'Done', null, $teachers);
    }

    public function addTeacher($request)
    {
        $validation = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'phone' => 'required',
            // 'role_id' => 'required|exists:roles,id'
        ]);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $this->user_model::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role_id' => 2,
        ]);
        return $this->ApiResponse(200,'Teacher was created');
    }

    public function specificTeacher($request)
    {
        $validation = Validator::make($request->all(),[
            'teacher_id' => 'required|exists:users,id'
        ]);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $teacher = $this->user_role('is_teacher', 1)->find($request->teacher_id);

        if($teacher){
            $teacher->first();
            return $this->ApiResponse(200, 'Done', null, $teacher);
        }
        return $this->ApiResponse(422, 'This User is not a teacher');
    }

    public function updateTeacher($request)
    {
        $validation = Validator::make($request->all(),[
            'teacher_id' => 'required|exists:users,id',
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,'.$request->teacher_id,
            'password' => 'required|min:8',
            'phone' => 'required',
            // 'role_id' => 'required|exists:roles,id'
        ]);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $teacher = $this->user_role('is_teacher', 1)->find($request->teacher_id);

        if ($teacher) {
            $teacher->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role_id' => 2,
            ]);
            return $this->ApiResponse(200,'Teacher Was Updated');
        }
        return $this->ApiResponse(422, 'This User is not teacher');
    }

    public function deleteTeacher($request)
    {
        $validation = Validator::make($request->all(),[
            'teacher_id' => 'required|exists:users,id'
        ]);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $teacher = $this->user_role('is_teacher', 1)->find($request->teacher_id);

        if($teacher){
            $teacher->delete();
            return $this->ApiResponse(200, 'Teacher Was deleted');
        }
        return $this->ApiResponse(422, 'This User is not teacher');
    }
}
