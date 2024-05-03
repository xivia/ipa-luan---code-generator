<?php
namespace Model;

use Utils\MysqliDB;

class Table implements \JsonSerializable {
 

    private int $id;

    private Database $database;
    private string $name;

    private static int $counter = 0;

    protected static bool $USE_CUSTOM_FIELDNAMES = true;
    protected static array $CUSTOM_FIELDNAMES = ['RecNo' => 'id'];


    public function __construct($name, $database) {

        $this->id = ++self::$counter;

        $this->database = $database;
        $this->name = $name;
    }

    public static function list(Database $database): array {
        $config = $database->getConfig();

        $mysqliConn = MysqliDB::getInstance()->getConnection($config, $database);

        $res = $mysqliConn->query("SHOW TABLES;");

        $key = "Tables_in_{$database->getName()}";
        $objects = [];
        while ($record = $res->fetch_assoc()) {
            $objects[] = new Table($record[$key], $database);
        }

        return $objects;
    }

    public static function getById(Database $database, $tableId): Table {
        $config = $database->getConfig();

        $mysqliConn = MysqliDB::getInstance()->getConnection($config, $database);

        $res = $mysqliConn->query("SHOW TABLES;");

        $object = (object) [];
        while ($record = $res->fetch_assoc()) {
            $object = new Table($record["Tables_in_{$database->getName()}"], $database);
            if($object->getId() == $tableId) {
                break;
            }
        }

        return $object;
    }

    public function getFields() {
        $config = $this->getDatabase()->getConfig();
        $database = $this->getDatabase();
        
        $mysqliConn = MysqliDB::getInstance()->getConnection($config, $database);

        $dbName = $database->getName();
        $tableName = $this->getName();

        $query = "SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
                  WHERE TABLE_SCHEMA = '$dbName' AND TABLE_NAME = '$tableName';";
        
        $res = $mysqliConn->query($query);
        $fields = $res->fetch_all(MYSQLI_ASSOC);

        if (self::$USE_CUSTOM_FIELDNAMES) {
            foreach ($fields as &$field) {
                $key = array_search($field['COLUMN_NAME'], array_keys(self::$CUSTOM_FIELDNAMES));
                if ($key !== false) {
                    $field['COLUMN_NAME'] = array_values(self::$CUSTOM_FIELDNAMES)[$key];
                }
            }
        }

        return $fields;
    }

    public function jsonSerialize(): mixed {
        return [
            'id'     => $this->id,
            'name'   => $this->name
        ];
    }

    public function getId(): int { 
        return $this->id;
    }

    public function getDatabase(): Database { 
        return $this->database;
    }

    private function setDatabase(Database $database) { 
        $this->database = $database;
    }

    public function getName(): string { 
        return $this->name;
    }

}   
