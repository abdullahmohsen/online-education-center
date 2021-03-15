<?php
namespace App\Http\Controllers;

use App\Http\Interfaces\AuthInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $auth_interface;

    public function __construct(AuthInterface $authInterface)
    {
        $this->auth_interface = $authInterface;
    }

    public function login()
    {
        return $this->auth_interface->HandleLogin();
    }

    public function logout()
    {
        return $this->auth_interface->HandleLogout();
    }

    public function update(Request $request)
    {
        return $this->auth_interface->updatePassword($request);
    }
}
