<?php
/**
 * Created by PhpStorm.
 * User: abdialam
 * Date: 9/10/18
 * Time: 21:14
 */

namespace App\Model;


final class restorantModel
{


    protected $db;
    protected $settings;

    public function __construct($db,$settings)
    {
        $this->db = $db;
        $this->settings=$settings;
    }
    //property
    protected $msg = [];

//    Get All Restoran
    public function getAllRestorant (){
        $sql = "SELECT  a.*,b.*,(SELECT count(*) FROM tr_pesan WHERE restoran_id=a.id_restoran AND  pesan_status = 'selesai') AS jumlah_pesan FROM m_restoran a,tr_pengguna b WHERE a.restoran_pemilik_phone = b.phone AND b.tipe = 'restoran'  ORDER  BY jumlah_pesan DESC ";;
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();



        $arr=array("status"=>"1","data"=>$result);
        return $arr;
    }



//    Get All Restoran By ID Restoran
    public function getRestorantByID ($id_restoran = null){
        $id_restoran =$id_restoran;
        $sql = "SELECT  a.*,b.* FROM m_restoran a,tr_pengguna b WHERE a.id_restoran=:id_restoran AND a.restoran_pemilik_phone = b.phone AND b.tipe = 'restoran'";;
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id_restoran"=>$id_restoran]);
        $result = $stmt->fetchAll();

        $arr=array("status"=>"1","data"=>$result);
        return $arr;
    }

//    get All Kategori
    public function getAllKategori (){
        $sql = 'SELECT * FROM m_kategori ORDER BY kategori_nama ASC' ;
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $arr=array("status"=>"success","data"=>$result);
        return $arr;
    }


    //    get Kategori by id restoran
    public function getMenuKategoriByResto ($id_restoran = null){
        $id_restoran = $id_restoran;
        $sql = 'SELECT b.* FROM tr_kategori_restoran a, m_kategori b WHERE a.restoran_id = :id_restoran AND a.kategori_id =b.id_kategori' ;
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id_restoran"=>$id_restoran]);
        $result = $stmt->fetchAll();
        $menu = $this->getMenuRestoran($id_restoran);
        $arr=array("status"=>"success","kategori"=>$result,"menu"=>$menu);
        return $arr;
    }


    //get menu by id resto
    public function  getMenuRestoran ($id_restoran = null){
        $id_restoran =$id_restoran;
        $sql = 'SELECT a.*,b.*,(SELECT  count(*) FROM tr_favorite where menu_id = a.id_menu) AS Favorit FROM `m_menu` a , `m_kategori` b WHERE a.menu_restoran_id =:id_restoran AND a.menu_kategori_id = b.id_kategori';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id_restoran"=>$id_restoran]);
        $result = $stmt->fetchAll();
        return $result;
    }

//    get All menu dari id restoran
    public function  getAllMenuByIdRestaurant ($id_restoran = null,$konsumen_id = null){
        $id_restoran =$id_restoran;
        $konsumen_id = $konsumen_id['id_konsumen'];
        $sql = 'SELECT a.*,b.*,(SELECT  count(*) FROM tr_favorite where menu_id = a.id_menu AND konsumen_id=:id_konsumen) AS Favorit FROM `m_menu` a , `m_kategori` b WHERE a.menu_restoran_id = :id_restoran AND a.menu_kategori_id = b.id_kategori';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id_restoran"=>$id_restoran,":id_konsumen"=>$konsumen_id]);

        $result = $stmt->fetchAll();
        $kategori = $this->getAllMenuRestoranByKategori($id_restoran);

        

        $arr = array ("value"=>"1","kategori"=>$kategori,"data"=>$result);
        return $arr;
    }

//    Get all menu by kategori
    public  function  getAllMenuRestoranByKategori($id_restoran = null){
        $id_restoran =$id_restoran;
        $sql = 'SELECT a.menu_kategori_id,b.kategori_nama , b.kategori_deskripsi, count(*) AS jumlah_menu FROM m_menu a,m_kategori b WHERE menu_restoran_id =:id_restoran AND a.menu_kategori_id = b.id_kategori GROUP BY menu_kategori_id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id_restoran"=>$id_restoran]);
        $result = $stmt->fetchAll();
        $arr = $result;
        return $arr;
    }


