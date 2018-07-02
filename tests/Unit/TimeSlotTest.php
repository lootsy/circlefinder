<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\UsersAdmins;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * @group timeslot
 */
class TimeSlotTest extends TestCase
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

    public function testCreateNewTimeSlot()
    {
        $user = $this->fetchUser();
        $faker = $this->fetchFaker();

        $data = [
            'type' => 'any',
            'begin' => $faker->date
        ];

        $membership = $this->fetchMembership($data, $user);

        $timeslot = new \App\TimeSlot;

        $timeslot->monday = [5, 6, 7];
        $timeslot->tuesday = 0;
        $timeslot->wednesday = 0;
        $timeslot->thursday = 0;
        $timeslot->friday = 0;
        $timeslot->saturday = 0;
        $timeslot->sunday = 0;

        $timeslot->membership_id = $membership->id;

        $timeslot->save();

        $this->assertDatabaseHas('time_slots', [
            'membership_id' => 1
        ]);

        $this->assertEquals($timeslot->monday, $membership->timeSlot->monday);

        $this->assertTrue($timeslot->atTime(6));
        $this->assertFalse($timeslot->atTime(4));
    }
}
