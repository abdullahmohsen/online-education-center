<?php
namespace App\Http\Controllers;

use App\Http\Interfaces\GroupSessionInterface;
use Illuminate\Http\Request;

class GroupSessionController extends Controller
{
    protected $groupSession_interface;

    public function __construct(GroupSessionInterface $groupSessionInterface)
    {
        $this->groupSession_interface = $groupSessionInterface;
    }

    public function index()
    {
        return $this->groupSession_interface->allGroupSessions();
    }

    public function create(Request $request)
    {
        return $this->groupSession_interface->addGroupSession($request);
    }

    public function show(Request $request)
    {
        return $this->groupSession_interface->specificGroupSession($request);
    }

    public function update(Request $request)
    {
        return $this->groupSession_interface->updateGroupSession($request);
    }

    public function delete(Request $request)
    {
        return $this->groupSession_interface->deleteGroupSession($request);
    }
}
