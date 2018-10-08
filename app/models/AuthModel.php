<?php
/**
 * Created by PhpStorm.
 * User: abdialam
 * Date: 8/26/18
 * Time: 14:23
 */

namespace App\Model;


final class AuthModel extends BaseModel
{
    //property
    protected $msg = [];


//############## SIGN UP KONSUMEN ################################################  OK
    public function signup ($new_konsumen = null){

        $konsumen = $new_konsumen ;
        $sql ="SElECT * FROM `m_konsumen` WHERE konsumen_Phone = :phone";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":phone"=>$konsumen["konsumen_phone"]]);

        if ($stmt->rowCount() > 0){ //kondisi telah ada pengguna
            $this->msg = ["value"=>"0", "message"=>"Oops! Nomor Telpon Genggam Sudah Terdaftar! "];
        }else {
            $db = $this->db;
            try{

                $db->beginTransaction();

                $sql = "INSERT INTO `m_konsumen`(`konsumen_phone`, `konsumen_nama`, `konsumen_email`, `konsumen_credit_balance`, `konsumen_baru`, `blacklist`) VALUES (:konsumen_phone, :konsumen_nama, :konsumen_email, :konsumen_credit_balance, :konsumen_baru, :blacklist)";
                $stmt = $this->db->prepare($sql);

                $stmt->bindValue(':konsumen_phone',$konsumen['konsumen_phone']);
                $stmt->bindValue(':konsumen_nama',$konsumen['konsumen_nama']);
                $stmt->bindValue(':konsumen_email',$konsumen['konsumen_email']);
                $stmt->bindValue(':konsumen_credit_balance',0);
                $stmt->bindValue(':konsumen_baru',1);
                $stmt->bindValue(':blacklist',0);

                $stmt->execute();

                $sql2 ="INSERT INTO `tr_pengguna`(`phone`,`token`, `tipe`) VALUES (:konsumen_phone,:token,:tipe)";
                $stmt = $this->db->prepare($sql2);
                $stmt->bindValue(':konsumen_phone',$konsumen['konsumen_phone']);
                $stmt->bindValue(':token',$konsumen['token']);
                $stmt->bindValue(':tipe',$konsumen['tipe']);
                $stmt->execute();
                $this->db->commit();

                $this->msg = ["value"=>"1", "message"=>"Sukses Mendaftar"];



            }catch (PDOException $e){
                $db->rollback();
                $this->msg = ["value"=>"0", "message"=>"Oops! Coba Lagi!"];

            }

        }
        return $this->msg;
    }

//############## SIGN IN KONSUMEN ################################################  OK
    public function getSignIn($phone =null){
        $phone =$phone ;

       // $sql = "SELECT * from `m_konsumen` WHERE konsumen_phone =:phone";
        $sql = "SELECT  a.*,b.* FROM `m_konsumen`a,tr_pengguna b WHERE a.konsumen_phone =:phone AND b.phone = a.konsumen_phone AND b.tipe = 'konsumen'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":phone"=> $phone]);
        $result = $stmt->fetch();

        if ($stmt->rowCount() == 1){
            $this->msg=["value"=>"1", "message"=>"success","user" => $result];
        }else{
            $this->msg=["value"=>"0", "message"=>"Oops! Nomor telepon tidak terdaftar "];
        }
        return $this->msg;
    }


