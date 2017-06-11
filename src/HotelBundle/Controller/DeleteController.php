<?php

namespace HotelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use HotelBundle\Entity\FosUser;
use HotelBundle\Entity\UserRole;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\DBAL\Statement;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class DeleteController extends Controller
{

    /**
     *
     * @param Request $request
     * @Route("/delete-employee", name="delete_employee")
     */
    public function deleteEmployeeAction(){

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

            if($request->isXmlHttpRequest()) {


                $id = $request->get('id');
                $user_id = $request->get('user');

                $sql = "DELETE FROM `fos_user` WHERE `id`='$user_id'";
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute();

                $sql = "DELETE FROM `employee` WHERE `id`='$id'";
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute();

                $message = array();
                $message[0] = "OK";
                $response = new Response();
                $data = json_encode($message);
                $response->headers->set('Content-Type', 'application/json');
                $response->setContent($data);
                return $response;
            } else {
                return new Response('no ajax');
            }
    }

    /**
     *
     * @param Request $request
     * @Route("/delete-user", name="delete_user")
     */
    public function deleteUserAction(){

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {


            $id = $request->get('id');
            $e_id = $request->get('employee');

            $sql = "DELETE FROM `fos_user` WHERE `id`='$id'";
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();

            $sql = "UPDATE `employee` SET `system`=0,`user`=0 WHERE `id`='$e_id'";
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();


            $message = array();
            $message[0] = "OK";
            $response = new Response();
            $data = json_encode($message);
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent($data);
            return $response;
        } else {
            return new Response('no ajax');
        }
    }

    /**
     *
     * @param Request $request
     * @Route("/delete-product", name="delete_product")
     */
    public function deleteProductAction(){

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {


            $id = $request->get('id');

            $sql = "DELETE FROM `product` WHERE `id`='$id'";
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();

            $message = array();
            $message[0] = "OK";
            $response = new Response();
            $data = json_encode($message);
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent($data);
            return $response;
        } else {
            return new Response('no ajax');
        }
    }

    /**
     *
     * @param Request $request
     * @Route("/delete-room", name="delete_room")
     */
    public function deleteRoomAction(){

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {


            $id = $request->get('id');

            $sql = "DELETE FROM `room` WHERE `id`='$id'";
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();

            $message = array();
            $message[0] = "OK";
            $response = new Response();
            $data = json_encode($message);
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent($data);
            return $response;
        } else {
            return new Response('no ajax');
        }
    }


}
