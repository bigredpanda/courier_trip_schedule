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

        $currentDate = new \DateTime('now');

        $couriersCount = 40;
        for($i = 0; $i < $couriersCount; $i++) {
            $courier = $this->getReference('courier' . $i);
            $startDate = new \DateTime('2015-06-01');
            while ($startDate <= $currentDate) {
                $region = $this->getReference('region' . rand(0, 9));
                $schedule = new Schedule();
                $schedule->setCourier($courier);
                /** @var Region $region */
                $schedule->setRegion($region);
                $schedule->setDepartureDate(clone $startDate);
                $arrivalDate = $startDate->add(new \DateInterval('P' . $region->getTripDuration() . 'D'));
                $schedule->setArrivalDate(clone $arrivalDate);
                $startDate = $startDate->add(new \DateInterval('P' . rand(1, 100) . 'D'));
                $om->persist($schedule);
            }
        }
        $om->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}