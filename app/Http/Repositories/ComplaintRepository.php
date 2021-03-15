<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\StudentInterface;
use App\Http\Traits\{ApiResponseTrait, UserRoleTrait, ValidationsTrait};
use App\Rules\ValidGroupId;
use App\Models\{Role, StudentGroup, User};
use Illuminate\Support\Facades\{
    Hash, Validator
};

class StudentRepository implements StudentInterface
{
    use ApiResponseTrait, UserRoleTrait, ValidationsTrait;

    private $user_model;
    private $role_model;
    private $studentGroup_model;

    public function __construct(User $user, Role $role, StudentGroup $studentGroup)
    {
        $this->user_model = $user;
        $this->role_model = $role;
        $this->studentGroup_model = $studentGroup;
    }

    public function allStudents()
    {
        $students = $this->user_role('is_staff', 0, 'is_teacher', 0)->with('roleName')->withCount('studentGroups')->get();

        return $this->ApiResponse(200, 'Done', null, $students);
    }

    public function addStudent($request)
    {
        $validation = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'phone' => 'required',
            'groups' => ['required', 'array', new ValidGroupId()],
            'groups.*' => 'required|min:3',
        ]);
        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $array = [];
        $groups = $request->groups;
        for ($i = 0; $i < count($groups); $i++){

            //Solution for Exist Group
//            for ($j = $i + 1; $j < count($groups); $j++) {
//                if ($groups[$i][0] == $groups[$j][0]) {
//                    return $this->ApiResponse(422, 'Validation Error', 'This Group is Exist');
//                }
//            }

            //Another Solution for Exist Group
            if (in_array($groups[$i][0], $array))
                return $this->ApiResponse(422, 'Validation Error', 'This Group is Exist');
            $array[] = $groups[$i][0];

            //Another Solution for Valid Group Id
//            $groupValidation = Validator::make($request->all(), [
//                'groups.'.$i.'.0' => 'exists:groups,id',
//            ]);
//            if($groupValidation->fails()){
//                return $this->ApiResponse(422, 'Validation Error', $groupValidation->errors());
//            }
        }

//        $array = [];
//        foreach ($groups as $group) {
//            $requestGroup = explode(',', $group);
//            if (count($requestGroup) != 3)
//                return $this->ApiResponse(422, 'Validation Error', 'ReFormat Group Data');
//            if (in_array($requestGroup[0], $array))
//                return $this->ApiResponse(422, 'Validation Error', 'This Group is Exist');
//            $array[] = $requestGroup[0];
//        }

        $studentRole = $this->role_model::where([['is_staff', 0], ['is_teacher', 0]])->first();

        $student = $this->user_model::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role_id' => $studentRole->id,
        ]);
//        foreach ($groups as $group){
//            $requestGroup = explode(',', $group);
//            $this->studentGroup_model::create([
//                'student_id' => $student->id,
//                'group_id' => $requestGroup[0],
//                'count' => $requestGroup[1],
//                'price' => $requestGroup[2]
//            ]);
//        }

        for ($i = 0; $i < count($groups); $i++){
            $this->studentGroup_model::create([
                'student_id' => $student->id,
                'group_id' => $groups[$i][0],
                'count' => $groups[$i][1],
                'price' => $groups[$i][2]
            ]);
        }
        return $this->ApiResponse(200,'Student was created');
    }

    public function specificStudent($request)
    {
        $validation = Validator::make($request->all(),[
            'student_id' => 'required|exists:users,id'
        ]);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $student = $this->user_role('is_staff', 0, 'is_teacher', 0)->find($request->student_id);

        if($student){
            $student->first();
            return $this->ApiResponse(200, 'Done', null, $student);
        }
        return $this->ApiResponse(422, 'This User is not a student');
    }

    public function updateStudent($request)
    {
        $validation = Validator::make($request->all(),[
            'student_id' => 'required|exists:users,id',
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,'.$request->student_id,
            'password' => 'required|min:8',
            'phone' => 'required',
            'groups' => ['required', 'array', new ValidGroupId()],
            'groups.*' => 'required|min:3',
        ]);
//        $validation = $this->getValidationsUser($request);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $studentRole = $this->role_model::where([['is_staff', 0], ['is_teacher', 0]])->first();
        $student = $this->user_role('is_staff', 0, 'is_teacher', 0)->find($request->student_id);

        if ($student) {
            $student->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role_id' => $studentRole->id,
            ]);

            if($request->has('groups')){

                $groups = $request->groups;
                $requestGroups = [];
//                $databaseGroups = [];
//                $oldDatabaseGroups = $this->studentGroup_model::where('student_id', $request->student_id)->get('group_id')->toArray();
//                $databaseGroups = array_column($oldDatabaseGroups, 'group_id');

//                dd($databaseGroups);
                for ($i=0; $i < count($groups); $i++){
                    if (in_array($groups[$i][0], $requestGroups))
                        return $this->ApiResponse(422, 'Validation Error', 'This Group is Exist');
                    $requestGroups[] = $groups[$i][0];

                    $studentGroup = $this->studentGroup_model::where([['student_id', $request->student_id], ['group_id', $groups[$i][0]]])->first();
                    if ($studentGroup) {
                        $studentGroup->update([
                            'count' => $groups[$i][1],
                            'price' => $groups[$i][2]
                        ]);
                    } else {
                        $this->studentGroup_model::create([
                            'student_id' => $request->student_id,
                            'group_id' => $groups[$i][0],
                            'count' => $groups[$i][1],
                            'price' => $groups[$i][2]
                        ]);
                    }
                }

//                $result=array_diff($databaseGroups,$requestGroups);
//                $resultStudentGroup = $this->studentGroup_model::where([['student_id', $request->student_id], ['group_id', $result]])->get();

                $oldStudentGroups = $this->studentGroup_model::where('student_id', $request->student_id)->whereNotIn('group_id', $requestGroups)->delete();

//                if($oldStudentGroups){
//                    $databaseGroups->each->delete();
//                }

//                foreach ($request->groups as $group){
//                    $requestGroup = explode(',', $group);
//                    $this->studentGroup_model::create([
//                        'student_id' => $student->id,
//                        'group_id' => $requestGroup[0],
//                        'count' => $requestGroup[1],
//                        'price' => $requestGroup[2]
//                    ]);
//                }
            }
            return $this->ApiResponse(200,'Student was updated');
        }
        return $this->ApiResponse(422, 'This User is not a Student');
    }

    public function deleteStudent($request)
    {
        $validation = Validator::make($request->all(),[
            'student_id' => 'required|exists:users,id'
        ]);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $student = $this->user_role('is_staff', 0, 'is_teacher', 0)->find($request->student_id);

        if($student){
            $student->delete();
            return $this->ApiResponse(200, 'Student was deleted');
        }
        return $this->ApiResponse(422, 'This User is not a Student');
    }
}
