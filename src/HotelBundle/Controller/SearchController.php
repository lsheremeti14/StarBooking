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
use Symfony\Component\Validator\Constraints\DateTime;


class SearchController extends Controller
{

    /**
     *
     * @param Request $request
     * @Route("/search_by_date", name="search_by_date")
     */
    public function searchByDateAction()
    {

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $sdate2 = $request->get('s_date');
            $edate2 = $request->get('e_date');
            $all_reservations = [];

            $sdate = date('Y-m-d', strtotime($sdate2 .'-1 day'));
            $edate = date('Y-m-d', strtotime($edate2 .'+1 day'));

            $all_rooms = $em->getRepository('HotelBundle:Room')->findAll();
            $qb = $em->createQueryBuilder()
                ->select('c')
                ->from('HotelBundle:Booking', 'c')
                ->where('c.startingDate BETWEEN :firstDate AND :lastDate')
                ->setParameter('firstDate', $sdate)
                ->setParameter('lastDate', $edate)
                ->orWhere('c.endingDate BETWEEN :firstDate AND :lastDate')
                ->setParameter('firstDate', $sdate)
                ->setParameter('lastDate', $edate);

            $result = $qb->getQuery()->getResult();

            for ($i = 0; $i < count($result); $i++) {
                array_push($all_reservations, $result[$i]->getRoom()->getId());
            }
            $rooms = [];
            for ($i = 0; $i < count($all_rooms); $i++) {
                array_push($rooms, $all_rooms[$i]->getId());
            }
            $empty = [];
            for ($j = 0; $j < count($rooms); $j++) {
                if (in_array($rooms[$j], $all_reservations)) {
                    $empty[$j] = 1;
                } else {
                    $empty[$j] = 0;
                }
            }

            $template = $this->render(
                'HotelBundle:Display:search_rooms.html.twig',
                array('reservations' => $result, 'rooms' => $all_rooms, 'empty' => $empty, 's_date'=>$sdate2, 'e_date'=>$edate2)
            )->getContent();

            $json = json_encode($template);
            $response = new Response($json, 200);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        } else {
            return new Response('no ajax');
        }
    }

    /**
     *
     * @param Request $request
     * @Route("/search_by_client", name="search_by_client")
     */
    public function searchByClientAction()
    {

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $name=$request->get('search');

            $qb = $em->createQueryBuilder()
                ->select('c')
                ->from('HotelBundle:Booking', 'c')
                ->where('c.name = :fname')
                ->setParameter('fname', $name)
                ->orWhere('c.surname = :sname')
                ->setParameter('sname', $name)
                ->orderBy('c.date', 'DESC');

            $all_clients = $qb->getQuery()->getResult();

            $template = $this->render(
                'HotelBundle:Display:search_client.html.twig',
                array('clients'=>$all_clients)
            )->getContent();

            $json = json_encode($template);
            $response = new Response($json, 200);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        } else {
            return new Response('no ajax');
        }
    }

    /**
     *
     * @param Request $request
     * @Route("/search_by_room", name="search_by_room")
     */
    public function searchByRoomAction()
    {

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $name=$request->get('search');

            $room = $em->getRepository('HotelBundle:Room')->findOneBy(array('code'=>$name));
            $id=$room->getId();


            $qb = $em->createQueryBuilder()
                ->select('c')
                ->from('HotelBundle:Booking', 'c')
                ->where('c.room = :room')
                ->setParameter('room', $id)
                ->orderBy('c.date', 'DESC');

            $all_clients = $qb->getQuery()->getResult();

            $template = $this->render(
                'HotelBundle:Display:search_client.html.twig',
                array('clients'=>$all_clients)
            )->getContent();

            $json = json_encode($template);
            $response = new Response($json, 200);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        } else {
            return new Response('no ajax');
        }
    }

    /**
     *
     * @param Request $request
     * @Route("/search_for_finance", name="search_for_finance")
     */
    public function searchForFinanceAction()
    {

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $month=$request->get('month');

            $from = new \DateTime('first day of '.$month.' 00:00:00 + 1 month');
            $to = new \DateTime('last day of '.$month.' 23:59:59');
            $result = $from->format('Y-m-d 00:00:00');
            $result2 = $to->format('Y-m-d 23:59:59');

            echo "hello ".$result." --> ".$result2;

            $qb = $em->createQueryBuilder()
                ->select('c')
                ->from('HotelBundle:Bill', 'c')
                ->where('c.date BETWEEN :firstDate AND :lastDate')
                ->setParameter('firstDate', $result)
                ->setParameter('lastDate', $result2)
                ->andwhere('c.type = :type')
                ->setParameter('type', 0);

            $buying_bills = $qb->getQuery()->getResult();

            $qb = $em->createQueryBuilder()
                ->select('c')
                ->from('HotelBundle:Bill', 'c')
                ->where('c.date BETWEEN :firstDate AND :lastDate')
                ->setParameter('firstDate', $result)
                    ->setParameter('lastDate', $result2)
                ->andwhere('c.type = :type')
                ->setParameter('type', 1);

            $selling_bills = $qb->getQuery()->getResult();

            $qb = $em->createQueryBuilder()
                ->select('c')
                ->from('HotelBundle:Bill', 'c')
                ->where('c.date BETWEEN :firstDate AND :lastDate')
                ->setParameter('firstDate', $result)
                ->setParameter('lastDate', $result2)
                ->andwhere('c.type = :type')
                ->setParameter('type', 3);

            $other_expenses = $qb->getQuery()->getResult();

            $qb = $em->createQueryBuilder()
                ->select('c')
                ->from('HotelBundle:Booking', 'c')
                ->where('c.date BETWEEN :firstDate AND :lastDate')
                ->setParameter('firstDate', $result)
                ->setParameter('lastDate', $result2);

            $reservations = $qb->getQuery()->getResult();

            echo "first: ".count($buying_bills);
            echo "second: ".count($selling_bills);
            echo "third: ".count($reservations);
            echo "forth: ".count($other_expenses);
            $template = $this->render('HotelBundle:Display:search_finance.html.twig', array('buying'=>$buying_bills, 'selling'=>$selling_bills, 'reservations'=>$reservations,'other_expenses'=>$other_expenses))->getContent();


            $json = json_encode($template);
            $response = new Response($json, 200);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        } else {
            return new Response('no ajax');
        }
    }
}
