<?php

namespace HotelBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PagesController extends Controller
{
    /**
     * @Route("/rooms", name="rooms")
     */
    public function roomAction()
    {

        $em = $this->getDoctrine()->getManager();
        $all_rooms = $em->getRepository('HotelBundle:Room')->findBy(array(), array('code'=>'ASC'));

        return $this->render('HotelBundle:Display:rooms.html.twig', array('rooms'=>$all_rooms));

    }

//    /**
//     * @Route("/clients", name="clients")
//     */
//    public function clientAction()
//    {
//
//        $em = $this->getDoctrine()->getManager();
//        $all_clients = $em->getRepository('HotelBundle:Room')->findAll();
//
//        return $this->render('HotelBundle:Display:clients.html.twig', array('rooms'=>$all_clients));
//
//    }

    /**
     * @Route("/products", name="products")
     */
    public function productAction()
    {

        $em = $this->getDoctrine()->getManager();
        $all_products = $em->getRepository('HotelBundle:Product')->findBy(array(), array('name'=>'ASC'));

        return $this->render('HotelBundle:Display:products.html.twig', array('products'=>$all_products));

    }


    /**
     * @Route("/insertproduct", name="insertproduct")
     */
    public function insertproductAction()
    {
        $em = $this->getDoctrine()->getManager();
        $all_products = $em->getRepository('HotelBundle:Product')->findBy(array('forSelling'=>0), array('name'=>'ASC'));

        return $this->render('HotelBundle:Add:add_product.html.twig', array('products'=>$all_products));

    }


    /**
     * @Route("/employees", name="employees")
     */
    public function employeesAction()
    {

        $em = $this->getDoctrine()->getManager();
        $all_employees = $em->getRepository('HotelBundle:Employee')->findBy(array(), array('name'=>'ASC'));

        return $this->render('HotelBundle:Display:employees.html.twig', array('employees'=>$all_employees));

    }

    /**
     * @Route("/sellings", name="sellings")
     */
    public function sellingsAction()
    {

        $em = $this->getDoctrine()->getManager();
        $all_products = $em->getRepository('HotelBundle:Product')->findBy(array('forSelling'=>1), array('name'=>'ASC'));

        return $this->render('HotelBundle:Add:selling.html.twig', array('products'=>$all_products));

    }

    /**
     * @Route("/buyings", name="buyings")
     */
    public function buyingsAction()
    {

        $em = $this->getDoctrine()->getManager();
        $all_products = $em->getRepository('HotelBundle:Product')->findBy(array('forSelling'=>0), array('name'=>'ASC'));

        return $this->render('HotelBundle:Add:buyings.html.twig', array('products'=>$all_products));

    }

    /**
     * @Route("/reservations", name="reservations")
     */
    public function reservationsAction()
    {

        $em = $this->getDoctrine()->getManager();
        $all_products = $em->getRepository('HotelBundle:Product')->findBy(array('forSelling'=>0), array('name'=>'ASC'));

        return $this->render('HotelBundle:Display:reservations.html.twig', array('products'=>$all_products));

    }

    /**
     * @Route("/finance", name="finance")
     */
    public function financeAction()
    {

        $em = $this->getDoctrine()->getManager();

        $from = new \DateTime('first day of this month 00:00:00');
        $to = new \DateTime('now');

        $qb = $em->createQueryBuilder()
            ->select('c')
            ->from('HotelBundle:Bill', 'c')
            ->where('c.date BETWEEN :firstDate AND :lastDate')
            ->setParameter('firstDate', $from)
            ->setParameter('lastDate', $to)
            ->andwhere('c.type = :type')
            ->setParameter('type', 0);

        $buying_bills = $qb->getQuery()->getResult();

        $qb = $em->createQueryBuilder()
            ->select('c')
            ->from('HotelBundle:Bill', 'c')
            ->where('c.date BETWEEN :firstDate AND :lastDate')
            ->setParameter('firstDate', $from)
            ->setParameter('lastDate', $to)
            ->andwhere('c.type = :type')
            ->setParameter('type', 1);

        $selling_bills = $qb->getQuery()->getResult();
        $qb = $em->createQueryBuilder()
            ->select('c')
            ->from('HotelBundle:Bill', 'c')
            ->where('c.date BETWEEN :firstDate AND :lastDate')
            ->setParameter('firstDate', $from)
            ->setParameter('lastDate', $to)
            ->andwhere('c.type = :type')
            ->setParameter('type', 3);

        $other_expenses = $qb->getQuery()->getResult();

        $qb = $em->createQueryBuilder()
            ->select('c')
            ->from('HotelBundle:Booking', 'c')
            ->where('c.date BETWEEN :firstDate AND :lastDate')
            ->setParameter('firstDate', $from)
            ->setParameter('lastDate', $to);

        $reservations = $qb->getQuery()->getResult();
        return $this->render('HotelBundle:Display:finance.html.twig', array('buying'=>$buying_bills, 'selling'=>$selling_bills, 'reservations'=>$reservations,'other_expenses'=>$other_expenses));

    }

}
