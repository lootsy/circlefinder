<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\UsersAdmins;
use Illuminate\Support\Facades\Hash;

/**
 * @group roles
 */
class RolesTest extends TestCase
{
    use DatabaseMigrations;
    use UsersAdmins;

    public function test_guest_cannot_acess_roles()
    {
        $response = $this->get(route('admin.roles.index'));
        $response->assertStatus(302);
        $response->assertRedirect('/admin/login');

        $response = $this->get(route('admin.roles.show', ['id' => 5]));
        $response->assertStatus(302);
        $response->assertRedirect('/admin/login');

        $response = $this->get(route('admin.roles.create'));
        $response->assertStatus(302);
        $response->assertRedirect('/admin/login');
        
        $response = $this->get(route('admin.roles.edit', ['id' => 5]));
        $response->assertStatus(302);
        $response->assertRedirect('/admin/login');

        $response = $this->post(route('admin.roles.store'));
        $response->assertStatus(302);
        $response->assertRedirect('/admin/login');

        $response = $this->put(route('admin.roles.update', ['id' => 5]));
        $response->assertStatus(302);
        $response->assertRedirect('/admin/login');

        $response = $this->delete(route('admin.roles.destroy', ['id' => 5]));
        $response->assertStatus(302);
        $response->assertRedirect('/admin/login');
    }

    public function test_get_roles_index()
    {
        $admin = $this->fetchAdmin();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.roles.index'));
        
        $response->assertStatus(200);
    }


    public function test_create_role()
    {
        $admin = $this->fetchAdmin();

        $response = $this->actingAs($admin, 'admin')
                         ->get(route('admin.roles.create'));

        $response->assertStatus(200);
    }

    public function test_show_role()
    {
        $admin = $this->fetchAdmin();
        $role = $this->fetchRole();

        $response = $this->actingAs($admin, 'admin')
                         ->get(route('admin.roles.show', ['id' => 5]));

        $response->assertStatus(404);

        $response = $this->actingAs($admin, 'admin')
                         ->get(route('admin.roles.show', ['id' => $role->id]));

        $response->assertStatus(200);
    }

    public function test_edit_role()
    {
        $admin = $this->fetchAdmin();
        $role = $this->fetchRole();

        $response = $this->actingAs($admin, 'admin')
                         ->get(route('admin.roles.edit', ['id' => 5]));

        $response->assertStatus(404);

        $response = $this->actingAs($admin, 'admin')
                         ->get(route('admin.roles.edit', ['id' => $role->id]));

        $response->assertStatus(200);
    }

    public function test_store_role()
    {
        $admin = $this->fetchAdmin();
        $faker = $this->fetchFaker();

        $new_name = $faker->domainWord;
        $new_title = $faker->jobTitle;

        $response = $this->actingAs($admin, 'admin')
                         ->post(route('admin.roles.store'), [
            'name' => $new_name,
            'title' => $new_title
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $role = \App\Role::where('name', $new_name)->get()->first();

        $this->assertEquals($role->title, $new_title);       
    }


    public function test_update_role()
    {
        $admin = $this->fetchAdmin();
        $role = $this->fetchRole();
        $faker = $this->fetchFaker();

        $new_name = $faker->domainWord;
        $new_title = $faker->jobTitle;

        $response = $this->actingAs($admin, 'admin')
                         ->put(route('admin.roles.update', ['id' => $role->id]), [
            'name' => $new_name,
            'title' => $new_title
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $role = $this->fetchRole($role->id);

        $this->assertEquals($role->name, $new_name);           
        $this->assertEquals($role->title, $new_title);
    }
}
