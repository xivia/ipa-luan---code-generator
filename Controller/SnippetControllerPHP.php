<?php
namespace Controller;

use Model\Config;
use Model\Database;
use Model\Table;

use Utils\Response;
use Utils\ErrorThrower;
use Utils\ConfigManager;

class SnippetControllerPHP extends SnippetControllerBasic {

    private static string $NAMESPACE_MODEL = 'Model';
    private static string $PARENT_MODEL = 'BasicModel';

    private static string $NAMESPACE_GATEWAY = 'Gateway';
    private static string $PARENT_GATEWAY = 'BasicGateway';

    private static array $DEFAULT_VALUES_TYPE = ['int' => 'NULL', 'float' => 'NULL', 'string' => '\'\'', 'DateTime' => 'NULL'];
    private static array $DEFAULT_VALUE_FIELD = ['StateCd' => 1];
    private static array $BIND_TYPES = ['int' => 'i', 'float' => 'd', 'string' => 's', 'DateTime' => 's'];

    public function generateModel(int $configId, int $databaseId, int $tableId) {
        $response = new Response();

        $config = ConfigManager::getInstance()->getConfigById($configId);
        $database = Database::getById($config, $databaseId);
        $table = Table::getById($database, $tableId);

        $fields = $this->prepareFields($table->getFields());
        $className = $this->convertToClassname($table->getName());
        $newLine1 = '<br>';
        $newLine2 = '<br><br>';

        $header = $this->generateModelHeader($className);
        $attributes = $this->generateAttributes($fields);
        $jsonSerialize = $this->generateJsonSerialize($fields);
        $createObjectMethod = $this->generateCreateObject($fields, $className);
        $gettersAndSetters = $this->generateGettersAndSetters($fields);
        $footer = $this->generateFooter();

        $output = $header.
                  $newLine2.
                  $attributes.
                  $newLine2.
                  $jsonSerialize.
                  $newLine2.
                  $createObjectMethod.
                  $newLine2.
                  $gettersAndSetters.
                  $newLine2.
                  $footer;

        $response->setStatus(Response::$STATUS_OK);
        $response->setMessage('');
        $response->setHttpResponseCode(Response::$HTTP_STATUS_OK);
        $response->setData($output);

        $response->respond();
    }

    public function generateGateway(int $configId, int $databaseId, int $tableId) {
        $response = new Response();

        $config = ConfigManager::getInstance()->getConfigById($configId);
        $database = Database::getById($config, $databaseId);
        $table = Table::getById($database, $tableId);

        $fields = $this->prepareFields($table->getFields(), ['id']);
        $tableName = $table->getName();
        $modelClassName = $this->convertToClassname($tableName);
        $className = $modelClassName.'Gateway';
        $newLine1 = '<br>';
        $newLine2 = '<br><br>';

        $header = $this->generateGatewayHeader($className, $tableName);
        $insert = $this->generateInsert($fields, $modelClassName, $tableName);
        $update = $this->generateUpdate($fields, $modelClassName, $tableName);
        $footer = $this->generateFooter();

        $output = $header.
                  $newLine2.
                  $insert.
                  $newLine2.
                  $update.
                  $newLine2.
                  $footer;

        $response->setStatus(Response::$STATUS_OK);
        $response->setMessage('');
        $response->setHttpResponseCode(Response::$HTTP_STATUS_OK);
        $response->setData($output);

        $response->respond();
    }

    private function generateModelHeader(string $className): string {

        $content = htmlspecialchars('<?php');
        $content .= '<br>';
        $content .= 'namespace ' . self::$NAMESPACE_MODEL . ';';
        $content .= '<br>';
        $content .= '<br>';
        $content .= 'use JsonSerializable;';
        $content .= '<br>';
        $content .= 'use \DateTime;';
        $content .= '<br>';
        $content .= '<br>';
        $content .= "class $className extends " . self::$PARENT_MODEL . ' implements JsonSerializable {';

        return ($content);
    }


    private function generateAttributes(array $fields): string {


        $content = '';
        foreach ($fields as $field) {

            $typeSpaces = $field['DATA_TYPE_SPACES'];
            $nameSpaces = $field['COLUMN_NAME_SPACES'];

            $field['COLUMN_NAME'] = lcfirst($field['COLUMN_NAME']);

            $content .= "{$this->indent(1)}private {$field['DATA_TYPE_DISPLAY']}$typeSpaces \${$field['COLUMN_NAME']}$nameSpaces = {$field['DEFAULT_VALUE']};<br>";
        }
        $content = $this->removeLastOccurrence($content, '<br>');
        return $content;
    }

