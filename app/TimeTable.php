<?php

namespace App;

class TimeTable
{
    private $timeSlots = array();
    private $memberships = null;
    private $checks = array();

    public function getTimeList()
    {
        return range(6, 22);
    }

    public static function getDayList($short = false)
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

    public function timeSlot($membership = null)
    {
        return count($this->timeSlots) ? $this->timeSlots[0] : null;
    }

    public function timeSlots()
    {
        return $this->timeSlots;
    }

    public function memberships()
    {
        return $this->memberships;
    }

    private function timeSlotForMembership($membership)
    {
        $timeSlot = $membership->timeSlot;

        if (is_null($timeSlot)) {
            $timeSlot = new \App\TimeSlot;
            $timeSlot->membership_id = $membership->id;

            foreach ($this->getDayList() as $day) {
                $timeSlot->$day = 0;
            }

            $timeSlot->save();
        }

        return $timeSlot;
    }

    public static function findForMembership($membership)
    {
        $timeTable = new \App\TimeTable;

        $timeTable->timeSlots[] = $timeTable->timeSlotForMembership($membership);

        return $timeTable;
    }

    public static function updateOrCreateForMembership($membership, $request_data)
    {
        $timeTable = new \App\TimeTable;

        $timeSlot = $membership->timeSlot;

        if (is_null($timeSlot)) {
            $timeSlot = new \App\TimeSlot;
            $timeSlot->membership_id = $membership->id;
        }

        foreach ($timeTable->getDayList() as $day) {
            if (key_exists($day, $request_data)) {
                $timeSlot->$day = $request_data[$day];
            } else {
                $timeSlot->$day = 0;
            }
        }

        $timeSlot->save();

        $timeTable->timeSlots[] = $timeSlot;

        $timeTable->memberships[] = $membership;

        return $timeTable;
    }

    public static function forCircle($circle)
    {
        $timeTable = new \App\TimeTable;

        $memberships = $circle->memberships;

        if (count($memberships) < 1) {
            return null;
        }

        $timeTable->memberships = $memberships;

        foreach ($memberships as $membership) {
            $timeTable->timeSlots[] = $timeTable->timeSlotForMembership($membership);
        }

        $timeTable->generateCheckCounts();

        return $timeTable;
    }

    public function atTime($time)
    {
        foreach ($this->timeSlots as $timeSlot) {
            if ($timeSlot->atTime($time)) {
                return true;
            }
        }

        return false;
    }

    private function generateCheckCounts()
    {
        foreach ($this->timeSlots as $timeSlot) {
            foreach ($this->getDayList() as $day) {
                if (is_array($timeSlot->$day)) {
                    if (key_exists($day, $this->checks) == false) {
                        $this->checks[$day] = array();
                    }

                    if (is_array($timeSlot->$day)) {
                        foreach ($timeSlot->$day as $time) {
                            if (key_exists($time, $this->checks[$day]) == false) {
                                $this->checks[$day][$time] = 1;
                            } else {
                                $this->checks[$day][$time]++;
                            }
                        }
                    }
                }
            }
        }
    }

    public function checksAt($day, $time)
    {
        if (key_exists($day, $this->checks) && key_exists($time, $this->checks[$day])) {
            return $this->checks[$day][$time];
        } else {
            return 0;
        }
    }
}
