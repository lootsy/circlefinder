<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\UsersAdmins;

/**
 * @group memberships
 */
class MembershipsTest extends TestCase
{
    use DatabaseMigrations;
    use UsersAdmins;

    public function test_guest_cannot_access_membership()
    {
        $response = $this->get(route('circles.membership.edit', ['circle_uuid' => '1234']));
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_user_cannot_access_foreign_membership()
    {
        
    }

    public function test_user_can_access_own_membership()
    {
        
    }

    public function test_user_can_update_own_membership()
    {
        
    }
}
