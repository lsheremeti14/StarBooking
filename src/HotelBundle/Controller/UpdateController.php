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

class UpdateController extends Controller
{

    /**
     *
     * @param Request $request
     * @Route("/update-employee", name="update_employee")
     */
    public function updateEmployeeAction(){

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

            if($request->isXmlHttpRequest()) {


            $id=$request->get('e_id');
            $name = $request->get('e_name');
            $surname = $request->get('e_surname');
            $position = $request->get('e_position');
            $address = $request->get('e_address');
            $cel = $request->get('e_cel');
            $salary = $request->get('e_salary');
            $date2 = $request->get('e_date');
            $date=date('Y-m-d',strtotime($date2));

            $sql = "UPDATE `employee` SET `name`='$name',`surname`='$surname',`position`='$position', `address`='$address',`cel`='$cel',`salary`='$salary',`starting_date`='$date' WHERE `id`=$id";
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
     * @Route("/update-room", name="update_room")
     */
    public function updateRoomAction(){

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {


            $id=$request->get('id');
            $code = $request->get('code');
            $description = $request->get('description');
            $beds = $request->get('beds');
            $price = $request->get('price');

            $sql = "UPDATE `room` SET `code`='$code',`description`='$description',`beds`='$beds', `price`='$price' WHERE `id`=$id";
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
     * @Route("/confirm-payment", name="confirm_payment")
     */
    public function confirmPaymentAction(){

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {


            $id=$request->get('id');


            $sql = "UPDATE `booking` SET `paid`=1 WHERE `id`=$id";
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
     * @Route("/update-user", name="update_user")
     */
    public function updateUserAction(){

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {


            $id=$request->get('id');

            $user = $em->getRepository('HotelBundle:Room')->findOneBy(array(), array('id'=>'DESC'));

            $user_id=$user->getId();

            $sql = "UPDATE `employee` SET `system`=1,`user`='$user_id' WHERE `id`='$id'";
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
     * @Route("/update-product", name="update_product")
     */
    public function updateProductAction(){

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {


            $id=$request->get('id');
            $prod = $request->get('p_name');
            $quant = $request->get('p_quantity');
            $unit = $request->get('p_unit');



            $sql = "UPDATE `product` SET `name`='$prod',`quantity`='$quant',`unit`='$unit' WHERE `id`='$id'";
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
     * @Route("/update-product-price", name="update_product_with_price")
     */
    public function updateProductPriceAction(){

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {


            $id=$request->get('id');
            $prod = $request->get('p_name');
            $quant = $request->get('p_quantity');
            $unit = $request->get('p_unit');
            $price = $request->get('p_price');



            $sql = "UPDATE `product` SET `name`='$prod',`quantity`='$quant',`selling`='$price',`unit`='$unit' WHERE `id`='$id'";
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
     * @Route("/list-ingredients", name="list_ingredients")
     */
    public function listIngredientsAction(){

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {

            $products=[];

            $id=$request->get('id');
            $sql = "SELECT * FROM `ingredients` WHERE `product_id`='$id'";
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $prod = $stmt->fetchAll();
            for($i=0;$i<count($prod);$i++){
                $p_id=$prod[$i]['id'];
                $sql = "SELECT * FROM `product` WHERE `id`='$p_id'";
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute();
                $prod2 = $stmt->fetch();
                $product = $prod2['name'];
                array_push($products,$product);

            }



            $message=array();
            $message[0]=$products;
            $message[1]=$prod;
            $file_array = json_encode($message, true);

            $response = new Response($file_array);
            $response->headers->set('Content-Type', 'application/json');
            return  $response;

        } else {
            return new Response('no ajax');
        }
    }

    /**
     *
     * @param Request $request
     * @Route("/update-list-ingredients", name="update_list_ingredients")
     */
    public function updateListIngredientsAction(){

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {

            $products=[];

            $id=$request->get('id');
            $prod=$request->get('product');
            $quant=$request->get('quantity');

            for($i=0;$i<count($prod);$i++){
                echo $quant[$i]." -> ".$id;
                $sql = "UPDATE `ingredients` SET `quantity`='$quant[$i]'  WHERE `id`='$prod[$i]'";
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute();
            }



            $message=array();
            $message[0]=$prod;
            $message[1]=$quant;
            $file_array = json_encode($message, true);
            $response = new Response($file_array);
            $response->headers->set('Content-Type', 'application/json');
            return  $response;

        } else {
            return new Response('no ajax');
        }
    }

}
