<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 04/09/2017
 * Time: 08:43
 */

namespace LouvreBundle\Repository;


use Doctrine\ORM\EntityRepository;
use LouvreBundle\Entity\Order;

class ItemRepository extends EntityRepository
{
    /**
     * Function to retrieve the details of an order : type of ticket sold,
     * number of ticket sold, amount of ticket sold
     * The values are grouped by ticket type
     *
     * @param Order $order
     * @return array
     */
    public function getItemsDataFromOrder(Order $order)
    {
        return $this->createQueryBuilder('i')
            ->innerJoin('i.ticket', 't')
            ->addSelect('t')
            ->innerJoin('t.rate', 'r')
            ->addSelect('r')
            ->where('i.order = :order')
            ->setParameter('order', $order)
            ->select( 'r.name, COUNT(i) as number, t.price as unit_price,  SUM(t.price) as total_price')
            ->groupBy('r.name')
            ->orderBy('r.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Function to retrieve the total amount of an order
     *
     * @param Order $order
     * @return mixed
     */
    public function getTotalAmountOfOrder(Order $order)
    {
        return $this->createQueryBuilder('i')
            ->innerJoin('i.ticket', 't')
            ->addSelect('t')
            ->where('i.order = :order')
            ->setParameter('order', $order)
            ->select('SUM(t.price) as total_price')
            ->getQuery()
            ->getSingleResult();

    }

    /**
     * Function to retrieve the detail of an order (name and rate)
     *
     * @param Order $order
     * @return array
     */
    public function getItemsDetailForTicket(Order $order)
    {
        return $this->createQueryBuilder('i')
            ->innerJoin('i.ticket', 't')
            ->addSelect('t')
            ->innerJoin('t.rate', 'r')
            ->addSelect('r')
            ->where('i.order = :order')
            ->setParameter('order', $order)
            ->select("CONCAT(i.lastName, ', ', i.firstName) as name, r.name as rate, t.price as price")
            ->orderBy('i.lastName, i.firstName', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
