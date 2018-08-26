<?php
// Routes



$app->get('/', 'App\Controller\IndexController:index')
    ->setName('index')->add($keyRequest);

//authentification
$app->group('/auth',function ($ap){
    //signup
    $ap->post('/signup','App\Controller\AuthController:setSignUp')
        ->setName('sign');
    //signin
    $ap->get('/login/{phone}','App\Controller\AuthController:checkPhoneNumber')
        ->setName('login');
});


$app->group('/api/v1', function () {
    $this->get('/sys/config[/{id}]', 'App\Controller\SystemController:getConfig')
         ->setName('api_get_config');
         
    $this->get('/sys/version', 'App\Controller\SystemController:getVersion')
         ->setName('api_get_app_version');
});

$app->get('/konsumen/', 'App\Controller\KonsumenController:getAllKonsumen')
    ;

