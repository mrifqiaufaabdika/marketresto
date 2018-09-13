<?php
/**
 * Created by PhpStorm.
 * User: abdialam
 * Date: 9/10/18
 * Time: 21:07
 */

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class RestorantController
{
    protected $logger;
    protected $restorantModel;

    public function __construct($logger,$restorantModel)
    {
        $this->logger =$logger;
        $this->restorantModel = $restorantModel;
    }

    public function getAllResto (Request $request,Response $response, $args){
        $data =[];
        $data = $this->restorantModel->getAllRestorant();
        return $response->withJson($data);
    }


    public function getAllKategori (Request $request,Response $response, $args){


        $data =[];
        $data = $this->restorantModel->getAllKategori();
        return $response->withJson($data);
    }

    public function getAllMenuByIdRestaurant (Request $request,Response $response, $args){
        $id_restoran = $args["id_restoran"];
        $data =[];
        $data = $this->restorantModel->getAllMenuByIdRestaurant($id_restoran);
        return $response->withJson($data);
    }


}