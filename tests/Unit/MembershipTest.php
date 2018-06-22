<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\UsersAdmins;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * @group membership
 */
class MembershipTest extends TestCase
{
    use DatabaseMigrations;
    use UsersAdmins;

    private function fetchMembership($data, $user)
    {
        $membership = new \App\Membership;
        
        $membership->fill($data);
        
        $membership->circle_id = 0;
        $membership->user_id = $user->id;
        
        $membership->save();

        return $membership;
    }

    public function test_create_new_membership()
    {
        $user = $this->fetchUser();
        $faker = $this->fetchFaker();

        $data = [
            'type' => 'any',
            'begin' => $faker->date
        ];

        $membership = $this->fetchMembership($data, $user);
        $membership = $this->fetchMembership($data, $user);
        $membership = $this->fetchMembership($data, $user);
        
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
            'type' => 'any',
            'begin' => $faker->date
        ];

        $membership = $this->fetchMembership($data, $user);

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
        $lang2 = factory(\App\Language::class)->create();

        $data = [
            'type' => 'any',
            'begin' => $faker->date
        ];

        $membership = $this->fetchMembership($data, $user);

        $membership->languages()->attach($lang);
        $membership->languages()->attach($lang2);

        $this->assertDatabaseHas('language_membership', [
            'membership_id' => $membership->id
        ]);

        $this->assertDatabaseHas('language_membership', [
            'language_id' => $lang->id
        ]);

        $this->assertDatabaseHas('language_membership', [
            'language_id' => $lang2->id
        ]);

        $membership->languages()->detach();

        $this->assertDatabaseMissing('language_membership', [
            'membership_id' => $membership->id
        ]);
    }
}
