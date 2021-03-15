<?php
namespace App\Http\Interfaces;

interface AuthInterface{
    public function HandleLogin();
    public function HandleLogout();
    public function respondWithToken($token);
}
