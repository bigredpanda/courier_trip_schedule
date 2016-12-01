<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * ScheduleRepository
 *
 */
class ScheduleRepository extends EntityRepository
{
    /**
     * Получаем $length поездок начиная с $start
     * @param int $start
     * @param int $length
     * @param array $filter
     * @param array $columns
     * @param array $order
     * @return array
     */
    public function getTrips($start, $length, $filter, $columns, $order)
    {
        $query = $this->createQueryBuilder('s')
            ->select(["
                s.id, r.name as region, 
                CONCAT(COALESCE(c.lastName,' '),' ',COALESCE(c.firstName,' '),' ', 
                             COALESCE(c.middleName,' ')) AS courierName,
                s.departureDate, 
                s.arrivalDate"])
            ->leftJoin('s.region', 'r')
            ->leftJoin('s.courier', 'c')
            ->setFirstResult($start)
            ->setMaxResults($length);
        $columnName = $columns[$order['column']]['data'];
        if($columnName == 'id' || $columnName == 'departureDate' || $columnName == 'arrivalDate') {
            $columnName = 's.' . $columnName;
        } elseif($columnName == 'region') {
            $columnName = 'r.name';
        } else {
            $columnName = 'c.lastName';
        }
            $query->orderBy($columnName, $order['dir']);
        $this->filter($query, $filter);

        return array_map(function ($item) {
            $item['departureDate'] = $item['departureDate']->format('Y-m-d');
            $item['arrivalDate'] = $item['arrivalDate']->format('Y-m-d');

            return $item;
        }, $query->getQuery()->getArrayResult());
    }

    /**
     * Получаем количество всех поездок
     * @return mixed
     */
    public function getTripsCount($filter)
    {
        $query = $this->createQueryBuilder('s')
            ->select('COUNT(s)');
        $this->filter($query, $filter);

        return $query->getQuery()->getSingleScalarResult();
    }

    private function filter(QueryBuilder &$query, $filter) {
        if(!empty($filter['departureDateFrom'])) {
            $query->andWhere('s.departureDate >= :departureDateFrom')
                ->setParameter('departureDateFrom', $filter['departureDateFrom']);
        }
        if(!empty($filter['departureDateTo'])) {
            $query->andWhere('s.departureDate <= :departureDateTo')
                ->setParameter('departureDateTo', $filter['departureDateTo']);
        }
        if(!empty($filter['arrivalDateFrom'])) {
            $query->andWhere('s.arrivalDate >= :arrivalDateFrom')
                ->setParameter('arrivalDateFrom', $filter['arrivalDateFrom']);
        }
        if(!empty($filter['arrivalDateTo'])) {
            $query->andWhere('s.arrivalDate <= :arrivalDateTo')
                ->setParameter('arrivalDateTo', $filter['arrivalDateTo']);
        }
    }

    /**
     * Получаем дату отправки и прибытия курьера
     * @param int $courierId
     * @return array
     */
    public function getScheduleDates($courierId)
    {
        $query = $this->createQueryBuilder('s')
            ->select(['s.arrivalDate, s.departureDate'])
            ->where('s.courier = :courierId')
            ->setParameter('courierId', $courierId)
            ->getQuery();

        return $query->getArrayResult();
    }
}
