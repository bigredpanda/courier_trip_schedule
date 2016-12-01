<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Region;
use AppBundle\Entity\Schedule;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSchedule extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $om)
    {
        $endDate = new \DateTime('now');
        $couriersCount = 40;
        $scheduleList = [];
        for($i = 0; $i < $couriersCount; $i++) {
            $courier = $this->getReference('courier' . $i);
            $startDate = new \DateTime('2015-06-01');
            while ($startDate <= $endDate) {
                $region = $this->getReference('region' . rand(0, 9));
                $schedule = new Schedule();
                $schedule->setCourier($courier);
                /** @var Region $region */
                $schedule->setRegion($region);
                $schedule->setDepartureDate(clone $startDate);
                $arrivalDate = $startDate->add(new \DateInterval('P' . $region->getTripDuration() . 'D'));
                $schedule->setArrivalDate(clone $arrivalDate);
                $scheduleList[] = $schedule;
                $startDate = $startDate->add(new \DateInterval('P' . rand(1, 100) . 'D'));
            }
        }
        foreach($scheduleList as $schedule) {
            $om->persist($schedule);
        }
        $om->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}