<?php

namespace AppBundle\Services;

use DateInterval;
use DatePeriod;

class ScheduleService
{

    public function __construct()
    {
    }

    /**
     * Получаем массив дат, в которые курьер занят
     * @param $scheduleDatesData
     * @return array
     */
    public function getDisableDates($scheduleDatesData)
    {
        $aDisableDates = [];
        foreach ($scheduleDatesData as $data) {
            $aDateRange = new DatePeriod($data['departureDate'], new DateInterval('P1D'), $data['arrivalDate']);
            foreach ($aDateRange as $date) {
                array_push($aDisableDates, $date->format("Y-m-d"));
            }
        }

        return $aDisableDates;
    }
}