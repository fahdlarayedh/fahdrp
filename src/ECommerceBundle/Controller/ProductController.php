<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 01/02/2020
 * Time: 20:36
 */

namespace ECommerceBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ECommerceBundle\Entity\Image;
use ECommerceBundle\Entity\Product;
use ECommerceBundle\Form\ProductType;

class ProductController extends Controller
{

    public function Add_ProductAction(Request $request)
    {
        //$today = new \DateTime();
        $Product = new Product();
        $form = $this->createForm(ProductType::class,$Product);

        $form = $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //$em = $this->getDoctrine()->getManager();
            //$Product->setDate($today);
            //$em->persist($Product);
            //$em->flush();

            return new Response(json_encode(array('status'=>'success')));

        }

        return $this->render('@ECommerce/Default/index.html.twig',array('form' => $form->createView()));
    }

    public function testAction(Request $request)
    {
        echo 'start ECommerce';
        $em = $this->getDoctrine()->getManager();
        $array = $em->getRepository(Image::class)->FindAllByProductRefrence('test');
        //var_dump($array);


        return $this->render('@ECommerce/Default/empty.html.twig');
    }

    public function List_ProductAction()
    {
        $Products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        $images = $this->getDoctrine()->getRepository(Image::class)->findAll();

        return $this->render('@ECommerce/Default/list.html.twig',array('Products'=>$Products,'Images'=>$images));
    }

    public function Delete_ProductAction($id)
    {
        $rm = $this->getDoctrine()->getManager();
        $product=$rm->getRepository(Product::class)->find($id);

        $rm->remove($product);
        $rm->flush();

        return $this->redirectToRoute('List_Product');
    }

    public function Modify_ProductAction($id,Request $request)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductType::class,$product);
           /* ->add('Modify',SubmitType::class,
                ['label'=> 'Modify Product']);*/

        $form = $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('List_Product');
        }
        //var_dump($id);
        //die();
        return $this->render('@ECommerce/Default/Modify.html.twig',array(
            'form' => $form->createView(),'id_product'=>$id
        ));
    }

    public function List_Product_FrontAction($category)
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findByCategory($category);
        $images = $this->getDoctrine()->getRepository(Image::class)->findAll();

        return $this->render('@ECommerce/Default/Front_list_products.html.twig',array('category'=>$category,'Products'=>$products,'Images'=>$images));

    }

    public function Single_Product_FrontAction($refrence)
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findByRefrence($refrence);
        $images = $this->getDoctrine()->getRepository(Image::class)->findAll();

        return $this->render('@ECommerce/Default/Single_list_products.html.twig',array('Product'=>$products,'Images'=>$images));

    }




}