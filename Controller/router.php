<?php
namespace Controller;

use Controller\ConfigController;

error_reporting(E_ALL);
ini_set('display_errors', 'On');

spl_autoload_register(function ($class_name) {
    $class_name = str_replace('\\', DIRECTORY_SEPARATOR, $class_name);
    require_once('../'.$class_name.'.php');
});



if(isset($_GET['action']) && $_GET['action'] == 'getConfigs') {

    $controller = new ConfigController();
    $controller->getConfigs();
    exit();

}

if(isset($_GET['action']) && $_GET['action'] == 'getDatabases') {

    $configId = isset($_GET['configId']) ? $_GET['configId'] : 0;

    $controller = new ConfigController();
    $controller->getDatabases($configId);

    exit();
}

if(isset($_GET['action']) && $_GET['action'] == 'getTables') {

    $configId = isset($_GET['configId']) ? $_GET['configId'] : 0;
    $databaseId = isset($_GET['databaseId']) ? $_GET['databaseId'] : 0;

    $controller = new ConfigController();
    $controller->getTables($configId, $databaseId);

    exit();
}

if(isset($_GET['action']) && $_GET['action'] == 'addConfig') {

    $host = isset($_GET['config_host']) ? $_GET['config_host'] : '';
    $user = isset($_GET['config_user']) ? $_GET['config_user'] : '';
    $password = isset($_GET['config_password']) ? $_GET['config_password'] : '';
    $port = isset($_GET['config_port']) ? $_GET['config_port'] : 0;

    $controller = new ConfigController();
    $controller->createConfig($host, $user, $password, $port);


    exit();
}

echo('{"msg": "fallthrough"}');



?>