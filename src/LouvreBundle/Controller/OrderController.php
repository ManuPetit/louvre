<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 02/09/2017
 * Time: 11:40
 */

namespace LouvreBundle\Controller;


use LouvreBundle\Entity\Order;
use LouvreBundle\Form\OrderFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Error\Card;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class OrderController extends Controller
{
    /**
     * @Route("/billetterie", name="create_ticket")
     */
    public function createAction(Request $request)
    {
        $order = new Order();
        $form = $this->createForm(OrderFormType::class, $order);
        
        //check form submission
        if ($request->isMethod('POST'))
        {
            //link $order with data typed in the form
            $form->handleRequest($request);
            //check data
            if ($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                //create the order number
                $order->createOrderNumber();
                //check that the order number is unique
                while ($em->getRepository('LouvreBundle:Order')->findByOrderNumber($order->getOrderNumber())){
                    $order->createOrderNumber();
                }
                //relate each item to the order
                foreach ($order->getItems() as $item){
                    $order->addItem($item);
//                    dump($order);
//                    dump($item);die;
                    $em->persist($item);
                    //set the ticket
                    $item->setTicket(
                        $em->getRepository('LouvreBundle:Ticket')
                        ->getTicketByRateAndDuration($item->getTicketRate(), $order->getDuration())
                    );
                }
                $em->flush();
                return $this->redirectToRoute('checkout_ticket',['id' => $order->getId()]);
            }

        }
        return $this->render('LouvreBundle:Order:create.html.twig',[
            'orderForm' => $form->createView()
        ]);

    }

    /**
     * @Route("/checkout/{id}", name="checkout_ticket")
     */
    public function checkoutAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $order =  $em->getRepository('LouvreBundle:Order')->find($id);
        $lines = $em->getRepository('LouvreBundle:Item')->getItemsDataFromOrder($order);
        $total_amount = $em->getRepository('LouvreBundle:Item')->getTotalAmountOfOrder($order);
        $amount = $total_amount['total_price'];
        //stripe need the price in cents
        $stripe_amount = $amount * 100;
        return $this->render('LouvreBundle:Order:checkout.html.twig', [
            'order' => $order,
            'lines' => $lines,
            'stripe_amount' => $stripe_amount,
            'amount' => $amount
        ]);
    }

    /**
     * @Route("/paiement/{id}", name="pay_ticket")
     */
    public function payAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $order =  $em->getRepository('LouvreBundle:Order')->find($id);
        //retrieve the amount
        $total_amount = $em->getRepository('LouvreBundle:Item')->getTotalAmountOfOrder($order);
        $stripe_amount = $total_amount['total_price'] * 100;
        //secret key for the api
        Stripe::setApiKey("sk_test_mpNlXQIdKANa8PC3wpNdhwo6");
        //recover the token created by checkout page
        $token = $_POST['stripeToken'];
        //create the charge to get paiement from user
        try {
            $charge = Charge::create([
                "amount" => $stripe_amount,
                "currency" => "eur",
                "description" => "Commande " . $order->getOrderNumber(),
                "source" => $token
            ]);
            //changing the order to paid
            $order->setOrderPaid(true);
            $em->flush();
            $this->addFlash("success","Votre paiement a bien été enregistré.");
            return $this->redirectToRoute('confirm_ticket', ['id' => $order->getId()]);
        }catch (Card $e){
            // Card was declined.
            $e_json = $e->getJsonBody();
            $message = "Une erreur s'est produite : <strong>";
            switch ($e_json['error']['code']){
                case 'invalid_number':
                    $message .= "le numéro de la carte n'est pas un numéro valide.";
                    break;
                case 'invalid_expiry_month':
                    $message .= "le mois d'expiration de la carte est incorrect.";
                    break;
                case 'invalid_expiry_year':
                    $message .= "l'année d'expiration de la carte est incorrecte.";
                    break;
                case 'invalid_cvc':
                    $message .= "le code de sécurité de la carte n'est pas valide.";
                    break;
                case 'incorrect_number':
                    $message .= "le number de la carte est incorrect.";
                    break;
                case 'expired_card':
                    $message .= "la carte a expiré.";
                    break;
                case 'incorrect_cvc':
                    $message .= "le code de sécurité de la carte est incorrect.";
                    break;
                case 'incorrect_zip':
                    $message .= "le code postal n'a pas été validé.";
                    break;
                case 'card_declined':
                    $message .= "la carte a été refusée.";
                    break;
                default:
                    $message .= "impossibilité de traiter votre carte.";
            }
            $message .= "</strong><br>Veuillez essayer à nouveau.";
            $this->addFlash("warning", $message);
            //redirect to paiement page
            return $this->redirectToRoute('checkout_ticket', ['id' => $order->getId()]);
        }
    }

    /**
     * @Route("/confirmation/{id}", name="confirm_ticket")
     */
    public function confirmAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('LouvreBundle:Order')->find($id);
        //retrieve the amount
        $total_amount = $em->getRepository('LouvreBundle:Item')->getTotalAmountOfOrder($order);
        //retrieving the details of the order
        $lines = $em->getRepository('LouvreBundle:Item')->getItemsDetailForTicket($order);
        //creating message to send
        $message = (new \Swift_Message('Musée du Louvre : votre commande'))
            ->setFrom('tickets@lelouvre.fr')
            ->setTo($order->getCustomerEmail())
            ->setBody(
                $this->renderView('LouvreBundle:Emails:sendticket.html.twig', [
                    'order' => $order,
                    'lines' => $lines,
                    'total' => $total_amount['total_price']
                ]),
                'text/html'
            );
        $this->get('mailer')->send($message);

        return $this->render('LouvreBundle:Order:confirm.html.twig');

    }
}