<?php
/**
 * Created by PhpStorm.
 * User: abdialam
 * Date: 9/20/18
 * Time: 09:41
 */

namespace App\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class orderController
{
    protected $logger;
    protected $orderModel;

    public function __construct($logger, $orderModel)
    {
        $this->logger = $logger;
        $this->orderModel = $orderModel;
    }

    // send and set order
    public function send (Request $request,Response $response,$args){
        $data = $request->getParsedBody();

        try{
            if(isset($data)){
                 $result =$this->orderModel->order($data);

            }
        }catch (\Exception $e){
            echo $e->getMessage();
            $this->logger->error($e->getMessage());
            die;
        }

       return $response->withJson($result);

    }

//set detail pesanan
    public function setPesanDetail (Request $request, Response $response,$args){

        $new_order = $request->getParsedBody();
        try{
            if(isset($new_order)){
                $data = $this->orderModel->setPesananDetail($new_order);
            }
        }catch (Exception $e){
            echo $e->getMessage();
            $this->logger->error($e->getMessage());
            die;
        }

        return $response->withJson($data);
    }


    //Get detail pesanan
    public function getPesanDetail (Request $request, Response $response,$args){
        $pesan_id = $args['pesan_id'];
        try{
            if(isset($pesan_id)){
                $data = $this->orderModel->getPesananDetail($pesan_id);
            }
        }catch (Exception $e){
            echo $e->getMessage();
            $this->logger->error($e->getMessage());
            die;
        }

        return $response->withJson($data);
    }


//    get token
    public function getToken (Request $request,Response $response,$args){
        $data = $request->getParsedBody();


        try{
            if(isset($data)){
                $result =$this->orderModel->getTokenByPhone($data['phone'],$data['id_restoran']);

            }
        }catch (\Exception $e){
            echo $e->getMessage();
            $this->logger->error($e->getMessage());
            die;
        }

        return $response->withJson($result);

    }


    //get order by id restoran
    public function getOrderByRestoran (Request $request,Response $response,$args){
        $data = $args['id_restoran'];

        try{
            if(isset($data)){
                $result =$this->orderModel->getOrderByRestoran($data);
            }
        }catch (\Exception $e){
            echo $e->getMessage();
            $this->logger->error($e->getMessage());
            die;
        }

        return $response->withJson($result);
    }


    //get order delivery by id restoran
    public function getOrderByRestoranDelivery (Request $request,Response $response,$args){
        $data = $args['id_restoran'];

        try{
            if(isset($data)){
                $result =$this->orderModel->getOrderByRestoranDelivery($data);
            }
        }catch (\Exception $e){
            echo $e->getMessage();
            $this->logger->error($e->getMessage());
            die;
        }

        return $response->withJson($result);
    }


//get order by id restoran
    public function getOrderByKonsumen (Request $request,Response $response,$args){
        $data = $args['id_konsumen'];

        try{
            if(isset($data)){
                $result =$this->orderModel->getOrderByKonsumen($data);
            }
        }catch (\Exception $e){
            echo $e->getMessage();
            $this->logger->error($e->getMessage());
            die;
        }

        return $response->withJson($result);
    }
}