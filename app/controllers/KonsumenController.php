<?php
/**
 * Created by PhpStorm.
 * User: abdialam
 * Date: 8/26/18
 * Time: 11:25
 */

namespace App\Controller;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class KonsumenController
{
    protected $logger;
    protected $konsumenModel;

    public function __construct($logger,$Model)
    {
        $this->logger = $logger;
        $this->konsumenModel= $Model;
    }


    public function viewKonsumen (Request $request,Response $response, $args){
        $phone = $args["phone"];


        $data = $this->konsumenModel->viewKonsumen($phone);
        return $response->withJson($data);


    }

    public function  getAllKonsumen (Request $request, Response $response, $args ){

        $data=[];
        $this->logger->info("Action:Mendapatkan seluruh konsumen");

        $data = $this->konsumenModel->getAllKonsumen();
        return $response->withJson($data);
    }
}