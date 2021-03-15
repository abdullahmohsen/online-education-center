<?php
namespace App\Http\Controllers;

use App\Http\Interfaces\TeacherInterface;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    protected $teacher_interface;

    public function __construct(TeacherInterface $teacherInterface)
    {
        $this->teacher_interface = $teacherInterface;
    }

    public function index()
    {
        return $this->teacher_interface->allTeachers();
    }

    public function create(Request $request)
    {
        return $this->teacher_interface->addTeacher($request);
    }

    public function show(Request $request)
    {
        return $this->teacher_interface->specificTeacher($request);
    }

    public function update(Request $request)
    {
        return $this->teacher_interface->updateTeacher($request);
    }

    public function delete(Request $request)
    {
        return $this->teacher_interface->deleteTeacher($request);
    }
}
