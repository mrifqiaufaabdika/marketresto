<?php
/**
 * Created by PhpStorm.
 * User: abdialam
 * Date: 9/20/18
 * Time: 09:15
 */

namespace App\Model;


final class orderModel extends BaseModel
{

    protected $msg = [];

//send & set order
    public function order ($data = null){
        $new_data = $data;

        $push = new push($new_data['title'],$new_data['message']);

        $mPushNotification = $push->getPush();
        $deviceToken = $this->getTokenByPhone($new_data['restoran_phone']);

        $fb = new fb();

        $msg =  ($fb->send($deviceToken,$mPushNotification));


        $sql  = "INSERT INTO `tr_pesan`(`konsumen_id`, `restoran_id`, `pesan_lokasi`, `pesan_alamat`, `pesan_catatan`, `pesan_metode_bayar`, `jarak_antar`, `pesan_biaya_antar`, `pesan_status`) VALUES (:konsumen_id,:restoran_id,:pesan_lokasi,:pesan_alamat,:pesan_catatan,:pesan_metode_bayar,:jarak_antar,:pesan_biaya_antar,:pesan_status)";
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':konsumen_id',$new_data['konsumen_id']);
        $stmt->bindValue(':restoran_id',$new_data['restoran_id']);
        $stmt->bindValue(':pesan_lokasi',$new_data['pesan_lokasi']);
        $stmt->bindValue(':pesan_alamat',$new_data['pesan_alamat']);
        $stmt->bindValue(':pesan_catatan',$new_data['pesan_catatan']);
        $stmt->bindValue(':pesan_metode_bayar',$new_data['pesan_metode_bayar']);
        $stmt->bindValue(':jarak_antar',$new_data['jarak_antar']);
        $stmt->bindValue(':pesan_biaya_antar',$new_data['pesan_biaya_antar']);
        $stmt->bindValue(':pesan_status',$new_data['pesan_status']);
        $stmt->execute();
        $id = $this->db->lastInsertId();
        $arr = array("id"=>$id,"message"=>json_decode($msg),$deviceToken);


        return($arr);
    }


    //    set detail pesanan
    public function setPesananDetail($detail= null){
        $new_detail = $detail;
        $sql ="INSERT INTO `tr_pesan_detail`(`pesan_id`, `menu_id`, `harga`, `qty`, `catatan`) VALUES (:pesan_id,:menu_id,:harga,:qty,:catatan)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':pesan_id',$new_detail['pesan_id']);
        $stmt->bindValue(':menu_id',$new_detail['menu_id']);
        $stmt->bindValue(':harga',$new_detail['harga']);
        $stmt->bindValue(':qty',$new_detail['qty']);
        $stmt->bindValue(':catatan',$new_detail['catatan']);
        $stmt->execute();
        $arr=array("status"=>"1", "message"=>"Sukses Mendaftar");
        return $arr;
    }

