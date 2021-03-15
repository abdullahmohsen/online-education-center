<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class staffTest extends TestCase
{
    public function testGetAllStaffData()
    {
        $allStaff = $this->json('POST', '/api/admin/staff/index');
        $allStaff->assertStatus(200);
    }
}
