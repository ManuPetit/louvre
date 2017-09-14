<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 12/09/2017
 * Time: 17:24
 */

namespace tests\LouvreBundle\Controller;

use LouvreBundle\Entity\Order;
use Proxies\__CG__\LouvreBundle\Entity\Item;
use Stripe\Token;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Error\Card;

class OrderControllerTest extends WebTestCase
{
    private $container;
    private $em;

    /**
     * This function allow us to call the entity manager
     */
    public function setUp()
    {
        self::bootKernel();
        $this->container = self::$kernel->getContainer();
        $this->em = $this->container->get('doctrine')->getManager();
    }

    /**
     * This function checks that when a bad email is given an error
     * is raised
     */
    public function testInvalidEmailGivenOnCreateAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/billetterie');

        //get the form to fill
        $form = $crawler->selectButton('submit')->form();
        $thisDate = new \DateTime('2018-02-21');
        $form['order_form[venueDate]'] = date_format($thisDate, 'YYYY-mm-dd');
        //provide a wrong email
        $form['order_form[customerEmail]'] = 'falseemail';
        $form['order_form[duration]']->select(1);
        $values = $form->getPhpValues();
        $values['order_form']['items'][0]['firstName'] = "Pascal";
        $values['order_form']['items'][0]['lastName'] = "Copain";
        $values['order_form']['items'][0]['birthDate'] = new \DateTime('1945-12-24');

        $crawler = $client->submit($form);
        //this should appear as an error message on the page
        $this->assertEquals(200,$client->getResponse()->getStatusCode());
        $this->assertContains('Veuillez vérifier votre email.', $client->getResponse()->getContent());
    }

    /**
     * This test ensure that a paid order cannot be paid again
     */
    public function testCannotPayTwiceSameOrder()
    {
        $order = $this->createMockOrder();
        $order->setOrderPaid(true);
        $this->em->flush();
        $client = static::createClient();
        $crawler = $client->request('GET','/checkout/' . $order->getId());
        $this->assertEquals(404,$client->getResponse()->getStatusCode());
    }

    /**
     * Function to create a fake order and save it in the database
     * @return Order
     */
    private function createMockOrder(){
        //generate random 10 letters word
        $word = substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyz", 10)), 0, 10);
        $order = new Order();
        $order->setVenueDate(new \DateTime('2018-02-21'));
        $order->setCustomerEmail('john' . $word . '@dodo.com');
        $duration = $this->em->getRepository('LouvreBundle:Duration')->find(1);
        $order->setDuration($duration);
        $order->createOrderNumber();
        //check that the order number is unique
        while ($this->em->getRepository('LouvreBundle:Order')->findByOrderNumber($order->getOrderNumber())){
            $order->createOrderNumber();
        }
        $item = new Item();
        $item->setFirstName('John');
        $item->setLastName($word);
        $item->setBirthDate(new \DateTime('1980-02-24'));
        $country = $this->em->getRepository('LouvreBundle:Country')->find(75);
        $item->setCountry($country);
        $ticket = $this->em->getRepository('LouvreBundle:Ticket')->find(1);
        $item->setTicket($ticket);
        $order->addItem($item);
        $this->em->persist($item);
        $this->em->persist($order);
        $this->em->flush();

        return $order;
    }
    public function tearDown()
    {
        $this->em = null;
        $this->container = null;
    }
}