    private function generateJsonSerialize(array $fields) {
        $content = "{$this->indent(1)}public function jsonSerialize(): array {<br>";
        $content .= "{$this->indent(2)}return [<br>";
        foreach ($fields as $field) {
            $lname = lcfirst($field['COLUMN_NAME']);
            $nameSpaces = $field['COLUMN_NAME_SPACES'];
            if ($field['DATA_TYPE'] == 'DateTime') {
                $content .= "{$this->indent(3)}'$lname'$nameSpaces => \$this->formatDate(\$this->$lname),<br>";
            } else {
                $content .= "{$this->indent(3)}'$lname'$nameSpaces => \$this->$lname,<br>";
            }
            
        }
        $content .= "{$this->indent(2)}];";
        $content .= "<br>";
        $content .= "{$this->indent(1)}}";

        $content = $this->removeLastOccurrence($content, ',');
        
        return $content;
    }

    private function generateCreateObject(array $fields, string $className) {
        $content = "{$this->indent(1)}public static function createObject(array \$data): $className {<br><br>";
        $content .= "{$this->indent(2)}\$obj = new $className();<br>";
        foreach ($fields as $field) {
            $name = $field['COLUMN_NAME'];
            $lname = lcfirst($name);
            $nameSpaces = $field['COLUMN_NAME_SPACES'];
            $nameSpacesAddon = $field['COLUMN_NAME_SPACES_ADDON'];
            if ($field['DATA_TYPE'] == 'DateTime') {
                $content .= "{$this->indent(2)}\$obj->{$lname}$nameSpaces = isset(\$data['$name'])$nameSpaces ? new DateTime(\$data['$name'])$nameSpacesAddon : {$field['DEFAULT_VALUE']};<br>";
            } else {
                $content .= "{$this->indent(2)}\$obj->{$lname}$nameSpaces = isset(\$data['$name'])$nameSpaces ? \$data['$name']$nameSpacesAddon : {$field['DEFAULT_VALUE']};<br>";
            }
        }
        $content .= "<br>";
        $content .= "{$this->indent(2)}return \$obj;<br><br>";
        $content .= "{$this->indent(1)}}";
        $content = rtrim($content, '<br>');
        return $content;
    }

    private function generateGettersAndSetters(array $fields, bool $visibilityGet = false, bool $visibilitySet = false): string {
        if ($visibilityGet == true) {
            $visibilityG = 'private';
        } else {
            $visibilityG = 'public';
        }

        if ($visibilitySet == true) {
            $visibilityS = 'private';
        } else {
            $visibilityS = 'public';
        }
        $content = '';
        foreach ($fields as $field) {
            $name = $field['COLUMN_NAME'];
            $lname = lcfirst($name);
            $uname = ucfirst($name);
            $type = $field['DATA_TYPE_DISPLAY'];
            $content .= "{$this->indent(1)}$visibilityG function get$uname(): $type {<br>{$this->indent(2)}return \$this->$lname;<br>{$this->indent(1)}}";
            $content .= "<br><br>";
            $content .= "{$this->indent(1)}$visibilityS function set$uname($type \$$lname) {<br>{$this->indent(2)}\$this->$lname = \$$lname;<br>{$this->indent(1)}}";
            $content .= "<br><br><br>";
        }
        $content = rtrim($content, '<br>');
        return $content;
    }

    private function generateGatewayHeader(string $className, string $tableName): string {

        $content = htmlspecialchars('<?php');
        $content .= '<br>';
        $content .= 'namespace ' . self::$NAMESPACE_GATEWAY . ';';
        $content .= '<br>';
        $content .= '<br>';
        $content .= 'use mysqli;';
        $content .= '<br>';
        $content .= 'use mysqli_result;';
        $content .= '<br>';
        $content .= '<br>';
        $content .= "class $className extends " . self::$PARENT_GATEWAY . ' {<br><br>';
        $content .= "{$this->indent(1)}public function __construct() {<br>";
        $content .= "{$this->indent(2)}parent::__construct();<br>";
        $content .= "{$this->indent(2)}\$this->table = \"$tableName\";<br>";
        $content .= "{$this->indent(1)}}";
        return ($content);
    }

