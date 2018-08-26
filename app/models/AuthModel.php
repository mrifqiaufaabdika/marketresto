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


    //method untuk melakukan validasi register pengguna baru
    public function signup ($new_konsumen = null){

        $konsumen = $new_konsumen ;
        $sql ="SElECT * FROM `m_konsumen` WHERE konsumen_Phone = :phone";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":phone"=>$konsumen["konsumen_phone"]]);

        if ($stmt->rowCount() > 0){ //kondisi telah ada pengguna
            $this->msg = ["value"=>"0", "message"=>"Oops! Nomor Telpon Genggam Sudah Terdaftar! "];
        }else {
            $sql = "INSERT INTO `m_konsumen`(`konsumen_phone`, `konsumen_nama`, `konsumen_email`, `konsumen_credit_balance`, `konsumen_baru`, `blacklist`) VALUES (:konsumen_phone, :konsumen_nama, :konsumen_email, :konsumen_credit_balance, :konsumen_baru, :blacklist)";
            $stmt = $this->db->prepare($sql);

            $data = [
                ":konsumen_phone" => $konsumen["konsumen_phone"],
                ":konsumen_nama" => $konsumen["konsumen_nama"],
                ":konsumen_email" => $konsumen["konsumen_email"],
                ":konsumen_credit_balance" => 0,
                ":konsumen_baru" => 1,
                ":blacklist" => 0,
            ];

            if ($stmt->execute($data)) {

                $this->msg = ["value"=>"1", "message"=>"Sukses Mendaftar"];
            } else {
                $this->msg = ["value"=>"0", "message"=>"Oops! Coba Lagi ! "];
            }
        }
        return $this->msg;
    }

    //method untuk melakukan validasi pengguna
    public function checkPhoneNumber($phone =null){
        $phone =$phone ;
        $sql = "SELECT konsumen_phone FROM m_konsumen WHERE konsumen_phone=:phone";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":phone"=> $phone]);
        $result = $stmt->fetch();

        if ($stmt->rowCount() == 1){
            $this->msg=["value"=>"1", "message"=>"success"];
        }else{
            $this->msg=["value"=>"0", "message"=>"Oops! Nomor Telpon Genggam Belum Terdaftar! "];
        }
        return $this->msg;
    }


    public function  generateApiKey(){
        $key = implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));

        return $key;
    }
}