//############## SIGN UP RESTORAN ################################################  OK
    public function signupResto ($resto = null){

        $new_resto = $resto ;
        $sql ="SELECT * FROM `tr_pengguna` WHERE phone = :phone AND tipe IN ('restoran','kurir')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":phone"=>$new_resto["restoran_pemilik_phone"]]);

        if ($stmt->rowCount() > 0){ //kondisi telah ada pengguna
            $this->msg = ["value"=>"0", "message"=>"Oops! Nomor Telpon  Sudah Terdaftar! "];
        }else {
            $db = $this->db;
            try{

                $db->beginTransaction();

                $sql = "INSERT INTO `m_restoran`(`id_restoran`, `restoran_nama`, `restoran_phone`, `restoran_email`, `restoran_alamat`, `restoran_lokasi`, `restoran_deskripsi`, `restoran_gambar`, `restoran_operasional`, `restoran_pemilik_nama`, `restoran_pemilik_phone`, `restoran_pemilik_email`, `restoran_balance`, `restoran_delivery`, `tarif_delivery`, `restoran_delivery_jarak`, `restoran_delivery_minimum`) VALUES (NULL,:restoran_nama,:restoran_phone,:restoran_email,:restoran_alamat,:restoran_lokasi,:restoran_deskripsi,:restoran_gambar,:restoran_operasional,:restoran_pemilik_nama, :restoran_pemilik_phone, :restoran_pemilik_email,:restoran_balance,:restoran_delivery,:tarif_delivery,:restoran_delivery_jarak,:restoran_delivery_minimum)";
                $stmt = $this->db->prepare($sql);

                $stmt->bindValue(':restoran_nama',$new_resto['restoran_nama']);
                $stmt->bindValue(':restoran_phone',$new_resto['restoran_phone']);
                $stmt->bindValue(':restoran_email',$new_resto['restoran_email']);
                $stmt->bindValue(':restoran_alamat',$new_resto['restoran_alamat']);
                $stmt->bindValue(':restoran_lokasi',$new_resto['restoran_lokasi']);
                $stmt->bindValue(':restoran_deskripsi',$new_resto['restoran_deskripsi']);
                $stmt->bindValue(':restoran_gambar',$new_resto['restoran_gambar']);
                $stmt->bindValue(':restoran_operasional',1);
                $stmt->bindValue(':restoran_pemilik_nama',$new_resto['restoran_pemilik_nama']);
                $stmt->bindValue(':restoran_pemilik_phone',$new_resto['restoran_pemilik_phone']);
                $stmt->bindValue(':restoran_pemilik_email',$new_resto['restoran_pemilik_email']);
                $stmt->bindValue(':restoran_balance',0);
                $stmt->bindValue(':restoran_delivery',$new_resto['restoran_delivery']);
                $stmt->bindValue(':tarif_delivery',$new_resto['tarif_delivery']);
                $stmt->bindValue(':restoran_delivery_jarak',$new_resto['restoran_delivery_jarak']);
                $stmt->bindValue(':restoran_delivery_minimum',$new_resto['restoran_delivery_minimum']);

                $stmt->execute();
                $id = $this->db->lastInsertId();
                $sql2 ="INSERT INTO `tr_pengguna`(`phone`,`token`, `tipe`) VALUES (:konsumen_phone,:token,:tipe)";
                $stmt = $this->db->prepare($sql2);
                $stmt->bindValue(':konsumen_phone',$new_resto['restoran_pemilik_phone']);
                $stmt->bindValue(':token',$new_resto['token']);
                $stmt->bindValue(':tipe',$new_resto['tipe']);
                $stmt->execute();

                $this->db->commit();
                $this->msg = ["value"=>"1", "message"=>"Sukses Mendaftar","ID"=>$id];


            }catch (PDOException $e){
                $db->rollback();
                $this->msg = ["value"=>"0", "message"=>"Oops! Coba Lagi ! "];

            }

        }
        return $this->msg;
    }

//############## SIGN UP RESTORAN - SET KATEGORI RESTORAN ################################################  OK
    public function setKategori($kat= null){
        $kategori = $kat;
        $sql ="INSERT INTO `tr_kategori_restoran`(`restoran_id`, `kategori_id`) VALUES (:restoran_id,:kategori_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':restoran_id',$kategori['restoran_id']);
        $stmt->bindValue(':kategori_id',$kategori['kategori_id']);
        $stmt->execute();
        $this->msg =["status"=>"1", "message"=>"Sukses Mendaftar"];
        return $this->msg;
    }


//########### SIGN IN RESTOPARTNER ##################################################  OK
    public function signInResto($phone =null){
        $phone =$phone ;



        $sql ="SELECT * FROM `tr_pengguna` WHERE phone = :phone AND tipe IN ('restoran','kurir')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":phone"=>$phone]);
        $result = $stmt->fetchAll();

        if($stmt->rowCount()==0){
            $this->msg=["value"=>"0","message"=>"Oops! Nomor telepon tidak terdaftar "];
        }else if($stmt->rowCount() > 0){

            $tabel = $result[0]['tipe'];

            if($tabel == "restoran"){
                $tabel = "m_"+$tabel;
                $sql = "SELECT  a.*,b.* FROM `m_restoran` a,tr_pengguna b WHERE a.restoran_pemilik_phone =:phone AND b.phone = a.restoran_pemilik_phone AND b.tipe = 'restoran'";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([":phone"=> $phone]);
                $hasil = $stmt->fetch();

                $this->msg=["value"=>"1","message"=>"success","tipe"=>"restoran","restoran"=>$hasil];
            }else if($tabel == "kurir"){
                $tabel = "m_"+$tabel;
                $sql = "SELECT a.*,b.* FROM `m_kurir`  a,tr_pengguna b WHERE a.kurir_phone =:phone AND b.phone = a.kurir_phone AND b.tipe = 'kurir'";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([":phone"=> $phone]);
                $hasil = $stmt->fetch();

                $this->msg=["value"=>"1","message"=>"success","tipe"=>"kurir", "Kurir"=>$hasil];
            }else{

            }


        }

        return $this->msg;


    }


########### UPDATE TOKEN PENGGUNA #############################################       #OK
    public function updateToken ($id_pengguna = null,$token=null){

        $id_pengguna =$id_pengguna;
        $new_token = $token;
        $sql = "UPDATE tr_pengguna SET `token`=:token WHERE id_pengguna =:id_pengguna";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':token',$token["token"]);
        $stmt->bindValue(':id_pengguna',$id_pengguna);

        if($stmt->execute()){
            return $this->msg = ["value"=>"1" , "message" => "Operasional Berhasil Di Perbarui",];
        }else{
            return $this->msg = ["value"=>"0" , "message" => "Operasional Gagal Di Perbarui"];
        }


    }


}