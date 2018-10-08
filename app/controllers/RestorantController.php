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
use Slim\Http\UploadedFile;

final class RestorantController
{
    protected $logger;
    protected $restorantModel;

    public function __construct($logger,$restorantModel)
    {
        $this->logger =$logger;
        $this->restorantModel = $restorantModel;
    }

//get All Restoran
    public function getAllResto (Request $request,Response $response, $args){
        $data =[];
        $data = $this->restorantModel->getAllRestorant();
        return $response->withJson($data);
    }


//  get restoran by kategori
    public function getRestorantByID (Request $request,Response $response, $args){
        $id_restoran = $args["id_restoran"];
        $data =[];
        $data = $this->restorantModel->getRestorantByID($id_restoran);
        return $response->withJson($data);
    }

//get All Kategori
    public function getAllKategori (Request $request,Response $response, $args){


        $data =[];
        $data = $this->restorantModel->getAllKategori();
        return $response->withJson($data);
    }

//    get All Menu By ID Restoran
    public function getAllMenuByIdRestaurant (Request $request,Response $response, $args){
        $id_restoran = $args["id_restoran"];
        $id_konsumen = $request->getQueryParams();
        $data =[];
        $data = $this->restorantModel->getAllMenuByIdRestaurant($id_restoran,$id_konsumen);
        return $response->withJson($data);
    }



//ambil menu berdasarkan kategori
    public function getMenuKategoriByResto (Request $request, Response $response,$args){
        $id_restoran = $args["id_restoran"];
        $data =[];
        $data = $this->restorantModel->getMenuKategoriByResto($id_restoran);
        return $response->withJson($data);
    }

//tambah menu
    public function addMenu (Request $request,Response $response,$args){

        $data =[];
        $new_menu = $request->getParsedBody();
        $image = $request->getUploadedFiles();
        try{
            if(isset($image)&&isset($new_menu)){
                $data = $this->restorantModel->addMenu($image,$new_menu);
            }
        }catch (Exception $e){
            echo $e->getMessage();
            $this->logger->error($e->getMessage());
            die;
        }

        return $response->withJson($data);
    }

//Update oprasional restoran
    public function putOperasionalRestoran (Request $request,Response $response, $args){
        $data=[];
        $id_restoran = $args["id_restoran"];
        $operasional = $request->getParsedBody();
        try{
            if(isset($id_restoran )){
                $data = $this->restorantModel->putOperasionalRestoran($id_restoran,$operasional);
            }
        }catch (Exception $e){
            echo $e->getMessage();
            $this->logger->error($e->getMessage());
            die;
        }

        return $response->withJson($data);
    }
//update ketersedian menu
    public  function  updateKetersedianMenu (Request $request,Response $response, $args){
        $data=[];
        $id_restoran = $args["id_menu"];
        $menu = $request->getParsedBody();
        try{
            if(isset($id_restoran )){
                $data = $this->restorantModel->updateKetersedianMenu($id_restoran,$menu);
            }
        }catch (Exception $e){
            echo $e->getMessage();
            $this->logger->error($e->getMessage());
            die;
        }

        return $response->withJson($data);
    }


//menambah Kurir
    public function addKurir (Request $request,Response $response,$args){

        $data =[];
        $new_kurir = $request->getParsedBody();

        try{
            if(isset($new_kurir)){
                $data = $this->restorantModel->addKurir($new_kurir);
            }
        }catch (Exception $e){
            echo $e->getMessage();
            $this->logger->error($e->getMessage());
            die;
        }

        return $response->withJson($data);
    }


    //ambil menu berdasarkan kategori
    public function getKurir (Request $request, Response $response,$args){
        $id_restoran = $args["id_restoran"];
        $data =[];
        $data = $this->restorantModel->getKurir($id_restoran);
        return $response->withJson($data);
    }

    public function setMenuFavorit (Request $request,Response $response, $args){
        $data =[];
        $new_favorit = $request->getParsedBody();

        try{
            if(isset($new_favorit)){
                $data = $this->restorantModel->setMenuFavorit($new_favorit);
            }
        }catch (Exception $e){
            echo $e->getMessage();
            $this->logger->error($e->getMessage());
            die;
        }

        return $response->withJson($data);
    }


    //ambil menu favorit
    public function getMenuFavorit (Request $request, Response $response,$args){
        $id_konsumen = $args["id_konsumen"];
        $data =[];
        $data = $this->restorantModel->getMenuFavorit($id_konsumen);
        return $response->withJson($data);
    }



}