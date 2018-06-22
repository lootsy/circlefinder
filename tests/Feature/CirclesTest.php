<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\UsersAdmins;


/**
 * @group circles
 */
class CirclesTest extends TestCase
{
    use DatabaseMigrations;
    use UsersAdmins;

    public function test_guest_cannot_access_circle()
    {
        $response = $this->get(route('circles.index'));
        $response->assertStatus(302);
        $response->assertRedirect('/login');

        $response = $this->get(route('circles.show', ['circle_uuid' => '1234']));
        $response->assertStatus(302);
        $response->assertRedirect('/login');

        $response = $this->get(route('circles.edit', ['circle_uuid' => '1234']));
        $response->assertStatus(302);
        $response->assertRedirect('/login');

        $response = $this->put(route('circles.update', ['circle_uuid' => '1234']));
        $response->assertStatus(302);
        $response->assertRedirect('/login');

        $response = $this->delete(route('circles.destroy', ['circle_uuid' => '1234']));
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_can_list_circles()
    {
        $user = $this->fetchUser();
        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        $response = $this->actingAs($user)->get(route('circles.index'));

        $response->assertStatus(200);
    }

    public function test_can_show_circle()
    {
        $user = $this->fetchUser();
        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        $response = $this->actingAs($user)->get(route('circles.show', ['uuid' => $circle->uuid]));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get(route('circles.show', ['uuid' => $faker->uuid]));
        $response->assertStatus(404);
    }

    public function test_some_user_cannot_edit_circle()
    {
        $user = $this->fetchUser();
        $user2 = $this->fetchUser();
        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        $response = $this->actingAs($user2)->get(route('circles.edit', ['uuid' => $circle->uuid]));
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }

    public function test_owner_can_edit_circle()
    {
        $user = $this->fetchUser();
        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        $response = $this->actingAs($user)->get(route('circles.edit', ['uuid' => $circle->uuid]));
        $response->assertStatus(200);
    }
}
