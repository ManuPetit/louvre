<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 06/09/2017
 * Time: 17:00
 */

namespace LouvreBundle\Menu;


use Knp\Menu\MenuFactory;

class Builder
{
    public function mainMenu(MenuFactory $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');
        $menu->addChild('Accueil', ['route' => 'welcome_page'])
            ->setLinkAttribute('title', 'Allez à notre page d\'accueil');
        $menu->addChild('Billetterie', ['route' => 'create_ticket'])
            ->setLinkAttribute('title', 'Achetez vos billets');
        $menu->addChild('Infos', ['route' => 'info_page'])
            ->setLinkAttribute('title', 'Infos pratiques pour votre visite');
        return $menu;
    }
}