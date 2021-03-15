<?php
namespace App\Http\Interfaces;

interface StudentInterface{
    public function allStudents();
    public function addStudent($request);
    public function specificStudent($request);
    public function updateStudent($request);
    public function deleteStudent($request);
}
