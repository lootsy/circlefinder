<?php

namespace App;

class TimeTable
{
    public function getTimeList()
    {
        return range(6, 22);
    }

    public function getDayList($short = false)
    {
        if ($short == true) {
            return [
                'mo',
                'tu',
                'we',
                'th',
                'fr',
                'sa',
                'su',
            ];
        }

        return [
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday',
        ];
    }

    public static function updateOrCreateForMemebership($membership, $request_data)
    {
        $timeTable = new \App\TimeTable;

        $timeSlot = $membership->timeSlot;

        if (is_null($timeSlot)) {
            $timeSlot = new \App\TimeSlot;
            $timeSlot->membership_id = $membership->id;
        }

        foreach ($timeTable->getDayList() as $day) {
            $timeSlot->$day = $request_data[$day];
        }

        $timeSlot->save();

        return $timeTable;
    }
}
