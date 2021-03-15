<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\GroupInterface;
use App\Http\Traits\{ApiResponseTrait, UserRoleTrait, ValidationsTrait};
use App\Models\{
    Group, User
};
use Illuminate\Support\Facades\{
    Validator
};

class GroupRepository implements GroupInterface
{
    use ApiResponseTrait, UserRoleTrait, ValidationsTrait;

    private $group_model;
    private $user_model;

    public function __construct(Group $group, User $user)
    {
        $this->group_model = $group;
        $this->user_model = $user;
    }

    public function allGroups()
    {
        $groups = $this->group_model::get();

        return $this->ApiResponse(200, 'Done', null, $groups);
    }

    public function addGroup($request)
    {
        $validation = $this->getValidationsGroup($request);
        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $teacher = $this->user_role('is_teacher', 1)->find($request->teacher_id);

        if ($teacher) {
            $this->group_model::create([
                'name' => $request->name,
                'body' => $request->body,
                'image' => $request->image,
                'teacher_id' => $request->teacher_id,
                'created_by' => auth()->user()->id,
            ]);
            return $this->ApiResponse(200, 'Group was Created');
        }
        return $this->ApiResponse(422, 'This user is not a teacher');
    }

    public function specificGroup($request)
    {
        $validation = Validator::make($request->all(),[
            'group_id' => 'required|exists:groups,id'
        ]);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $group = $this->group_model::find($request->group_id);

        if($group){
            $group->first();
            return $this->ApiResponse(200, 'Done', null, $group);
        }
        return $this->ApiResponse(422, 'This User is not staff');
    }

    public function updateGroup($request)
    {
        $validation = $this->getValidationsGroup($request);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $teacher = $this->user_role('is_teacher', 1)->find($request->teacher_id);

        if (!$teacher) {
            return $this->ApiResponse(422, 'This user is not a teacher');
        }

        $group = $this->group_model::find($request->group_id);

        if ($group) {
            $group->update([
                'name' => $request->name,
                'body' => $request->body,
                'image' => $request->image,
                'teacher_id' => $request->teacher_id,
                'created_by' => auth()->user()->id,
            ]);
            return $this->ApiResponse(200,'Group was updated');
        }
        return $this->ApiResponse(422, 'This Group is not found');
    }

    public function deleteGroup($request)
    {
        $validation = Validator::make($request->all(),[
            'group_id' => 'required|exists:groups,id'
        ]);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $group = $this->group_model::find($request->group_id);

        if($group){
            $group->delete();
            return $this->ApiResponse(200, 'Group was deleted');
        }
        return $this->ApiResponse(422, 'This Group is not found');
    }
}
