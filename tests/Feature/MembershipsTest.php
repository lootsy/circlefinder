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
        $membership = $user->memberships()->create($data);
        $membership = $user->memberships()->create($data);
        
        $this->assertDatabaseHas('memberships', [
            'user_id' => $user->id
        ]);

        $this->assertEquals(3, count($user->memberships));

        $this->assertEquals($user->id, $membership->user->id);
        $this->assertEquals($data['type'], $membership->type);
        $this->assertEquals($data['begin'], $membership->begin);
    }

    public function test_removing_user_removes_memberships()
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

        $user->delete();

        $this->assertDatabaseMissing('memberships', [
            'user_id' => $user->id
        ]);
    }

    public function test_attach_detach_language_to_membership()
    {
        $user = $this->fetchUser();
        $faker = $this->fetchFaker();

        $lang = factory(\App\Language::class)->create();

        $data = [
            'type' => 'both',
            'begin' => $faker->date
        ];

        $membership = $user->memberships()->create($data);

        $membership->languages()->attach($lang);

        $this->assertDatabaseHas('language_membership', [
            'membership_id' => $membership->id
        ]);

        $membership->languages()->detach();

        $this->assertDatabaseMissing('language_membership', [
            'membership_id' => $membership->id
        ]);
    }

}
