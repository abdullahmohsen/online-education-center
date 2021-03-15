<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\StaffInterface;
use Illuminate\Validation\Rule;

use App\Http\Traits\{
    ApiResponseTrait, UserRoleTrait
};
use App\Models\{
    Role, User
};
use Illuminate\Support\Facades\{
    Hash, Validator
};

class StaffRepository implements StaffInterface
{
    use ApiResponseTrait, UserRoleTrait;

    private $user_model;
    private $role_model;

    public function __construct(User $user, Role $role)
    {
        $this->user_model = $user;
        $this->role_model = $role;
    }

    public function allStaff()
    {
        // $roles = ['Admin', 'Support', 'Secretary'];
        // $staff_data = $this->role_model::whereIn('name', $roles)->get();

        // $staff_data = $this->role_model::where('is_staff', 1)->with('userRole')->get();

        // $staff_data = $this->user_model::whereHas('roleName', function($query){
        //     return $query->where('is_staff', 1);
        // })->with('roleName')->get();

        $allStaff = $this->user_role('is_staff', 1)->with('roleName')->get();

        return $this->ApiResponse(200, 'Done', null, $allStaff);
    }

    public function addStaff($request)
    {
        $validation = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'phone' => 'required',
            'role_id' => 'required|exists:roles,id',
        ]);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $this->user_model::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role_id' => $request->role_id,
        ]);
        return $this->ApiResponse(200,'Staff Was Created');
    }

    public function specificStaff($request)
    {
        $validation = Validator::make($request->all(),[
            'staff_id' => 'required|exists:users,id'
        ]);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $staff = $this->user_role('is_staff', 1)->find($request->staff_id);

        if($staff){
            $staff->first();
            return $this->ApiResponse(200, 'Done', null, $staff);
        }
        return $this->ApiResponse(422, 'This User is not staff');
    }

    public function updateStaff($request)
    {
        $validation = Validator::make($request->all(),[
            'staff_id' => 'required|exists:users,id',
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,'.$request->staff_id,
            'password' => 'required|min:8',
            'phone' => 'required',
            'role_id' => 'required|exists:roles,id'
        ]);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $staff = $this->user_role('is_staff', 1)->find($request->staff_id);

        if ($staff) {
            $staff->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role_id' => $request->role_id,
            ]);
            return $this->ApiResponse(200,'Staff was updated');
        }
        return $this->ApiResponse(422, 'This User is not staff');
    }

    public function deleteStaff($request)
    {
        $validation = Validator::make($request->all(),[
            'staff_id' => 'required|exists:users,id'
        ]);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $staff = $this->user_role('is_staff', 1)->find($request->staff_id);

        if($staff){
            $staff->delete();
            return $this->ApiResponse(200, 'Staff was deleted');
        }
        return $this->ApiResponse(422, 'This User is not staff');
    }
}
