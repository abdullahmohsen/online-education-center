<?php
namespace App\Http\Controllers;

use App\Http\Interfaces\GroupInterface;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    protected $group_interface;

    public function __construct(GroupInterface $groupInterface)
    {
        $this->group_interface = $groupInterface;
        $this->middleware('jwt.verify',[
            'except' => [
                'index', 'show'
            ]
        ]);
    }

    public function index()
    {
        return $this->group_interface->allGroups();
    }

    public function create(Request $request)
    {
        return $this->group_interface->addGroup($request);
    }

    public function show(Request $request)
    {
        return $this->group_interface->specificGroup($request);
    }

    public function update(Request $request)
    {
        return $this->group_interface->updateGroup($request);
    }

    public function delete(Request $request)
    {
        return $this->group_interface->deleteGroup($request);
    }
}