    private function generateInsert(array $fields, string $className, string $tablename) {

        $content = "{$this->indent(1)}public function insert($className \$obj): int {<br><br>";
        $content .= "{$this->indent(2)}\$stmt = \$this->conn->prepare(\"INSERT INTO $tablename (<br>";

        foreach ($fields as $field) {
            $name = $field['COLUMN_NAME'];
            $content .= "{$this->indent(3)}$name,<br>";
        }

        $content = $this->removeLastOccurrence($content, ',');
        $content .= "{$this->indent(2)}) VALUES (<br>";
        $content .= "{$this->indent(3)}".str_repeat('?,', count($fields))."<br>";
        $content = $this->removeLastOccurrence($content, ',');
        $content .= "{$this->indent(2)});\");";
        $content .= "<br><br>";
        $content .= "{$this->indent(2)}// bind requires references (get return value)<br>";
        $content .= "{$this->indent(2)}\$params = [<br>";

        $bindTypes = '';
        foreach ($fields as $field) {
            $name = $field['COLUMN_NAME'];
            $uname = ucfirst($name);
            $dataType = $field['DATA_TYPE'];
            $bindTypes .= self::$BIND_TYPES[$dataType];
            if ($field['DATA_TYPE'] == 'DateTime') {
                $content .= "{$this->indent(3)}\$obj->formatDate(\$obj->get$uname()),<br>";
            } else {
                $content .= "{$this->indent(3)}\$obj->get$uname(),<br>";
            }
        }
        $content = $this->removeLastOccurrence($content, ',');
        $content .= "{$this->indent(2)}];";
        $content .= "<br><br>";
        $content .= "{$this->indent(2)}\$stmt->bind_param('$bindTypes', ...\$params);";
        $content .= "<br><br>";
        $content .= "{$this->indent(2)}\$stmt->execute();";
        $content .= "<br><br>";
        $content .= "{$this->indent(2)}//echo(\$stmt->error);";
        $content .= "<br><br>";
        $content .= "{$this->indent(2)}return \$this->conn->insert_id;<br>";
        $content .= "{$this->indent(1)}}";

        return $content;

    }

    private function generateUpdate(array $fields, string $className, string $tablename) {

        $content = "{$this->indent(1)}public function update($className \$obj): int {<br><br>";
        $content .= "{$this->indent(2)}\$stmt = \$this->conn->prepare(\"UPDATE $tablename SET<br>";

        foreach ($fields as $field) {
            $name = $field['COLUMN_NAME'];
            $content .= "{$this->indent(3)}$name = ?,<br>";
        }

        $content = $this->removeLastOccurrence($content, ',');
        $content .= "{$this->indent(3)}WHERE \$this->pk = ?;\");";
        $content .= "<br><br>";
        $content .= "{$this->indent(2)}// bind requires references (get return value)<br>";
        $content .= "{$this->indent(2)}\$params = [<br>";

        $bindTypes = '';
        foreach ($fields as $field) {
            $name = $field['COLUMN_NAME'];
            $uname = ucfirst($name);
            $dataType = $field['DATA_TYPE'];
            $bindTypes .= self::$BIND_TYPES[$dataType];
            if ($field['DATA_TYPE'] == 'DateTime') {
                $content .= "{$this->indent(3)}\$obj->formatDate(\$obj->get$uname()),<br>";
            } else {
                $content .= "{$this->indent(3)}\$obj->get$uname(),<br>";
            }
        }
        $content = $this->removeLastOccurrence($content, ',');
        $content .= "{$this->indent(2)}];";
        $content .= "<br><br>";
        $content .= "{$this->indent(2)}\$params[] = \$obj->getId();";
        $content .= "<br><br>";
        $content .= "{$this->indent(2)}\$stmt->bind_param('{$bindTypes}i', ...\$params);";
        $content .= "<br><br>";
        $content .= "{$this->indent(2)}\$stmt->execute();";
        $content .= "<br><br>";
        $content .= "{$this->indent(2)}//echo(\$stmt->error);";
        $content .= "<br><br>";
        $content .= "{$this->indent(2)}return \$obj->getId();<br>";
        $content .= "{$this->indent(1)}}";

        return $content;

    }

    private function generateFooter() : string {
        return '}';
    }

