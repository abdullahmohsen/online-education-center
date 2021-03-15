<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\ComplaintInterface;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    protected $complaint_interface;

    public function __construct(ComplaintInterface $complaintInterface)
    {
        $this->complaint_interface = $complaintInterface;
    }

    public function index()
    {
        return $this->complaint_interface->allComplaints();
    }

    public function show(Request $request)
    {
        return $this->complaint_interface->getComplaint($request);
    }

    public function delete(Request $request)
    {
        return $this->complaint_interface->deleteComplaint($request);
    }
}
