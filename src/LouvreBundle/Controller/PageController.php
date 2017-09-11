<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 01/09/2017
 * Time: 15:28
 */

namespace LouvreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    /**
     * @Route("/", name="welcome_page")
     */
    public function welcomePage()
    {
        return $this->render('LouvreBundle:Page:welcome.html.twig');
    }

    /**
     * @Route("/infos", name="info_page")
     */
    public function infoAction()
    {
        return $this->render('LouvreBundle:Page:info.html.twig');
    }
}
