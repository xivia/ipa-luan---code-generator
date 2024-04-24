<?php
namespace Model;

use Utils\MysqliDB;

class Database implements \JsonSerializable {

    private int $id;
    
    private Config $config;
    private string $name;

    private static int $counter = 0;


    public function __construct(string $name, Config $config) {

        $this->id = ++self::$counter;
        
        $this->config = $config;
        $this->name = $name;
    }

    public static function list(Config $config): array {
        $mysqliConn = MysqliDB::getInstance()->getConnection($config);

        $res = $mysqliConn->query("SHOW DATABASES;");

        $objects = [];
        while ($record = $res->fetch_assoc()) {
            $objects[] = new Database($record['Database'], $config);
        }

        return $objects;
    }

    public static function getById(Config $config, int $databaseId): Database {
        $mysqliConn = MysqliDB::getInstance()->getConnection($config);

        $res = $mysqliConn->query("SHOW DATABASES;");

        $object = (object) [];
        while ($record = $res->fetch_assoc()) {
            $object = new Database($record['Database'], $config);
            if($object->getId() == $databaseId) {
                break;
            }
        }

        return $object;
    }

    public function jsonSerialize(): mixed {
        return [
            'id'   => $this->id,
            'name' => $this->name
        ];
    }

    public function getId(): string { 
        return $this->id;
    }

    public function getConfig(): Config { 
        return $this->config;
    }

    public function setConfig(Config $config) { 
        $this->config = $config;
    }

    public function getName(): string { 
        return $this->name;
    }

    public function setName(string $name) { 
        $this->name = $name;
    }

    

}