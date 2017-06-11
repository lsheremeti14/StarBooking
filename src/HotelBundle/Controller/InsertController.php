<?php

namespace HotelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use HotelBundle\Entity\UserRole;
use HotelBundle\Entity\FosUser;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\DBAL\Statement;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class InsertController extends Controller
{

    /**
     *
     * @param Request $request
     * @Route("/add-employee", name="add_employee")
     */
    public function insertEmployeeAction(){

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

            if($request->isXmlHttpRequest()) {


            $name = $request->get('e_name');
            $surname = $request->get('e_surname');
            $position = $request->get('e_position');
            $address = $request->get('e_address');
            $cel = $request->get('e_cel');
            $salary = $request->get('e_salary');
            $date2 = $request->get('e_date');
            $date=date('Y-m-d',strtotime($date2));

            $sql = "INSERT INTO `employee`(`id`, `name`, `surname`, `position`, `address`, `cel`, `salary`, `starting_date`,`system`) 
              VALUES ('','$name','$surname','$position','$address','$cel','$salary','$date',0)";
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
     * @Route("/add-room", name="add_room")
     */
    public function insertRoomAction(){

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {


            $code = $request->get('code');
            $description = $request->get('description');
            $beds = $request->get('beds');
            $price = $request->get('price');

            $sql = "INSERT INTO `room`(`id`, `code`, `description`, `beds`, `price`) 
              VALUES ('','$code','$description','$beds','$price')";
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
     * @Route("/add-photo/{id}", name="add_photo")
     */
    public function insertPhotoAction($id){

        $em = $this->getDoctrine()->getManager();
        $post = Request::createFromGlobals();

        if ($post->request->has('add_photo')) {

            $target_dir = './uploads';

            $tmp_name = $_FILES["photo"]["tmp_name"];
            $filename = $_FILES["photo"]["name"];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            move_uploaded_file($tmp_name, "$target_dir/$filename");
            $cover = $filename;

            $sql = "UPDATE `room` SET `photo`='$cover' WHERE `id`= '$id'";
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();

        }
        $em = $this->getDoctrine()->getManager();
        $all_rooms = $em->getRepository('HotelBundle:Room')->findBy(array(), array('code'=>'ASC'));

        return $this->render('HotelBundle:Display:rooms.html.twig', array('rooms'=>$all_rooms));
    }


    /**
     *
     * @param Request $request
     * @Route("/add-user-system", name="add_user_system")
     */
    public function insertUserAction(){

        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {


            $username = $request->get('username');
            $password = $request->get('password');
            $email = $request->get('email');
            $roli = $request->get('role');
            $id = $request->get('id');

            $user = new FosUser();

            $user->setUsername($username);
            $user->setPlainPassword($password);
            $user->setEmail($email);
            $user->addRole($roli);
            $user->setEnabled(1);



            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

           // $user2 = $em->getRepository('HotelBundle:Room')->findOneBy(array('id'=>'DESC'));

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
     * @Route("/add-product", name="add_product")
     */
    public function insertProductAction(){

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {


            $name = $request->get('name');
            $unit = $request->get('unit');
            $forSelling = $request->get('forSelling');
            $selling = $request->get('selling');
            $ty = $request->get('type');
            $quantity = $request->get('quantity');
            $ing_id = $request->get('ing_id');
            $ing_q = $request->get('ing_q');
            if($ty=="raw"){
                $type=0;
                $sql = "INSERT INTO `product`(`id`, `name`, `type`, `for_selling`, `quantity`, `selling`,`unit`) 
                                  VALUES ('','$name','$type','$forSelling','$quantity', '$selling','$unit')";
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute();

            }else{
                $type=1;
                $sql = "INSERT INTO `product`(`id`, `name`, `type`, `for_selling`, `quantity`, `selling`,`unit`) 
                                  VALUES ('','$name','$type','$forSelling','$quantity', '$selling','$unit')";
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute();

                $last_id = $em->getConnection()->lastInsertId();

                for($i=0;$i<count($ing_id);$i++){
                    $sql = "INSERT INTO `ingredients`(`id`,  `product_id`, `product_ingredient_id`, `quantity`)
                                  VALUES ('','$last_id','$ing_id[$i]','$ing_q[$i]')";
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();

                }
            }

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
     * @Route("/add-selling-bill", name="add_selling_bill")
     */
    public function insertSellingBillAction(){

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {

            $user = $request->get('user');
            $date2 = $request->get('date');
            $total = $request->get('total');
            $products = $request->get('products');
            $quantity = $request->get('quantity');
            $prices = $request->get('prices');
            $date=date('Y-m-d H:i:s',strtotime($date2));

                $sql = "INSERT INTO `bill`(`id`, `type`, `date`, `total`, `user`)
                                   VALUES ('',1, '$date' , '$total','$user')";
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute();

                $last_id = $em->getConnection()->lastInsertId();

                for($i=0;$i<count($products);$i++){
                    $sql = "INSERT INTO `selling`(`id`, `bill_id`, `product_id`, `quantity`, `price`)
                                  VALUES ('','$last_id','$products[$i]','$quantity[$i]','$prices[$i]')";
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();

                    echo $products[$i];
                    $prod = $em->getRepository('HotelBundle:Product')->findOneBy(array('id'=>$products[$i]));
                    if($prod->getType()==0){
                        $sql = "UPDATE `product` SET `quantity`=`quantity`-'$quantity[$i]' WHERE `id`='$products[$i]'";
                        $stmt = $em->getConnection()->prepare($sql);
                        $stmt->execute();
                    }else{
                        $prod = $em->getRepository('HotelBundle:Ingredients')->findBy(array('product'=>$products[$i]));
                        for($z=0;$z<count($prod);$z++){
                            $changed=$prod[$z]->getQuantity()*$quantity[$i];
                            $id_ch=$prod[$z]->getProductIngredientId();
                            $sql = "UPDATE `product` SET `quantity`=`quantity`-'$changed' WHERE `id`='$id_ch'";
                            $stmt = $em->getConnection()->prepare($sql);
                            $stmt->execute();
                        }

                    }

                }


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
     * @Route("/add-buying-bill", name="add_buying_bill")
     */
    public function insertBuyingBillAction(){

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {

            $user = $request->get('user');
            $date2 = $request->get('date');
            $total = $request->get('total');
            $products = $request->get('products');
            $quantity = $request->get('quantity');
            $prices = $request->get('prices');
            $date=date('Y-m-d H:i:s',strtotime($date2));

            $sql = "INSERT INTO `bill`(`id`, `type`, `date`, `total`, `user`)
                                   VALUES ('',0, '$date' , '$total','$user')";
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();

            $last_id = $em->getConnection()->lastInsertId();

            for($i=0;$i<count($products);$i++){
                $sql = "INSERT INTO `buying`(`id`, `bill_id`, `product_id`, `quantity`, `price`)
                                  VALUES ('','$last_id','$products[$i]','$quantity[$i]','$prices[$i]')";
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute();

                $sql = "UPDATE `product` SET `quantity`=`quantity`+'$quantity[$i]' WHERE `id`='$products[$i]'";
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute();

            }


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
     * @Route("/add-expense", name="add_expense")
     */
    public function insertExpenseAction(){

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {

            $expense = $request->get('expense');
            $value = $request->get('value_e');
            $user = $request->get('user');
            $date2 = $request->get('date');
            $date=date('Y-m-d H:i:s',strtotime($date2));

            $sql = "INSERT INTO `bill`(`id`, `type`, `date`, `total`, `user`,`explanation`)
                                   VALUES ('',3, '$date' , '$value','$user','$expense')";
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
     * @Route("/isnert-reservation", name="insert_reservation")
     */
    public function insertReservationAction(){

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()) {

            $room = $request->get('room');
            $name = $request->get('name');
            $surname = $request->get('surname');
            $per_nr = $request->get('per_nr');
            $date2 = $request->get('date');
            $sdate2= $request->get('starting');
            $edate2= $request->get('ending');
            $price = $request->get('price');
            $paid = $request->get('paid');
            $date=date('Y-m-d H:i:s',strtotime($date2));
            $sdate=date('Y-m-d',strtotime($sdate2));
            $edate=date('Y-m-d',strtotime($edate2));

            $sql = "INSERT INTO `booking`(`id`, `date`, `room_id`, `starting_date`, `ending_date`, `price`, `name`, `surname`, `id_nr`, `paid`)
                                   VALUES ('','$date', '$room' , '$sdate','$edate','$price','$name','$surname','$per_nr','$paid')";
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