    public function getAllPesananDetail (){
        $sql = 'SELECT b.*, a.*  FROM `tr_pesan_detail` a, m_menu b WHERE a.menu_id = b.id_menu';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $arr=array("status"=>"success","data"=>$result);
        return $arr;
    }

    public function setKategori($kat= null){
        $kategori = $kat;
        $sql ="INSERT INTO `tr_kategori_restoran`(`restoran_id`, `kategori_id`) VALUES (:restoran_id,:ketegori_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':restoran_id',$kategori['restoran_id']);
        $stmt->bindValue(':ketegori_id ',$kategori['ketegori_id ']);
        $stmt->execute();
        $arr=array("status"=>"1", "message"=>"Sukses Mendaftar");
        return $arr;
    }


    //add menu
    public function  addMenu($images=null,$menu =null){
        $image = $images;
        $new_menu = $menu;

        $newImage =$image['file'];





        if($newImage->getError() === UPLOAD_ERR_OK) {
//            $uploadFileName = $newImage->getClientFilename();
//            $type=$newImage->getClientMediaType();
            $name = uniqid('img-' . date('Ymd') . '-');
//            $name.=$newImage->getClientFilename();
            //  $img[] = array('url'=>'/upload/'.$name);
            $extension = pathinfo($newImage->getClientFilename(), PATHINFO_EXTENSION);
            $filename = sprintf('%s.%0.8s', $name, $extension);

            $directory =$this->settings;
            $dir = __DIR__.'upload';


            $newImage->moveTo($directory.DIRECTORY_SEPARATOR.$filename);


            $sql = "INSERT INTO `m_menu`( `menu_restoran_id`, `menu_kategori_id`, `menu_nama`, `menu_deskripsi`, `menu_harga`, `menu_gambar`, `menu_ketersedian`)  VALUES (:menu_restoran_id,:menu_kategori_id,:menu_nama,:menu_deskripsi,:menu_harga,:menu_gambar,:menu_ketersedian)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':menu_restoran_id', $new_menu['menu_restoran_id']);
            $stmt->bindValue(':menu_kategori_id', $new_menu['menu_kategori_id']);
            $stmt->bindValue(':menu_nama', $new_menu['menu_nama']);
            $stmt->bindValue(':menu_deskripsi', $new_menu['menu_deskripsi']);
            $stmt->bindValue(':menu_harga', $new_menu['menu_harga']);
            $stmt->bindValue(':menu_gambar', $filename);
            $stmt->bindValue(':menu_ketersedian', 1);
            $stmt->execute();
            $this->msg = ["status" => "1", "message" => "Sukses Mendaftar"];
            return $this->msg;

        }


    }

