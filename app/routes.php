<?php
// Routes



$app->get('/', 'App\Controller\IndexController:index')
    ->setName('index');

//authentification
$app->group('/auth',function ($ap){
    //signup
    $ap->post('/signup','App\Controller\AuthController:setSignUp')                      //ok
        ->setName('sign');
    //signin
    $ap->get('/signin/{phone}','App\Controller\AuthController:getSignIn')               //ok
        ->setName('login');

    //signin resto
    $ap->get('/signin/resto/{phone}','App\Controller\AuthController:signInResto')       //ok
        ->setName('loginresto');

    //sign up resto
    $ap->Post('/signup/resto','App\Controller\AuthController:setSignUpResto');              //ok

    //set Kategori
    $ap->Post('/signup/resto/kategori','App\Controller\AuthController:setKategoriResto');      //ok

    $ap->PUT('/token/{id_pengguna}','App\Controller\AuthController:updateToken')               //ok
    ->setName('updatetoken');

});
//---------------------- tidak digunakan -------------------------
//konsuemn
$app->group('/konsumen',function($ap){
    //view konsumen by phone
    $ap->GET('/{phone}','App\Controller\KonsumenController:viewKonsumen')
        ->setName('viewKonsumen');
    //update
});
///---------------------------------------------------


//restoran
$app->group('/restoran',function ($ap){
    //get all restoran
    $ap->GET('/','App\Controller\RestorantController:getAllResto');
    //get restoran by id
    $ap->GET('/{id_restoran}','App\Controller\RestorantController:getRestorantByID');
    //oprasional restoran
    $ap->PUT('/{id_restoran}','App\Controller\RestorantController:putOperasionalRestoran');

    //add kurir
    $ap->POST('/kurir/','App\Controller\RestorantController:addKurir');

    //get kurir
    $ap->GET('/kurir/{id_restoran}','App\Controller\RestorantController:getKurir');

});

//kategori
$app->group('/kategori',function ($ap){
    //get all restoran
    $ap->GET('/','App\Controller\RestorantController:getAllKategori');              //ok
});

//menu
$app->group('/menu',function ($ap){
    //get all restoran
    $ap->GET('/{id_restoran}','App\Controller\RestorantController:getAllMenuByIdRestaurant');
    //get menu & kategori for restaurant
    $ap->GET('/restoran/{id_restoran}','App\Controller\RestorantController:getMenuKategoriByResto');
    //insert menu
    $ap->POST('/','App\Controller\RestorantController:addMenu');
    //update ketersedian menu
    $ap->PUT('/{id_menu}','App\Controller\RestorantController:updateKetersedianMenu');
    //set Favorit
    $ap->POST('/favorit/','App\Controller\RestorantController:setMenuFavorit');
    //get Favorit konsumen
    $ap->GET('/favorit/{id_konsumen}','App\Controller\RestorantController:getMenuFavorit');


});

//pesanan detail
$app->group('/pesandetail',function ($ap){
    //set detail pesan
    $ap->POST('/','App\Controller\orderController:setPesanDetail');
});



//Pesanan
$app->post('/send/','App\Controller\orderController:send');



$app->GET ('/order/{id_restoran}','App\Controller\orderController:getOrderByRestoran');

$app->GET ('/order/delivery/{id_restoran}','App\Controller\orderController:getOrderByRestoranDelivery');

///pesanan by id konsumen
$app->GET ('/order/konsumen/{id_konsumen}','App\Controller\orderController:getOrderByKonsumen');


//kurir



$app->group('/api/v1', function () {
    $this->get('/sys/config[/{id}]', 'App\Controller\SystemController:getConfig')
         ->setName('api_get_config');

         
    $this->get('/sys/version', 'App\Controller\SystemController:getVersion')
         ->setName('api_get_app_version');
});

$app->get('/konsumen/', 'App\Controller\KonsumenController:getAllKonsumen')
    ;



$app->post('/getToken/','App\Controller\orderController:getToken');