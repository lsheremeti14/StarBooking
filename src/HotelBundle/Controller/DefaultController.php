<?php

namespace HotelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use HotelBundle\Entity\FosUser;
use HotelBundle\Entity\UserRole;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{

    /**
     * @Route("/", name="home")
     */
    public function homeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $all_rooms = $em->getRepository('HotelBundle:Room')->findBy(array(), array('code'=>'ASC'));

        return $this->render('HotelBundle:Display:rooms.html.twig', array('rooms'=>$all_rooms));

    }
}
