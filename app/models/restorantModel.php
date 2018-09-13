<?php
/**
 * Created by PhpStorm.
 * User: abdialam
 * Date: 9/10/18
 * Time: 21:14
 */

namespace App\Model;


final class restorantModel extends  BaseModel
{
    public function getAllRestorant (){
        $sql = "SELECT  a.*,b.* FROM m_restoran a,tr_pengguna b WHERE a.restoran_pemilik_phone = b.phone AND b.tipe = 'restoran'";;
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $arr=array("status"=>"1","data"=>$result);
        return $arr;
    }

    public function getAllKategori (){
        $sql = 'SELECT * FROM m_kategori';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $arr=array("status"=>"success","data"=>$result);
        return $arr;
    }

    public function  getAllMenuByIdRestaurant ($id_restoran = null){
        $id_restoran =$id_restoran;
        $sql = 'SELECT a.*,b.* FROM `m_menu` a , `m_kategori` b WHERE a.menu_restoran_id = :id_restoran AND a.menu_kategori_id = b.id_kategori';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id_restoran"=>$id_restoran]);
        $result = $stmt->fetchAll();
        $arr = array ("value"=>"1","data"=>$result);
        return $arr;
    }

}