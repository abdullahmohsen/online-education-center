<?php
namespace App\Http\Interfaces;

interface GroupSessionInterface{
    public function allGroupSessions();
    public function addGroupSession($request);
    public function specificGroupSession($request);
    public function updateGroupSession($request);
    public function deleteGroupSession($request);
}
