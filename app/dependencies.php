<?php
// DIC configuration

$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Twig
$container['view'] = function ($container) {
    $settings = $container->get('settings');
    $view = new \Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($container->get('router'), $container->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());

    return $view;
};

// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------


// PDO
$container['pdo'] = function ($container) {
    $settings = $container->get('settings')['db'];
    $server = $settings['driver'].":host=".$settings['host'].";dbname=".$settings['dbname'];
    $conn = new PDO($server, $settings["user"], $settings["pass"]);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Disable emulate prepared statements
    return $conn;
};

// monolog
$container['logger'] = function ($container) {
    $settings = $container->get('settings');
    $logger = new \Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['logger']['path'], \Monolog\Logger::DEBUG));
    return $logger;
};



// -----------------------------------------------------------------------------
// Model factories
// -----------------------------------------------------------------------------
$container['cfgModel'] = function ($container) {
    $settings = $container->get('settings');
    $cfgModel = new App\Model\ConfigurationModel($container->get('pdo'));
    return $cfgModel;
};

$container['Konsumen'] = function ($container) {
    $settings = $container->get('settings');
    $Model = new App\Model\konsumenModel($container->get('pdo'));
    return $Model;
};

$container['authModel'] = function ($container) {
    $settings = $container->get('settings');
    $cfgModel = new App\Model\AuthModel($container->get('pdo'));
    return $cfgModel;
};

$container['restorantModel'] = function ($container){
    $setting = $container->get('settings') ;
    $model = new App\Model\restorantModel($container->get('pdo'));
    return $model;
};

// -----------------------------------------------------------------------------
// Controller factories
// -----------------------------------------------------------------------------

$container['App\Controller\IndexController'] = function ($container) {
    $view = $container->get('view');
    $logger = $container->get('logger');
    return new App\Controller\IndexController($view, $logger);
};

$container['App\Controller\SystemController'] = function ($container) {
    $logger = $container->get('logger');
    $cfgModel = $container->get('cfgModel');
    // $cfgModel = $container->get('cfgModelFPDO');
    // $cfgModel = $container->get('cfgModelMock');
    return new App\Controller\SystemController($logger, $cfgModel);
};

$container['App\Controller\KonsumenController'] = function ($container) {
    $logger = $container->get('logger');
    $Model = $container->get('Konsumen');
    return new App\Controller\KonsumenController($logger, $Model);
};

$container['App\Controller\AuthController'] = function ($container) {
    $logger = $container->get('logger');
    $authModel = $container->get('authModel');
    return new App\Controller\AuthController($logger, $authModel);
};

$container['App\Controller\RestorantController'] = function ($container){
    $logger = $container->get('logger');
    $restorantModel = $container->get('restorantModel');
    return new App\Controller\RestorantController($logger,$restorantModel);
};