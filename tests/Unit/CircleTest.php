<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\UsersAdmins;
use Illuminate\Foundation\Testing\DatabaseMigrations;


/**
 * @group circle
 */
class CircleTest extends TestCase
{
    use DatabaseMigrations;
    use UsersAdmins;

    private function fetchMembershipData()
    {
        $faker = $this->fetchFaker();

        return [
            'type' => 'any',
            'begin' => $faker->date
        ];
    }

    public function test_user_can_create_circle()
    {
        $user = $this->fetchUser();
        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        $this->assertDatabaseHas('circles', [
            'user_id' => $user->id
        ]);

        $this->assertEquals($user->id, $circle->user->id);

        $this->assertEquals(36, strlen($circle->uuid));
    }

    public function test_user_owns_circle()
    {
        $user = $this->fetchUser();
        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        $this->assertTrue($circle->ownedBy($user));
    }

    public function test_circle_can_create_memberships()
    {
        $user = $this->fetchUser();
        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        $membership = $circle->join($this->fetchMembershipData(), $user);

        $this->assertDatabaseHas('memberships', [
            'circle_id' => $circle->id
        ]);

        $this->assertEquals(1, $circle->memberships()->count());
        $this->assertEquals($membership->id, $circle->memberships()->first()->id);
        $this->assertEquals($user->id, $circle->users()->first()->id);
    }

    public function test_user_can_delete_circle()
    {
        $user = $this->fetchUser();
        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        $this->assertTrue($circle->deletable());

        $membership = $circle->join($this->fetchMembershipData(), $user);

        $this->assertDatabaseHas('memberships', [
            'circle_id' => $circle->id
        ]);

        $this->assertEquals($circle->id, $membership->circle_id);

        $this->assertFalse($circle->deletable());

        $circle->delete();

        $this->assertDatabaseMissing('memberships', [
            'circle_id' => $circle->id
        ]);
    }

    public function test_user_can_join_circle()
    {
        $user = $this->fetchUser();
        $user2 = $this->fetchUser();
        $user3 = $this->fetchUser();
        $user4 = $this->fetchUser();

        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        $circle->join($this->fetchMembershipData(), $user2);
        $circle->join($this->fetchMembershipData(), $user3);
        $circle->join($this->fetchMembershipData(), $user4);

        $this->assertDatabaseHas('memberships', [
            'circle_id' => $circle->id,
            'user_id' => $user2->id
        ]);

        $this->assertEquals(3, $circle->memberships()->count());
        $this->assertEquals(3, $circle->users()->count());
    }

    public function test_user_can_join_circle_with_defaults()
    {
        $user = $this->fetchUser();
        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        $circle->languages()->attach($this->fetchLanguage());
        $circle->languages()->attach($this->fetchLanguage());

        $membership = $circle->joinWithDefaults($user);

        $this->assertEquals($circle->type, $membership->type);
        $this->assertEquals($circle->begin, $membership->begin);
        $this->assertEquals($circle->languages[0]->code, $membership->languages[0]->code);
        $this->assertEquals($circle->languages[1]->code, $membership->languages[1]->code);
    }

    public function test_one_user_cannot_join_twice()
    {
        $user = $this->fetchUser();

        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        $this->assertFalse(is_null($circle->join($this->fetchMembershipData(), $user)));

        $this->assertFalse($circle->joinable($user));

        $this->assertTrue(is_null($circle->join($this->fetchMembershipData(), $user)));
    }

    public function test_circle_gets_new_owner_if_user_deleted()
    {
        $user = $this->fetchUser();
        $user2 = $this->fetchUser();

        $faker = $this->fetchFaker();

        $circle = $this->fetchCircle($user);
        $circle = $this->fetchCircle($user);
        $circle = $this->fetchCircle($user);

        $circle->join($this->fetchMembershipData(), $user);
        $circle->join($this->fetchMembershipData(), $user2);

        $this->assertEquals(2, $circle->users()->count());

        $user->delete();

        $circle = $circle->fresh();

        $this->assertEquals(1, $circle->users()->count());
        $this->assertEquals($user2->id, $circle->user_id);

        $user2->delete();

        $this->assertDatabaseMissing('memberships', [
            'circle_id' => $circle->id
        ]);

        $this->assertDatabaseMissing('circles', [
            'id' => $circle->id
        ]);
    }

