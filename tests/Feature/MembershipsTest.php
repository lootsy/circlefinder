<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\UsersAdmins;
use Illuminate\Support\Facades\Artisan;

/**
 * @group memberships
 */
class MembershipsTest extends TestCase
{
    use DatabaseMigrations;
    use UsersAdmins;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => 'LanguagesTableSeeder', '--env' => 'testing']);
    }

    public function testGuestCannotAccessMembership()
    {
        $response = $this->get(route('circles.membership.edit', ['circle_uuid' => '1234']));
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testUserCanEditOwnMembership()
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

    public function testUserCanUpdateOwnMembership()
    {
        $user = $this->fetchUser();
        $user2 = $this->fetchUser();
        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        $membership = $circle->joinWithDefaults($user2);

        $text = $faker->text;

        $response = $this->actingAs($user2)->put(route('circles.membership.update', ['uuid' => $circle->uuid]), [
            'type' => 'any',
            'begin' => today(),
            'languages' => [
                '0' => \App\Language::find(1)->code,
                '1' => \App\Language::find(2)->code,
                '2' => \App\Language::find(3)->code
            ],
            'comment' => $text
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $membership = $membership->refresh();

        $this->assertEquals(3, count($membership->languages));
        $this->assertEquals($text, $membership->comment);
    }
}
