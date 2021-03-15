<?php
namespace App\Http\Interfaces;

interface TeacherInterface{
    public function allTeachers();
    public function addTeacher($request);
    public function specificTeacher($request);
    public function updateTeacher($request);
    public function deleteTeacher($request);
}
