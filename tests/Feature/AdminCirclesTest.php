<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\UsersAdmins;


/**
 * @group admin.circles
 */
class AdminCirclesTest extends TestCase
{
    use DatabaseMigrations;
    use UsersAdmins;

    public function test_guest_cannot_access_circles()
    {
        $response = $this->get(route('admin.circles.index'));
        $response->assertStatus(302);
        $response->assertRedirect('/admin/login');

        $response = $this->get(route('admin.circles.show', ['id' => '1234']));
        $response->assertStatus(302);
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_list_circles()
    {
        $admin = $this->fetchAdmin();
        $user = $this->fetchUser();
        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.circles.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_show_circle()
    {
        $admin = $this->fetchAdmin();
        $user = $this->fetchUser();
        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.circles.show', ['id' => $circle->id]));

        $response->assertStatus(200);
    }    
}
