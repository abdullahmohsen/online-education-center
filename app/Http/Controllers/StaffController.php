<?php
namespace App\Http\Controllers;

use App\Http\Interfaces\StaffInterface;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    protected $staff_interface;

    public function __construct(StaffInterface $staffInterface)
    {
        $this->staff_interface = $staffInterface;
    }

    public function index()
    {
        return $this->staff_interface->allStaff();
    }

    public function create(Request $request)
    {
        return $this->staff_interface->addStaff($request);
    }

    public function update(Request $request)
    {
        return $this->staff_interface->updateStaff($request);
    }

    public function delete(Request $request)
    {
        return $this->staff_interface->deleteStaff($request);
    }
}