    protected function prepareFields(array $fields, array $filterOut = []): array {

        // translate SQL data types to PHP
        $wholeNumberSQLTypes = ['int', 'integer', 'bigint', 'smallint', 'tinyint'];
        $decimalNumberSQLTypes = ['dec', 'decimal', 'float', 'double', 'double precision'];
        $stringSQLTypes = ['char', 'varchar', 'tinyblob', 'mediumblob', 'blob', 'longblob', 'tinytext', 'mediumtext', 'text', 'longtext'];
        $dateTimeSQLTypes = ['date', 'datetime', 'timestamp', 'time', 'year'];

        // special length for when COLUMN_NAME must be wrapped by "new DateTime()" - used in Model createObject
        $addonNames = [];
        foreach ($fields as $field) {
            if ($field['DATA_TYPE'] == 'datetime' || $field['DATA_TYPE'] == 'date') {
                $addonNames[] = "new DateTime({$field['COLUMN_NAME']})";
            } else {
                $addonNames[] = $field['COLUMN_NAME'];
            }
        }
        $maxAddonNameLen = max(array_map('strlen', $addonNames));

        foreach ($fields as $key => &$field) {
            
            // add key for special spacing with "new DateTime()"
            $field['COLUMN_NAME_SPACES_ADDON'] = str_repeat('&nbsp;', $maxAddonNameLen - strlen($addonNames[$key]));

            $type = strtolower($field['DATA_TYPE']);
            if(in_array($type, $wholeNumberSQLTypes)) {
                $field['DEFAULT_VALUE'] = self::$DEFAULT_VALUES_TYPE['int'];
                if ($field['DEFAULT_VALUE'] == 'NULL') {
                    $field['DATA_TYPE'] = 'int';
                    $field['DATA_TYPE_DISPLAY'] = '?int';
                } else {
                    $field['DATA_TYPE'] = 'int';
                    $field['DATA_TYPE_DISPLAY'] = 'int';
                }  
                
            } else if (in_array($type, $decimalNumberSQLTypes)) {
                $field['DEFAULT_VALUE'] = self::$DEFAULT_VALUES_TYPE['float'];
                if ($field['DEFAULT_VALUE'] == 'NULL') {
                    $field['DATA_TYPE'] = 'float';
                    $field['DATA_TYPE_DISPLAY'] = '?float';
                } else {
                    $field['DATA_TYPE'] = 'float';
                    $field['DATA_TYPE_DISPLAY'] = 'float';
                }
                
            } else if (in_array($type, $stringSQLTypes)) {
                $field['DEFAULT_VALUE'] = self::$DEFAULT_VALUES_TYPE['string'];
                if ($field['DEFAULT_VALUE'] == 'NULL') {
                    $field['DATA_TYPE'] = 'string';
                    $field['DATA_TYPE_DISPLAY'] = '?string';
                } else {
                    $field['DATA_TYPE'] = 'string';
                    $field['DATA_TYPE_DISPLAY'] = 'string';
                }
                
            } else if (in_array($type, $dateTimeSQLTypes)) {
                $field['DEFAULT_VALUE'] = self::$DEFAULT_VALUES_TYPE['DateTime'];
                if ($field['DEFAULT_VALUE'] == 'NULL') {
                    $field['DATA_TYPE'] = 'DateTime';
                    $field['DATA_TYPE_DISPLAY'] = '?DateTime';
                } else {
                    $field['DATA_TYPE'] = 'DateTime';
                    $field['DATA_TYPE_DISPLAY'] = 'DateTime';
                }
                
            } else {
                ErrorThrower::throw("Unknown type \"$type\"");
            }
            // default value for certain fields:
            if (in_array($field['COLUMN_NAME'], array_keys(self::$DEFAULT_VALUE_FIELD))) {
                $field['DEFAULT_VALUE'] = self::$DEFAULT_VALUE_FIELD[$field['COLUMN_NAME']];
            }
        }

        return parent::prepareFields($fields, $filterOut);
    }

    
    public function createManual(string $attributes, bool $visibilityGet = false, bool $visibilitySet = false) {
        $response = new Response();
        // make array from attributes
        $attributes = explode("\n", $attributes);
        $fields = [];

        foreach ($attributes as $attribute) {
            $attributeExplode = explode(' ', $attribute);

            $attribute = trim($attribute);
            $datatype = '';
            // get datatype
            $datatype = $attributeExplode[1]; 
            // get attribute and cut out $ from it
            $attribute = $attributeExplode[2];
            $attribute = str_replace('$', '', $attribute);
            
            $fields[] = ['COLUMN_NAME' => $attribute, 'DATA_TYPE_DISPLAY' => $datatype];
        }

        $gettersAndSetters = $this->generateGettersAndSetters($fields, $visibilityGet, $visibilitySet);

        $output = $gettersAndSetters;

        $response->setStatus(Response::$STATUS_OK);
        $response->setMessage('');
        $response->setHttpResponseCode(Response::$HTTP_STATUS_OK);
        $response->setData($output);

        $response->respond();
    }
}