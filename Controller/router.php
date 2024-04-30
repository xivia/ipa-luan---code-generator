<?php
namespace Controller;

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


if(isset($_GET['action']) && $_GET['action'] == 'generatePHPModel') {

    $configId = isset($_GET['configId']) ? $_GET['configId'] : 0;
    $databaseId = isset($_GET['databaseId']) ? $_GET['databaseId'] : 0;
    $tableId = isset($_GET['tableId']) ? $_GET['tableId'] : '';

    $snippet = new SnippetControllerPHP();
    $snippet->generateModel($configId, $databaseId, $tableId);

    exit();
}

if(isset($_GET['action']) && $_GET['action'] == 'generatePHPGateway') {

    $configId = isset($_GET['configId']) ? $_GET['configId'] : 0;
    $databaseId = isset($_GET['databaseId']) ? $_GET['databaseId'] : 0;
    $tableId = isset($_GET['tableId']) ? $_GET['tableId'] : '';

    $snippet = new SnippetControllerPHP();
    $snippet->generateGateway($configId, $databaseId, $tableId);

    exit();
}

if(isset($_GET['action']) && $_GET['action'] == 'generateExtJSModel') {

    $configId = isset($_GET['configId']) ? $_GET['configId'] : 0;
    $databaseId = isset($_GET['databaseId']) ? $_GET['databaseId'] : 0;
    $tableId = isset($_GET['tableId']) ? $_GET['tableId'] : '';

    $snippet = new SnippetControllerExtJS();
    $snippet->generateModel($configId, $databaseId, $tableId);

    exit();
}

if(isset($_GET['action']) && $_GET['action'] == 'generateExtJSGrid') {

    $configId = isset($_GET['configId']) ? $_GET['configId'] : 0;
    $databaseId = isset($_GET['databaseId']) ? $_GET['databaseId'] : 0;
    $tableId = isset($_GET['tableId']) ? $_GET['tableId'] : '';

    $snippet = new SnippetControllerExtJS();
    $snippet->generateGridList($configId, $databaseId, $tableId);

    exit();
}

if(isset($_GET['action']) && $_GET['action'] == 'generateExtJSAdd') {

    $configId = isset($_GET['configId']) ? $_GET['configId'] : 0;
    $databaseId = isset($_GET['databaseId']) ? $_GET['databaseId'] : 0;
    $tableId = isset($_GET['tableId']) ? $_GET['tableId'] : '';

    $snippet = new SnippetControllerExtJS();
    $snippet->generateAddDialog($configId, $databaseId, $tableId);

    exit();
}

if(isset($_GET['action']) && $_GET['action'] == 'generateExtJSEdit') {

    $configId = isset($_GET['configId']) ? $_GET['configId'] : 0;
    $databaseId = isset($_GET['databaseId']) ? $_GET['databaseId'] : 0;
    $tableId = isset($_GET['tableId']) ? $_GET['tableId'] : '';

    $snippet = new SnippetControllerExtJS();
    $snippet->generateEditDialog($configId, $databaseId, $tableId);

    exit();
}

if(isset($_GET['action']) && $_GET['action'] == 'generateExtJSInfo') {

    $configId = isset($_GET['configId']) ? $_GET['configId'] : 0;
    $databaseId = isset($_GET['databaseId']) ? $_GET['databaseId'] : 0;
    $tableId = isset($_GET['tableId']) ? $_GET['tableId'] : '';

    $snippet = new SnippetControllerExtJS();
    $snippet->generateInfoDialog($configId, $databaseId, $tableId);

    exit();
}

// POST REQUESTS

if(isset($_POST['action']) && $_POST['action'] == 'addConfig') {

    $host = isset($_POST['config_host']) ? $_POST['config_host'] : '';
    $user = isset($_POST['config_user']) ? $_POST['config_user'] : '';
    $password = isset($_POST['config_password']) ? $_POST['config_password'] : '';
    $port = isset($_POST['config_port']) ? $_POST['config_port'] : 0;

    $controller = new ConfigController();
    $controller->createConfig($host, $user, $password, $port);


    exit();
}

echo('{"msg": "fallthrough"}');



?>