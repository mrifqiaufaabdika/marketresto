<?php
// Routes



$app->get('/', 'App\Controller\IndexController:index')
    ->setName('index');

//authentification
$app->group('/auth',function ($ap){
    //signup
    $ap->post('/signup','App\Controller\AuthController:setSignUp')
        ->setName('sign');
    //signin
    $ap->get('/signin/{phone}','App\Controller\AuthController:checkPhoneNumber')
        ->setName('login');

    //signin
    $ap->get('/signin/resto/{phone}','App\Controller\AuthController:checkPhoneNumber')
        ->setName('loginresto');
});

//konsuemn
$app->group('/konsumen',function($ap){
    //view konsumen by phone
    $ap->GET('/{phone}','App\Controller\KonsumenController:viewKonsumen')
        ->setName('viewKonsumen');
    //update

});

$app->group('/restorant',function ($ap){
    //get all restoran
    $ap->GET('/','App\Controller\RestorantController:getAllResto');
});


$app->group('/kategori',function ($ap){
    //get all restoran
    $ap->GET('/','App\Controller\RestorantController:getAllKategori');
});

$app->group('/menu',function ($ap){
    //get all restoran
    $ap->GET('/{id_restoran}','App\Controller\RestorantController:getAllMenuByIdRestaurant');
});





$app->group('/api/v1', function () {
    $this->get('/sys/config[/{id}]', 'App\Controller\SystemController:getConfig')
         ->setName('api_get_config');

         
    $this->get('/sys/version', 'App\Controller\SystemController:getVersion')
         ->setName('api_get_app_version');
});

$app->get('/konsumen/', 'App\Controller\KonsumenController:getAllKonsumen')
    ;

