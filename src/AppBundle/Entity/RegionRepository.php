<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * RegionRepository
 */
class RegionRepository extends EntityRepository
{
    /**
     * Получаем $length регионов начиная с $start
     * @param $start
     * @param $length
     * @return array
     */
    public function getRegions($start, $length) {
        $query = $this->createQueryBuilder('r')
            ->select(['r.id, r.name, r.tripDuration'])
            ->setFirstResult($start)
            ->setMaxResults($length)
            ->orderBy('r.name', 'asc');

        return $query->getQuery()->getArrayResult();
    }

    /**
     * Получаем количество всех регионов
     * @return mixed
     */
    public function getRegionsCount() {
        $query = $this->createQueryBuilder('r')
            ->select('COUNT(r)')
            ->getQuery();

        return $query->getSingleScalarResult();
    }
}
