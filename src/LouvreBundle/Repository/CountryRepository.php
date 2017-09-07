<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 02/09/2017
 * Time: 12:02
 */

namespace LouvreBundle\Repository;


use Doctrine\ORM\EntityRepository;

class CountryRepository extends EntityRepository
{
    public function getCountriesByAlphabeticalOrder()
    {
        return $this
            ->createQueryBuilder('c')
            ->orderBy('c.name','ASC');
    }
}