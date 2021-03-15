<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\EndUserInterface;
use App\Http\Traits\{
    ApiResponseTrait, UserRoleTrait
};
use App\Models\{Group, Role, StudentGroup, User};
use Illuminate\Support\Facades\{
    Hash, Validator
};

class EndUserRepository implements EndUserInterface
{
    use ApiResponseTrait, UserRoleTrait;

    private $user_model;
    private $group_model;
    private $studentGroup_model;

    public function __construct(User $user, Group $group, StudentGroup $studentGroup)
    {
        $this->group_model = $group;
    }

    public function userGroups()
    {
        $userId = auth()->user()->id;
        $userRole = auth()->user()->roleName->name;
        if ($userRole == 'Teacher') {
            $data = $this->group_model::where('teacher_id', $userId)->withCount('studentGroups')->get();
        } elseif ($userRole == 'Student'){
            $data = $this->group_model::whereHas('studentGroups', function ($query) use($userId){
               return $query->where([['student_id', $userId], ['count', '>=', 1]]);
            })->withCount('studentGroups')->get();
        }
        return $this->ApiResponse(200, 'Done', null, $data);
    }
}
