<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 04/09/2017
 * Time: 08:44
 */

namespace LouvreBundle\Repository;


use Doctrine\ORM\EntityRepository;
use LouvreBundle\Entity\Ticket;

class TicketRepository extends EntityRepository
{
    /**Function to retrieve a ticket depending of a
     * visit duration and the rate
     *
     * @param integer $rate
     * @param integer $duration
     * @return Ticket
     */
    public function getTicketByRateAndDuration($rate, $duration){
        return $this->createQueryBuilder('t')
            ->andWhere('t.rate = :rate')
            ->andWhere('t.duration = :duration')
            ->setParameter('rate', $rate)
            ->setParameter('duration', $duration)
            ->getQuery()
            ->getSingleResult();
    }
}
