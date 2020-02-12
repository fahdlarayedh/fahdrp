<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/02/2020
 * Time: 18:59
 */

namespace ECommerceBundle\Controller;


use ECommerceBundle\Entity\Cart;
use ECommerceBundle\Entity\Commande;
use LoginBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommandeController extends Controller
{
    public function checkout_confirmAction(Request $request)
    {
        //var_dump($request);
        //die();

        $user = $this->getUser();

        $commande = new Commande();
            $commande->setDescription('');
            $commande->setEtat('Nouvelle Commande');
            $commande->setPaiment($request->get('payment_radio'));
        //create new Commande

        $commande->addCommandes($user);
        //add table Commandes

        $rm = $this->getDoctrine()->getManager();

        $rm->persist($commande);
        $rm->flush();


        //create facture

        //delete panier
        $articles = $rm->getRepository(Cart::class)->Cart_user_id($user->getId());
        foreach ($articles as $key)
        {
            $rm->remove($key);
        }
        $rm->flush();

        //return new \Symfony\Component\HttpFoundation\Response('DONE !');
        return $this->redirectToRoute('Cart');

    }
    public function List_CommandeAction()
    {
        $cmds = $this->getDoctrine()->getRepository(Commande::class)->myfind();
        $Commandes = $this->getDoctrine()->getRepository(Commande::class)->findAll();
        $clients = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('@ECommerce/Default/list_commande.html.twig',
            array('commandes'=>$Commandes,
                'cmds'=>$cmds,
                'clients'=>$clients
            ));

    }

}