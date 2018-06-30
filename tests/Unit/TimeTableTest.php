<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\UsersAdmins;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;

class TimeTableTest extends TestCase
{
    use DatabaseMigrations;
    use UsersAdmins;

    private function fetchMembership($user)
    {
        $faker = $this->fetchFaker();
        $membership = new \App\Membership;
        
        $data = [
            'type' => 'any',
            'begin' => $faker->date
        ];

        $membership->fill($data);
        
        $membership->circle_id = 0;
        $membership->user_id = $user->id;
        
        $membership->save();

        return $membership;
    }

    public function testUpdateOrCreateTimeSlot()
    {
        $user = $this->fetchUser();
        $faker = $this->fetchFaker();

        $membership = $this->fetchMembership($user);

        $this->assertTrue(is_null($membership->timeSlot));

        $timeTable = \App\TimeTable::updateOrCreateForMemebership($membership, [
            'monday' => [1,2,3],
            'tuesday' => 0,
            'wednesday' => [5, 6, 7, 8, 9, 10],
            'thursday' => 0,
            'friday' => 0,
            'saturday' => 0,
            'sunday' => [],
        ]);

        $membership = $membership->refresh();

        $this->assertFalse(is_null($membership->timeSlot));
    }
}
