<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Courier;
use AppBundle\Entity\Region;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadRegions extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $om)
    {
        $regionNames = [
            'Санкт-Петербург',
            'Уфа',
            'Нижний Новгород',
            'Владимир',
            'Кострома',
            'Екатеринбург',
            'Ковров',
            'Воронеж',
            'Самара',
            'Астрахань'
        ];
        $regions = [];
        for ($i = 0; $i < sizeof($regionNames); $i++) {
            $tripTime = rand(1, 10);
            $region = new Region();
            $region->setName($regionNames[$i]);
            $region->setTripDuration($tripTime);
            $om->persist($region);
            $regions[] = $region;
        }
        $om->flush();
        $regionsCount = sizeof($regions);
//        $this->addReference('regionsCount', $regionsCount);
        for ($i = 0; $i < $regionsCount; $i++) {
            $this->addReference('region' . $i, $regions[$i]);
        }
    }

    public function getOrder()
    {
        return 1;
    }
}