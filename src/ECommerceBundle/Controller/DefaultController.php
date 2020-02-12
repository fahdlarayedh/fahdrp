<?php

namespace ECommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ECommerceBundle:Default:index.html.twig');
    }
}
