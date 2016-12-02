<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Courier;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class LoadCouriers extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $om)
    {
        $faker = Factory::create('ru_RU');
        $couriers = [];
        for ($i = 0; $i < 40; $i++) {
            $isMale = rand(0, 1);
            $courier = new Courier();
            if($isMale) {
                $courier->setFirstName($faker->firstNameMale);
                $courier->setLastName($faker->lastName);
                $courier->setMiddleName($faker->middleNameMale);
            } else {
                $courier->setFirstName($faker->firstNameFemale);
                $courier->setLastName($faker->lastName . 'a');
                $courier->setMiddleName($faker->middleNameFemale);
            }
            $om->persist($courier);
            $couriers[] = $courier;
        }
        $om->flush();
        for ($i = 0; $i < sizeof($couriers); $i++) {
            $this->addReference('courier' . $i, $couriers[$i]);
        }
    }

    public function getOrder()
    {
        return 1;
    }
}