//get Token by phone
    public function getTokenByPhone($phone = null){
        $phone = $phone;
        $restoran = "restoran";

        $sql ="SELECT token FROM `tr_pengguna` WHERE phone =:phone AND tipe='restoran'";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([":phone"=>$phone]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $clearToken = preg_replace("!\r?\n!", "", $result['token']);
        return array($clearToken);


    }

//    get order by id
    public function getOrderByRestoran ($id_restoran = null){
        $arr=[];
        $id_restoran =$id_restoran;
        $sql = "SELECT  a.*,b.* FROM tr_pesan a,m_konsumen b WHERE a.restoran_id=:id_restoran AND a.konsumen_id = b.id_konsumen AND pesan_status='proses' ORDER BY pesan_waktu DESC ";;
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id_restoran"=>$id_restoran]);
        $result = $stmt->fetchAll();

        for ($x = 0 ; $x < $stmt->rowCount();$x++){
            $result[$x]['detailorder']= $this->getPesananDet($result[$x]['id_pesan']);
        }


        if ($stmt->rowCount() > 0) { //kondisi telah ada pesanan
            $arr = array("value" => "1","message"=>"Anda Memiliki Pesanan", "jumlah_pesan"=>$stmt->rowCount(),"pesan" => $result);
        }elseif ($stmt->rowCount()==0){
            $arr = array("value" => "0","pesanan"=>$stmt->rowCount(),"message"=>"Anda Tidak Memiliki Pesanan");
        }
        return $arr;
    }


    //    get order by id
    public function getOrderByRestoranDelivery ($id_restoran = null){
        $arr=[];
        $id_restoran =$id_restoran;
        $sql = "SELECT  a.*,b.* FROM tr_pesan a,m_konsumen b WHERE a.restoran_id=:id_restoran AND a.konsumen_id = b.id_konsumen AND pesan_status='pengantaran' ORDER BY pesan_waktu DESC ";;
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id_restoran"=>$id_restoran]);
        $result = $stmt->fetchAll();

        for ($x = 0 ; $x < $stmt->rowCount();$x++){
            $result[$x]['detailorder']= $this->getPesananDet($result[$x]['id_pesan']);
        }


        if ($stmt->rowCount() > 0) { //kondisi telah ada pesanan
            $arr = array("value" => "1","message"=>"Anda Memiliki Pesanan", "jumlah_pesan"=>$stmt->rowCount(),"pesan" => $result);
        }elseif ($stmt->rowCount()==0){
            $arr = array("value" => "0","pesanan"=>$stmt->rowCount(),"message"=>"Anda Tidak Memiliki Pesanan");
        }
        return $arr;
    }





//    //    get order by id
//    public function getPesananDetail ($id_pesan = null){
//        $arr=[];
//        $id_pesan =$id_pesan;
//        $sql = "SELECT a.*,b.menu_nama,(a.harga*a.qty)AS jumlah FROM `tr_pesan_detail` a, `m_menu` b WHERE a.pesan_id =:id_pesan AND a.menu_id = b.id_menu ORDER BY b.menu_nama ";
//        $stmt = $this->db->prepare($sql);
//        $stmt->execute([":id_pesan"=>$id_pesan]);
//        $result = $stmt->fetchAll();
//        if ($stmt->rowCount() > 0) { //kondisi telah ada pesanan
//            $arr = array("value" => "1","message"=>"Anda Memiliki Pesanan", "pesanan"=>$stmt->rowCount(),"data" => $result);
//        }elseif ($stmt->rowCount()==0){
//            $arr = array("value" => "0","messge"=>"Anda Tidak Memiliki Pesanan","pesanan"=>$stmt->rowCount());
//        }
//        return $arr;
//    }

    //    get order by id
    public function getOrderByKonsumen ($id = null){
        $arr=[];
        $id_konsumen =$id;
        $sql = "SELECT  a.*,b.id_restoran,b.restoran_nama,b.restoran_phone FROM tr_pesan a,m_restoran b WHERE a.restoran_id = b.id_restoran AND a.konsumen_id =:id_konsumen AND a.pesan_status IN ('proses', 'pengantaran') ORDER BY a.pesan_waktu DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id_konsumen"=>$id_konsumen]);
        $result = $stmt->fetchAll();

        for ($x = 0 ; $x < $stmt->rowCount();$x++){
            $result[$x]['detailpesan']= $this->getPesananDet($result[$x]['id_pesan']);
        }

        if ($stmt->rowCount() > 0) { //kondisi telah ada pesanan

            $arr = array("value" => "1","messge"=>"Memiliki Pesanan","pesan"=>$result);
        }elseif ($stmt->rowCount()==0){
            $arr = array("value" => "0","messge"=>"Anda Tidak Memiliki Pesanan");
        }
        return $arr;
    }





    //    get order by id
    public function getPesananDet ($id_pesan = null){
        $arr=[];
        $id_pesan =$id_pesan;
        $sql = "SELECT a.*,b.menu_nama,(a.harga*a.qty)AS jumlah FROM `tr_pesan_detail` a, `m_menu` b WHERE a.pesan_id =:id_pesan AND a.menu_id = b.id_menu ORDER BY b.menu_nama ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id_pesan"=>$id_pesan]);
        $result = $stmt->fetchAll();

        return $result;
    }





}