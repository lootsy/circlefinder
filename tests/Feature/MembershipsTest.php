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

    public function test_user_can_edit_own_membership()
    {
        $user = $this->fetchUser();
        $user2 = $this->fetchUser();
        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        $circle->joinWithDefaults($user2);

        $response = $this->actingAs($user2)->get(route('circles.membership.edit', ['uuid' => $circle->uuid]));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get(route('circles.membership.edit', ['uuid' => $circle->uuid]));
        $response->assertStatus(404);
    }

    public function test_user_can_update_own_membership()
    {
        $user = $this->fetchUser();
        $user2 = $this->fetchUser();
        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        $circle->joinWithDefaults($user2);

        $response = $this->actingAs($user2)->put(route('circles.membership.update', ['uuid' => $circle->uuid]), [
            'type' => 'any',
            'begin' => today()
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
    }
}
