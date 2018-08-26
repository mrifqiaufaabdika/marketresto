<?php
/**
 * Created by PhpStorm.
 * User: abdialam
 * Date: 8/26/18
 * Time: 11:03
 */

namespace App\Model;


final class konsumen extends BaseModel
{

    public function getAllKonsumen (){
        $sql = 'SELECT * FROM M_KONSUMEN';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $arr=array(["status"=>"success","data"=>$result],200);
        return $arr;
    }
}