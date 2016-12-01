<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CourierRepository
 */
class CourierRepository extends EntityRepository
{

    /**
     * Получаем $length курьеров начиная с $start
     * @param $start
     * @param $length
     * @return array
     */
    public function getCouriers($start, $length) {
        $query = $this->createQueryBuilder('c')
            ->select(['c.id, c.firstName, c.middleName, c.lastName']) //TODO ПЕРЕПИСАТЬ С ПОМОЩЬЮ CONCAT
            ->setFirstResult($start)
            ->setMaxResults($length)
            ->orderBy('c.lastName', 'asc');

        return $query->getQuery()->getArrayResult();
    }

    /**
     * Получаем количество всех курьеров
     * @return mixed
     */
    public function getCouriersCount() {
        $query = $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->getQuery();

         return $query->getSingleScalarResult();
    }
}
