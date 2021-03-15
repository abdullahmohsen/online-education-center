<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\ComplaintInterface;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Complaint;
use Illuminate\Support\Facades\Validator;

class ComplaintRepository implements ComplaintInterface
{
    use ApiResponseTrait;

    private $complaint_model;

    public function __construct(Complaint $complaint)
    {
        $this->complaint_model = $complaint;
    }


    public function allComplaints()
    {
        $data = $this->complaint_model::with('sender:id,name,email')->get();
        return $this->ApiResponse(200, 'Done', null, $data);
    }

    public function getComplaint($request)
    {
        $validation = Validator::make($request->all(),[
            'complaint_id' => 'required|exists:complaints,id'
        ]);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $data = $this->complaint_model::where('id', $request->complaint_id)->with('sender:id,name,email')->get();
        if ($data)
            return $this->ApiResponse(200, 'Done', null, $data);
        return $this->ApiResponse(422, 'This Complaint is not found');
    }

    public function deleteComplaint($request)
    {
        $validation = Validator::make($request->all(), [
            'complaint_id' => 'required|exists:complaints,id'
        ]);

        if ($validation->fails()) {
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $complaint = $this->complaint_model::find($request->complaint_id);

        if ($complaint){
            $complaint->delete();
            return $this->ApiResponse(200, 'Complaint was deleted');
        }
        return $this->ApiResponse(422, 'This Complaint is not found');
    }
}
