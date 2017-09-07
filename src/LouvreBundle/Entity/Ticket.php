<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 01/09/2017
 * Time: 17:19
 */

namespace LouvreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="LouvreBundle\Repository\TicketRepository")
 * @ORM\Table(name="tickets")
 */
class Ticket
{
    /**
     * @var integer
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=7, scale=2)
     */
    private $price;

    /**
     * @var \LouvreBundle\Entity\Duration
     *
     * @ORM\ManyToOne(targetEntity="LouvreBundle\Entity\Duration")
     * @ORM\JoinColumn(name="duration_id", referencedColumnName="id")
     */
    private $duration;

    /**
     * @var \LouvreBundle\Entity\Rate
     *
     * @ORM\ManyToOne(targetEntity="LouvreBundle\Entity\Rate")
     * @ORM\JoinColumn(name="rate_id", referencedColumnName="id")
     */
    private $rate;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return Duration
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param Duration $duration
     */
    public function setDuration(Duration $duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return Rate
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param Rate $rate
     */
    public function setRate(Rate $rate)
    {
        $this->rate = $rate;
    }



}