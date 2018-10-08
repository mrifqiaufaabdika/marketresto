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


//############## SIGN UP KONSUMEN ################################################  OK
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
//########### SIGN IN KONSUMEN ##################################################  OK
    public function getSignIn (Request $request, Response $response,$args){

        $phone = $args["phone"];
        $data = $this->AuthModel->getSignIn($phone);
        return $response->withJson($data);
    }


//############## SIGN UP RESTORAN ################################################  OK
    public function setSignUpResto (Request $request, Response $response,$args){

        $new_resto = $request->getParsedBody();
        try{
            if(isset($new_resto)){
                $data = $this->AuthModel->signupResto($new_resto);
            }
        }catch (Exception $e){
            echo $e->getMessage();
            $this->logger->error($e->getMessage());
            die;
        }

        return $response->withJson($data);
    }

//############## SIGN UP RESTORAN - SET KATEGORI ################################################  OK
    public function setKategoriResto (Request $request, Response $response,$args){

        $new_resto = $request->getParsedBody();
        try{
            if(isset($new_resto)){
                $data = $this->AuthModel->setKategori($new_resto);
            }
        }catch (Exception $e){
            echo $e->getMessage();
            $this->logger->error($e->getMessage());
            die;
        }

        return $response->withJson($data);
    }


//########### SIGN IN RESTOPARTNER ##################################################  OK
    public function signInResto (Request $request, Response $response,$args){
        $phone = $args["phone"];
        $data = $this->AuthModel->signInResto($phone);
        return $response->withJson($data);
    }

//########## UPDATE TOKEN PENGGUNA ################################################### ok
    public function updateToken (Request $request,Response $response,$args){

        $data=[];
        $id_pengguna = $args["id_pengguna"];
        $token = $request->getParsedBody();
        try{
            if(isset($id_pengguna)){
                $data = $this->AuthModel->updateToken($id_pengguna,$token);
            }
        }catch (Exception $e){
            echo $e->getMessage();
            $this->logger->error($e->getMessage());
            die;
        }

        return $response->withJson($data);
    }



}