<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 01/09/2017
 * Time: 17:32
 */

namespace LouvreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="LouvreBundle\Repository\OrderRepository")
 * @ORM\Table(name="orders")
 */
class Order
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $orderDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="Veuillez spécifier une date de visite.")
     * @Assert\Date(message="Date invalide.")
     */
    private $venueDate;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20, unique=true)
     */
    private $orderNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Veuillez spécifier une adresse email.")
     * @Assert\Email(message="Veuillez vérifier votre email.")
     * @Assert\Length(max=100, maxMessage="100 caractères au plus.")
     */
    private $customerEmail;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $orderPaid;

    /**
     * @var \LouvreBundle\Entity\Duration
     *
     * @ORM\ManyToOne(targetEntity="LouvreBundle\Entity\Duration")
     * @ORM\JoinColumn(name="duration_id", referencedColumnName="id")
     */
    private $duration;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="LouvreBundle\Entity\Item", mappedBy="order")
     * @Assert\Valid()
     */
    private $items;

    /**
     * Order constructor.
     * Set default values for our order
     */
    public function __construct()
    {
        $this->orderPaid = false;
        $this->orderDate = new \DateTime();
        $this->items = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    /**
     * @return \DateTime
     */
    public function getVenueDate()
    {
        return $this->venueDate;
    }

    /**
     * @param \DateTime $venueDate
     */
    public function setVenueDate($venueDate)
    {
        $this->venueDate = $venueDate;
    }

    /**
     * @return string
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * @return string
     */
    public function getCustomerEmail()
    {
        return $this->customerEmail;
    }

    /**
     * @param string $customerEmail
     */
    public function setCustomerEmail($customerEmail)
    {
        $this->customerEmail = $customerEmail;
    }

    /**
     * @return bool
     */
    public function isOrderPaid()
    {
        return $this->orderPaid;
    }

    /**
     * @param bool $orderPaid
     */
    public function setOrderPaid($orderPaid)
    {
        $this->orderPaid = $orderPaid;
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
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param Item $item
     */
    public function addItem(Item $item)
    {
//        if ($this->items->contains($item)) {
//            return;
//        }
        $this->items[] = $item;
        $item->setOrder($this);

    }

    /**
     * @param Item $item
     */
    public function removeItem(Item $item)
    {
//        if (!$this->items->contains($item)) {
//            return;
//        }
        $this->items->removeElement($item);
        $item->setOrder(null);
    }

    /**
     * Generates a unique order number and set
     * it to its variable
     */
    public function createOrderNumber()
    {
        $orderNumber = $this->generateOrderNumber();
        $this->orderNumber = $orderNumber;
    }

    /**
     * Generates an order number based on order and venue date
     * and a ramdom 8 letters word
     * @return string
     */
    private function generateOrderNumber(){
        //generate random 8 letters word
        $letter = array_merge(range('a', 'z'), range('A', 'Z'));
        shuffle($letter);
        $word = substr(implode($letter), 0, 8);
        return date_format($this->orderDate, 'ymd')
            . $word
            . date_format($this->venueDate, 'ymd');
    }
}