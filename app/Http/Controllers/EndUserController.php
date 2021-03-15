<?php
namespace App\Http\Controllers;

use App\Http\Interfaces\EndUserInterface;

class EndUserController extends Controller
{
    protected $endUser_interface;

    public function __construct(EndUserInterface $endUserInterface)
    {
        $this->endUser_interface = $endUserInterface;
    }

    public function index()
    {
        return $this->endUser_interface->userGroups();
    }
}