    public function putOperasionalRestoran ($id_restorann = null,$operasionall=null){

        $id_restoran =$id_restorann;
        $operasional = $operasionall;
        $sql = "UPDATE m_restoran SET restoran_operasional =:operasional WHERE id_restoran =:id_restoran";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':operasional',$operasional['operasional']);
        $stmt->bindValue(':id_restoran',$id_restoran);
        if($stmt->execute()){
            return $this->msg = ["status"=>"1" , "message" => "Operasional Berhasil Di Perbarui"];
        }else{
           return $this->msg = ["status"=>"0" , "message" => "Operasional Gagal Di Perbarui"];
        }


    }

    public function  updateKetersedianMenu ($id_menu = null,$ketersedian=null){
        $id_menu =$id_menu;
        $ketersedian = $ketersedian;
        $sql = "UPDATE m_menu SET menu_ketersedian =:ketersedian WHERE id_menu =:id_menu";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':ketersedian',$ketersedian['ketersedian']);
        $stmt->bindValue(':id_menu',$id_menu);
        if($stmt->execute()){
            return $this->msg = ["status"=>"1" , "message" => "Ketersedian Berhasil Di Perbarui"];
        }else{
            return $this->msg = ["status"=>"0" , "message" => "Ketersedian Gagal Di Perbarui"];
        }
    }

    public function getMenuFavorit ($id_konsumen = null){

        $id_konsumen = $id_konsumen;
        $sql= "SELECT a.*,b.menu_nama,b.menu_harga,c.restoran_nama  FROM `tr_favorite` a, m_menu b,m_restoran c WHERE a.konsumen_id=:id_konsumen  AND a.menu_id =b.id_menu AND b.menu_restoran_id = c.id_restoran";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id_konsumen"=>$id_konsumen]);
        $result = $stmt->fetchAll();

        if($stmt->rowCount()>0){
            $this->msg = ["value"=>"1", "message"=>"Anda Memiliki Favorit","favorit"=>$result];
        }else{
            $this->msg = ["value"=>"0", "message"=>"Anda Tidak Memiliki Favorit"];
        }
        return $this->msg;

    }


 // Add kurir
    public  function  addKurir($data = null){
        $new_kurir = $data;
        $sql ="SElECT * FROM `tr_pengguna` WHERE phone = :phone AND tipe IN ('restoran','kurir')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":phone"=>$new_kurir["kurir_phone"]]);

        if ($stmt->rowCount() > 0){ //kondisi telah ada pengguna
            $this->msg = ["status"=>"0", "message"=>"Oops! Nomor Telpon Genggam Sudah Terdaftar! "];
        }else {
            $db = $this->db;
            try{

                $db->beginTransaction();

                $sql = "INSERT INTO `m_kurir`(`kurir_restoran_id`,`kurir_phone`, `kurir_nama`, `kurir_email`) VALUES (:restoran_id,:kurir_phone, :kurir_nama, :kurir_email)";
                $stmt = $this->db->prepare($sql);

                $stmt->bindValue(':kurir_phone',$new_kurir['kurir_phone']);
                $stmt->bindValue(':kurir_nama',$new_kurir['kurir_nama']);
                $stmt->bindValue(':kurir_email',$new_kurir['kurir_email']);
                $stmt->bindValue(':restoran_id',$new_kurir['kurir_restoran_id']);

                $stmt->execute();

                $sql2 ="INSERT INTO `tr_pengguna`(`phone`,`token`, `tipe`) VALUES (:kurir_phone,:token,:tipe)";
                $stmt = $this->db->prepare($sql2);
                $stmt->bindValue(':kurir_phone',$new_kurir['kurir_phone']);
                $stmt->bindValue(':token',0);
                $stmt->bindValue(':tipe','kurir');
                $stmt->execute();
                $this->db->commit();

                $this->msg = ["status"=>"1", "message"=>"Sukses Mendaftar"];



            }catch (PDOException $e){
                $db->rollback();
                $this->msg = ["status"=>"0", "message"=>"Oops! Coba Lagi!"];

            }

        }
        return $this->msg;
    }

    public function getKurir ($id_restorann = null){

        $id_restoran =$id_restorann;
        $sql = "SELECT a.*,b.* FROM `m_kurir`  a,tr_pengguna b WHERE b.phone = a.kurir_phone   AND a.kurir_restoran_id =:id_restoran ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_restoran',$id_restoran);
        $stmt->execute();
        $result =  $stmt->fetchAll();
        if($stmt->rowCount()>0){
            return $this->msg = ["status"=>"1" , "message" => "Anda Memiliki Kurir", "jumlah"=>$stmt->rowCount(),"kurir" => $result];
        }else{
            return $this->msg = ["status"=>"0" , "message" => "Anda Tidak Memiliki Kurir"];
        }


    }


 //set menu favorit
    public  function  setMenuFavorit($data = null){
        $new_favorit = $data;
        $sql ="SElECT * FROM `tr_favorite` WHERE konsumen_id =:id_konsumen AND menu_id=:id_menu";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id_konsumen"=>$new_favorit["id_konsumen"], ":id_menu"=> $new_favorit["id_menu"]]);

        if ($stmt->rowCount() > 0){ //kondisi telah ada pengguna
            $this->msg = ["value"=>"0", "message"=>"Menu Telah Menjadi Favorit"];
        }else {


                $sql = "INSERT INTO `tr_favorite`( `konsumen_id`, `menu_id`) VALUES  (:id_konsumen,:id_menu)";
                $stmt = $this->db->prepare($sql);

                $stmt->bindValue(':id_konsumen',$new_favorit['id_konsumen']);
                $stmt->bindValue(':id_menu',$new_favorit['id_menu']);

                if( $stmt->execute()){
                $this->msg = ["value"=>"1", "message"=>"Favorit"];
                }else{
                    $this->msg = ["value"=>"0", "message"=>"Gagal, Coba Lagi"];
                }




        }
        return $this->msg;
    }




}