<?php
namespace Utils;

use Model\Config;
use Model\Database;
use mysqli;
use mysqli_sql_exception as MysqliSQLException;

class MysqliDB {

    private static MysqliDB $instance;

    private array $connections = []; // [['config' => obj, 'mysqli' => obj], [...]]

    
    private function __construct() {
        // turn on mysqli error throwing
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    }

    public static function getInstance():MysqliDB {
        if(!isset(self::$instance)) {
            self::$instance = new MysqliDB();
        }
        return self::$instance;
    }

    public function getConnection(Config $config, Database $database = NULL): mysqli {
        
        $foundConnection = NULL;

        foreach ($this->connections as $connection) {
            if ($connection['config']->getId() == $config->getId()) {
                $foundConnection = $connection['mysqli'];
                break;
            }
        }
        // if we're here, myslqi was not found, so lets add it and get the new connection
        $foundConnection = $this->addConnection($config);

        // if database is set we have to use it
        if (!is_null($database)) {
            $foundConnection->select_db($database->getName());
        }


        return $foundConnection;
    }

    public function getConnections(): array {
        return $this->connections;
    }

    private function setConnections(array $connections) {
        $this->connections = $connections;
    }

    // returns the added connection array
    private function addConnection(Config $config): mysqli {

         try {
            $mysqliConn = new mysqli($config->getHost(), 
                                      $config->getUsername(), 
                                      $config->getPassword(),  
                                      port: $config->getPort());

        } catch (MysqliSQLException $e) {

            ErrorThrower::throw('Connection to server failed', $e);
        }

        $connectionArray = ['config' => $config, 'mysqli' => $mysqliConn];
        array_push($this->connections, $connectionArray);

        return $connectionArray['mysqli'];
    }
}