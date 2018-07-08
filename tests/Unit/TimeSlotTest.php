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

    public function testTimezoneConversion()
    {
        $user = $this->fetchUser();
        $faker = $this->fetchFaker();

        $data = [
            'type' => 'any',
            'begin' => $faker->date
        ];

        $membership = $this->fetchMembership($data, $user);

        $timeslot = new \App\TimeSlot;

        $timeslot->setTimeOffset(3);

        $timeslot->monday = [10, 12, 14];
        $timeslot->tuesday = [4, 5, 6];
        $timeslot->wednesday = 0;
        $timeslot->thursday = 0;
        $timeslot->friday = 0;
        $timeslot->saturday = 0;
        $timeslot->sunday = 0;

        $timeslot->membership_id = $membership->id;
        $timeslot->save();

        $this->assertDatabaseHas('time_slots', [
            'membership_id' => 1,
            'monday' => json_encode([7, 9, 11]),
            'tuesday' => json_encode([1, 2, 3]),
            'wednesday' => 0
        ]);

        $timeslot = \App\TimeSlot::where('membership_id', 1)->first();

        $this->assertEquals($timeslot->monday, [7, 9, 11]);

        $timeslot->setTimeOffset(3);

        $this->assertEquals($timeslot->monday, [10, 12, 14]);
        $this->assertEquals($timeslot->wednesday, 0);
    }
}
