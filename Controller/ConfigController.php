<?php
namespace Controller;

use Model\Config;
use Model\Database;
use Model\Table;

use Utils\Response;
use Utils\ConfigManager;

class ConfigController {
    
    public function getConfigs() {
        $response = new Response();

        $response->setStatus(Response::$STATUS_OK);
        $response->setMessage('');
        $response->setHttpResponseCode(Response::$HTTP_STATUS_OK);
        $response->setData(ConfigManager::getInstance()->getConfigs());

        $response->respond();
    }

    public function getDatabases($configId) {
        $response = new Response();
        
        $config = ConfigManager::getInstance()->getConfigById($configId);
        $databases = Database::list($config);

        $response->setStatus(Response::$STATUS_OK);
        $response->setMessage('');
        $response->setHttpResponseCode(Response::$HTTP_STATUS_OK);
        $response->setData($databases);

        $response->respond();
    }

    public function getTables($configId, $databaseId) {
        $response = new Response();

        $config = ConfigManager::getInstance()->getConfigById($configId);
        $database = Database::getById($config, $databaseId);
        $tables = Table::list($database);

        $response->setStatus(Response::$STATUS_OK);
        $response->setMessage('');
        $response->setHttpResponseCode(Response::$HTTP_STATUS_OK);
        $response->setData($tables);

        $response->respond();
    }

    public function createConfig($host, $user, $password, $port) {
        $response = new Response();

        $manager = ConfigManager::getInstance();
        if($manager->createConfig($host, $user, $password, $port)) {
            $response->setStatus(Response::$STATUS_OK);
            $response->setMessage('');
            $response->setHttpResponseCode(Response::$HTTP_STATUS_OK);
        } else {
            $response->setStatus(Response::$STATUS_ERROR);
            $response->setMessage('Failed to add new config');
            $response->setHttpResponseCode(Response::$HTTP_STATUS_SERVER_ERROR);
        }

        $response->respond();
    }

}