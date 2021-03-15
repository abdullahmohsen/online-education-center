<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    private $email;

    public function testLoginWithAdminAccount()
    {
        $data = [
            'email' => 'admin@gmail.com',
            'password' => '12345678'
        ];

        $user = $this->json('POST', '/api/auth/login', $data);
        $user->assertJson(['data' => ['role' => 'Admin']]);
        $user->assertStatus(200);

        $this->email = $user['data']['email'];
    }

//    public function testCredentials()
//    {
//        $data = [
//            'email' => 'adm@gmail.com',
//            'password' => '12345678'
//        ];

//        $user = $this->json('POST', '/api/auth/login', $data);
//        $user->assertJson(['role' => 'Admin']);
//        $user->assertStatus(200);

//        $this->email = $user['data']['email'];
//    }

}
