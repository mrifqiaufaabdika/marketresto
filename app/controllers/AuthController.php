<?php
/**
 * Created by PhpStorm.
 * User: abdialam
 * Date: 8/26/18
 * Time: 14:24
 */

namespace App\Controller;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
final class AuthController

{
    protected $logger;
    protected $AuthModel;

    public function __construct($logger, $AuthModel)
    {
        $this->logger = $logger;
        $this->AuthModel = $AuthModel;
    }

    public function setSignUp (Request $request, Response $response,$args){

        $new_konsumen = $request->getParsedBody();
        try{
            if(isset($new_konsumen)){
                $data = $this->AuthModel->signup($new_konsumen);
            }
        }catch (\Exception $e){
            echo $e->getMessage();
            $this->logger->error($e->getMessage());
            die;
        }

        return $response->withJson($data);
    }


    public function checkPhoneNumber (Request $request, Response $response,$args){

        $phone = $args["phone"];
        $data = $this->AuthModel->checkPhoneNumber($phone);
        return $response->withJson($data);
    }
}