    public function test_circle_gets_new_owner_if_user_not_member_and_deleted()
    {
        $user = $this->fetchUser();
        $user2 = $this->fetchUser();
        $user3 = $this->fetchUser();
        
        $faker = $this->fetchFaker();

        $circle = $this->fetchCircle($user);
        $circle = $this->fetchCircle($user);
        $circle = $this->fetchCircle($user);

        $circle->join($this->fetchMembershipData(), $user2);
        $circle->join($this->fetchMembershipData(), $user3);

        $this->assertEquals(2, $circle->users()->count());

        $user->delete();

        $circle = $circle->fresh();

        $this->assertEquals(2, $circle->users()->count());
        $this->assertEquals($circle->user_id, $user2->id);       
    }

    public function test_delete_empty_circle_when_user_deleted()
    {
        $user = $this->fetchUser();

        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        $user->delete();

        $this->assertDatabaseMissing('circles', [
            'id' => $circle->id
        ]);
    }

    public function test_one_user_can_leave_circle()
    {
        $user = $this->fetchUser();
        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        $membership = $circle->join($this->fetchMembershipData(), $user);
        
        $this->assertDatabaseHas('memberships', [
            'circle_id' => $circle->id,
            'user_id' => $user->id
        ]);

        $circle->leave($user);

        $this->assertDatabaseMissing('memberships', [
            'circle_id' => $circle->id,
            'user_id' => $user->id
        ]);
    }

    public function test_one_user_cannot_join_if_full()
    {
        $user = $this->fetchUser();
        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        for($i = 0; $i < $circle->limit + 1; $i++)
        {
            $member = $this->fetchUser();
            
            $membership = $circle->join($this->fetchMembershipData(), $member);

            if($i >= $circle->limit)
            {
                $this->assertFalse($circle->joinable());

                $this->assertDatabaseMissing('memberships', [
                    'circle_id' => $circle->id,
                    'user_id' => $member->id
                ]);
            }
            else
            {
                $this->assertDatabaseHas('memberships', [
                    'circle_id' => $circle->id,
                    'user_id' => $member->id
                ]);
            }
        }
    }

    public function test_one_user_cannot_join_if_completed()
    {
        $user = $this->fetchUser();
        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        $member = $this->fetchUser();    
        $membership = $circle->join($this->fetchMembershipData(), $member);

        $this->assertDatabaseHas('memberships', [
            'circle_id' => $circle->id,
            'user_id' => $member->id
        ]);

        $circle->complete();

        $member = $this->fetchUser();    
        $membership = $circle->join($this->fetchMembershipData(), $member);

        $this->assertTrue(is_null($membership));
        $this->assertFalse($circle->joinable());

        $this->assertDatabaseMissing('memberships', [
            'circle_id' => $circle->id,
            'user_id' => $member->id
        ]);

        $circle->uncomplete();

        $member = $this->fetchUser();    
        $membership = $circle->join($this->fetchMembershipData(), $member);

        $this->assertFalse(is_null($membership));
        $this->assertTrue($circle->joinable());

        $this->assertDatabaseHas('memberships', [
            'circle_id' => $circle->id,
            'user_id' => $member->id
        ]);
    }

    public function test_validation_rules()
    {
        $rules = \App\Circle::validationRules();
        $rules2 = \App\Circle::validationRules(['type']);

        $this->assertTrue(count($rules) > 0);

        $this->assertTrue(key_exists('type', $rules));
        
        $this->assertFalse(key_exists('type', $rules2));
    }

    public function test_get_link_to_circle()
    {
        $user = $this->fetchUser();
        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        $link = sprintf('<a href="%s">%s</a>', route('circles.show', ['uuid' => $circle->uuid]), (string) $circle);
        $this->assertEquals($link, $circle->link());

        $link = sprintf('<a href="%s">%s</a>', route('circles.show', ['uuid' => $circle->uuid]), 'Test');
        $this->assertEquals($link, $circle->link('Test'));

        $link = sprintf('<a href="%s" class="demo">%s</a>', route('circles.show', ['uuid' => $circle->uuid]), 'Test');
        $this->assertEquals($link, $circle->link('Test', 'demo'));
    }
}
