<?php

namespace App\Http\Repositories;

use App\Http\Traits\ApiResponseTrait;
use App\Http\Interfaces\GroupSessionInterface;
use App\Models\GroupSession;
use App\Models\StudentGroup;
use Illuminate\Support\Facades\Validator;

class GroupSessionRepository implements GroupSessionInterface
{
    use ApiResponseTrait;

    private $groupSession_model;
    private $studentGroup_model;

    public function __construct(GroupSession $groupSession, StudentGroup $studentGroup)
    {
        $this->groupSession_model = $groupSession;
        $this->studentGroup_model = $studentGroup;
    }

    public function allGroupSessions()
    {
        $groupSessions = $this->groupSession_model::with('group')->get();

        return $this->ApiResponse(200, 'Done', null, $groupSessions);
    }

    public function addGroupSession($request)
    {
        $validation = Validator::make($request->all(), [
            'group_id' => 'required|exists:groups,id',
            'name' => 'required',
            'from' => 'required',
            'to' => 'required',
            'link' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $this->groupSession_model::create([
            'group_id' => $request->group_id,
            'name' => $request->name,
            'from' => $request->from,
            'to' => $request->to,
            'link' => $request->link,
        ]);

        $this->studentGroup_model::where([['count', '>', '0'], ['group_id', $request->group_id]])->decrement('count');

//        $studentGroupsCount = $this->studentGroup_model::where('group_id', $request->group_id)->get();
//        foreach ($studentGroupsCount as $studentGroupCount) {
//            $studentGroupCount->update([
//                'count' => $studentGroupCount->count - 1
//            ]);
//        }

        return $this->ApiResponse(200, 'Group Session was Created');
    }

    public function specificGroupSession($request)
    {
        $validation = Validator::make($request->all(),[
            'groupSession_id' => 'required|exists:group_sessions,id'
        ]);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $groupSession = $this->groupSession_model::find($request->groupSession_id)->with('group')->first();

        if($groupSession)
            return $this->ApiResponse(200, 'Done', null, $groupSession);
        return $this->ApiResponse(422, 'This Group session is not found');
    }

    public function updateGroupSession($request)
    {
//        $validation = Validator::make($request->all(),[
//            'groupSession_id' => 'required|exists:group_sessions,id',
//            'group_id' => 'required|exists:groups,id',
//            'name' => 'required',
//            'from' => 'required',
//            'to' => 'required',
//            'link' => 'required',
//        ]);
//
//        if($validation->fails()){
//            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
//        }
//
//        $oldGroupId = $this->groupSession_model::where('id', $request->groupSession_id)->value('group_id');
//        $requestGroupId = $request->group_id;
//
//        if ($oldGroupId == $requestGroupId) {
//            dd("equal");
//        }
//        dd("not equal");
//
//
//        $studentGroupsCount = $this->studentGroup_model::where('group_id', $request->group_id)->get();
//        foreach ($studentGroupsCount as $studentGroupCount) {
//            $studentGroupCount->update([
//                'count' => $studentGroupCount->count - 1
//            ]);
//        }
//
//        $groupSession = $this->groupSession_model::find($request->groupSession_id);
//
//        if ($groupSession) {
//            $groupSession->update([
//                'group_id' => $request->group_id,
//                'name' => $request->name,
//                'from' => $request->from,
//                'to' => $request->to,
//                'link' => $request->link,
//            ]);
//            return $this->ApiResponse(200,'Group session was updated');
//        }
//        return $this->ApiResponse(422, 'This Group session is not found');
    }

    public function deleteGroupSession($request)
    {
        $validation = Validator::make($request->all(),[
            'groupSession_id' => 'required|exists:group_sessions,id'
        ]);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $groupSession = $this->groupSession_model::find($request->groupSession_id);

        if($groupSession){
            $groupSession->delete();
            return $this->ApiResponse(200, 'Group session was deleted');
        }
        return $this->ApiResponse(422, 'This Group session is not found');
    }
}
