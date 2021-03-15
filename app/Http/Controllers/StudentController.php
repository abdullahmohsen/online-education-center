<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Interfaces\StudentInterface;

class StudentController extends Controller
{
    protected $student_interface;

    public function __construct(StudentInterface $studentInterface)
    {
        $this->student_interface = $studentInterface;
    }

    public function index()
    {
        return $this->student_interface->allStudents();
    }

    public function create(Request $request)
    {
        return $this->student_interface->addStudent($request);
    }

    public function show(Request $request)
    {
        return $this->student_interface->specificStudent($request);
    }

    public function update(Request $request)
    {
        return $this->student_interface->updateStudent($request);
    }

    public function delete(Request $request)
    {
        return $this->student_interface->deleteStudent($request);
    }
}
