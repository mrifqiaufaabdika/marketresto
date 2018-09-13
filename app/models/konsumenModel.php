<?php
/**
 * Created by PhpStorm.
 * User: abdialam
 * Date: 8/26/18
 * Time: 11:03
 */

namespace App\Model;


final class konsumenModel extends BaseModel
{

    public function getAllKonsumen (){
        $sql = 'SELECT * FROM M_KONSUMEN';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $arr=array(["status"=>"success","data"=>$result],200);
        return $arr;
    }


    public function viewKonsumen ($phone =null){
        $phone =$phone ;

        // $sql = "SELECT * from `m_konsumen` WHERE konsumen_phone =:phone";
        $sql = "SELECT  a.*,b.* FROM `m_konsumen`a,tr_pengguna b WHERE a.konsumen_phone =:phone AND b.phone = a.konsumen_phone AND b.tipe = 'konsumen'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":phone"=> $phone]);
        $result = $stmt->fetch();

        if ($stmt->rowCount() == 1){
            $this->msg=["value"=>"1", "message"=>"success","user"=>$result];
        }else{
            $this->msg=["value"=>"0", "message"=>"Oops! Nomor telepon tidak terdaftar "];
        }
        return $this->msg;
    }
}