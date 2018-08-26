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
    protected $cfgModel;

    public function __construct($logger,$cfgModel)
    {
        $this->logger = $logger;
        $this->cfgs= $cfgModel;
    }

    public function  getAllKonsumen (Request $request, Response $response, $args ){

        $data=[];
        $this->logger->info("Action:Mendapatkan seluruh konsumen");

        $data = $this->cfgs->getAllKonsumen();
        return $response->withJson($data);
    }
}