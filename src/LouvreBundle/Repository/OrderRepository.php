<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 11/09/2017
 * Time: 13:39
 */

namespace LouvreBundle\Repository;


use Doctrine\ORM\EntityRepository;

class OrderRepository extends EntityRepository
{
    public function getNumberTicketLeftForDate(\DateTime $venueDate)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.venueDate = :venueDate')
            ->setParameter('venueDate', $venueDate)
            ->andWhere('o.orderPaid = 1')
            ->innerJoin('o.items', 'i')
            ->select('COUNT (i.id) as sold')
            ->getQuery()
            ->getSingleResult();
    }
}