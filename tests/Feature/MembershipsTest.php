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

    public function test_create_new_membership_without_circle()
    {
        $user = $this->fetchUser();
        $faker = $this->fetchFaker();

        $data = [
            'type' => 'both',
            'begin' => $faker->date
        ];

        $membership = $user->memberships()->create($data);

        $this->assertDatabaseHas('memberships', [
            'user_id' => $user->id
        ]);

        $this->assertEquals($user->id, $membership->user->id);
        $this->assertEquals($data['type'], $membership->type);
        $this->assertEquals($data['begin'], $membership->begin);
    }

    
}
