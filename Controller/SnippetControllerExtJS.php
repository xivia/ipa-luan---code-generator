<?php
namespace Controller;

use Model\Config;
use Model\Database;
use Model\Table;

use Utils\Response;
use Utils\ErrorThrower;
use Utils\ConfigManager;

Class SnippetControllerExtJs extends SnippetControllerBasic {

    public function generateModel($configId, $databaseId, $tableId) {
        $response = new Response();

        $config = ConfigManager::getInstance()->getConfigById($configId);
        $database = Database::getById($config, $databaseId);
        $table = Table::getById($database, $tableId);

        $fields = $this->prepareFields($table->getFields());
        $className = $this->convertToClassname($table->getName());
        $newLine1 = '<br>';
        $newLine2 = '<br><br>';

        $header = $this->generateModelHeader($className);
        $body = $this->generateModelBody($fields);
        $footer = $this->generateFooter();

        $output = $header.
                  $newLine2.
                  $body.
                  $newLine2.
                  $footer;

        $response->setStatus(Response::$STATUS_OK);
        $response->setMessage('');
        $response->setHttpResponseCode(Response::$HTTP_STATUS_OK);
        $response->setData($output);

        $response->respond();
    }

    public function generateGridList($configId, $databaseId, $tableId) {
        $response = new Response();

        $config = ConfigManager::getInstance()->getConfigById($configId);
        $database = Database::getById($config, $databaseId);
        $table = Table::getById($database, $tableId);

        $fields = $this->prepareFields($table->getFields());
        $className = $this->convertToClassname($table->getName());
        $packetName = strtolower($className);
        $newLine1 = '<br>';
        $newLine2 = '<br><br>';

        $header = $this->generateGridListHeader($className, $packetName);
        $body = $this->generateGridListBody($fields, $className);
        $footer = $this->generateFooter();

        $output = $header.
                  $newLine2.
                  $body.
                  $newLine2.
                  $footer;

        $response->setStatus(Response::$STATUS_OK);
        $response->setMessage('');
        $response->setHttpResponseCode(Response::$HTTP_STATUS_OK);
        $response->setData($output);

        $response->respond();
    }

    public function generateAddDialog($configId, $databaseId, $tableId) {
        $response = new Response();

        $config = ConfigManager::getInstance()->getConfigById($configId);
        $database = Database::getById($config, $databaseId);
        $table = Table::getById($database, $tableId);

        $fields = $this->prepareFields($table->getFields());
        $className = $this->convertToClassname($table->getName());
        $packetName = strtolower($className);
        $newLine1 = '<br>';
        $newLine2 = '<br><br>';

        $header = $this->generateDialogHeader($className, $packetName, 'Add');
        $body = $this->generateDialogBody($fields, $className, 'POST');
        $footer = $this->generateFooter();

        $output = $header.
                  $newLine2.
                  $body.
                  $newLine2.
                  $footer;

        $response->setStatus(Response::$STATUS_OK);
        $response->setMessage('');
        $response->setHttpResponseCode(Response::$HTTP_STATUS_OK);
        $response->setData($output);

        $response->respond();
    }

    public function generateEditDialog($configId, $databaseId, $tableId) {
        $response = new Response();

        $config = ConfigManager::getInstance()->getConfigById($configId);
        $database = Database::getById($config, $databaseId);
        $table = Table::getById($database, $tableId);

        $fields = $this->prepareFields($table->getFields());
        $className = $this->convertToClassname($table->getName());
        $packetName = strtolower($className);
        $newLine1 = '<br>';
        $newLine2 = '<br><br>';

        $header = $this->generateDialogHeader($className, $packetName, 'Edit');
        $body = $this->generateDialogBody($fields, $className, 'PUT');
        $footer = $this->generateFooter();

        $output = $header.
                  $newLine2.
                  $body.
                  $newLine2.
                  $footer;

        $response->setStatus(Response::$STATUS_OK);
        $response->setMessage('');
        $response->setHttpResponseCode(Response::$HTTP_STATUS_OK);
        $response->setData($output);

        $response->respond();
    }

    public function generateInfoDialog($configId, $databaseId, $tableId) {
        $response = new Response();

        $config = ConfigManager::getInstance()->getConfigById($configId);
        $database = Database::getById($config, $databaseId);
        $table = Table::getById($database, $tableId);

        $fields = $this->prepareFields($table->getFields());
        $className = $this->convertToClassname($table->getName());
        $packetName = strtolower($className);
        $newLine1 = '<br>';
        $newLine2 = '<br><br>';

        $header = $this->generateDialogHeader($className, $packetName, 'Info');
        $body = $this->generateDialogBody($fields, $className, 'GET');
        $footer = $this->generateFooter();

        $output = $header.
                  $newLine2.
                  $body.
                  $newLine2.
                  $footer;

        $response->setStatus(Response::$STATUS_OK);
        $response->setMessage('');
        $response->setHttpResponseCode(Response::$HTTP_STATUS_OK);
        $response->setData($output);

        $response->respond();
    }

    private function generateModelHeader($className): string {

        $content = "Ext.define('swo.model.$className', {";
        $content .= "<br>";
        $content .= "{$this->indent(1)}extend: 'Ext.data.Model',";

        return $content;
    }

    private function generateModelBody($fields): string {

        $content = "{$this->indent(1)}fields: [";
        $content .= "<br>";

        foreach ($fields as $field) {

            $name = $field['COLUMN_NAME'];
            $lname = lcfirst($name);
            $type = $field['DATA_TYPE'];

            $typeSpaces = $field['DATA_TYPE_SPACES'];
            $nameSpaces = $field['COLUMN_NAME_SPACES'];

            $content .= "{$this->indent(2)}{ name: '$lname',$nameSpaces type: '$type' },<br>";
        }
        $content = $this->removeLastOccurrence($content, ',');
        $content .= "{$this->indent(1)}]";
        return $content;
        
    }

    private function generateGridListHeader($className, $packetName) {

        $content = "Ext.define('swo.view.$packetName.List', {";
        $content .= "<br>";
        $content .= "{$this->indent(1)}extend: 'Ext.grid.Panel',";
        $content .= "<br>";
        $content .= "<br>";
        $content .= "{$this->indent(1)}alias: 'widget.{$packetName}_list',";
        $content .= "<br>";
        $content .= "{$this->indent(1)}id: '{$packetName}_list'";
        $content .= "<br>";
        $content .= "<br>";
        $content .= "{$this->indent(1)}requires: [";
        $content .= "<br>";
        $content .= "{$this->indent(2)}'swo.store.$className', 'swo.controller.$className', // store+controller";
        $content .= "<br>";
        $content .= "{$this->indent(2)}'swo.utils.GlobalFunctions', // functions";
        $content .= "<br>";
        $content .= "{$this->indent(2)}'swo.view.$packetName.dialogs.CreateModal', // dialogs";
        $content .= "<br>";
        $content .= "{$this->indent(2)}'swo.view.$packetName.dialogs.EditModal',";
        $content .= "<br>";
        $content .= "{$this->indent(2)}'swo.view.$packetName.dialogs.DeleteDialog',";
        $content .= "<br>";
        $content .= "{$this->indent(2)}// 'combobox stores, toolbar, etc.'";
        $content .= "<br>";
        $content .= "{$this->indent(1)}],";
        $content .= "<br>";
        $content .= "<br>";
        $content .= "{$this->indent(1)}store: {type: '$className'},";
        $content .= "<br>";
        $content .= "{$this->indent(1)}controller: '$className',";
        $content .= "<br>";
        $content .= "<br>";
        $content .= "{$this->indent(1)}layout: 'fit',";
        $content .= "<br>";
        $content .= "{$this->indent(1)}title: 'Service Articles',";
        $content .= "<br>";
        $content .= "{$this->indent(1)}// tbar";
        $content .= "<br>";
        $content .= "{$this->indent(1)}//dockedItems: [{";
        $content .= "<br>";
        $content .= "{$this->indent(2)}//xtype: '{$className}Toolbar',";
        $content .= "<br>";
        $content .= "{$this->indent(2)}//dock: 'top',";
        $content .= "<br>";
        $content .= "{$this->indent(1)}//}],";

        return $content;

    }

    private function generateGridListBody($fields, $className) {

        $content = "{$this->indent(1)}columns: [";
        $content .= "<br>";

        foreach ($fields as $field) {

            $name = $field['COLUMN_NAME'];
            $uname = ucfirst($name);
            $lname = lcfirst($name);
            $type = $field['DATA_TYPE'];

            $typeSpaces = $field['DATA_TYPE_SPACES'];
            $nameSpaces = $field['COLUMN_NAME_SPACES'];
            
            $content .= "{$this->indent(2)}{";
            $content .= "<br>";
            $content .= "{$this->indent(3)}text: '$uname'";
            $content .= "<br>";
            $content .= "{$this->indent(3)}dataIndex: '$lname'";
            $content .= "<br>";
            $content .= "{$this->indent(3)}hidden: false";
            $content .= "<br>";
            $content .= "{$this->indent(3)}flex: 1,";
            $content .= "<br>";
            $content .= "{$this->indent(3)}//scope: swo.utils.GlobalFunctions,";
            $content .= "<br>";
            $content .= "{$this->indent(3)}//renderer: 'render{$name}Grid'";
            $content .= "<br>";
            $content .= "{$this->indent(2)}},";
            $content .= "<br>";
        }

        $content = $this->removeLastOccurrence($content, ',');

        $content .= "{$this->indent(1)}],";
        $content .= "<br>";
        $content .= "<br>";
        $content .= "{$this->indent(1)}bbar: {";
        $content .= "<br>";
        $content .= "{$this->indent(2)}xtype: 'pagingtoolbar',";
        $content .= "<br>";
        $content .= "{$this->indent(2)}displayInfo: true";
        $content .= "<br>";
        $content .= "{$this->indent(2)}beforePageText: 'Seite',";
        $content .= "<br>";
        $content .= "{$this->indent(2)}afterPageText: 'von {0}',";
        $content .= "<br>";
        $content .= "{$this->indent(2)}listeners: {";
        $content .= "<br>";
        $content .= "{$this->indent(3)}change: 'onPageEvent'";
        $content .= "<br>";
        $content .= "{$this->indent(2)}}";
        $content .= "<br>";
        $content .= "{$this->indent(1)}},";
        $content .= "<br>";
        $content .= "<br>";
        $content .= "{$this->indent(1)}listeners: {";
        $content .= "<br>";
        $content .= "{$this->indent(2)}select: 'onItemSelected',";
        $content .= "<br>";
        $content .= "{$this->indent(2)}itemdblclick: 'onDoubleClick'";
        $content .= "<br>";
        $content .= "{$this->indent(1)}}";

        return $content;
    }

    private function generateDialogHeader($className, $packetName, $dialogType): string {

        $content = "Ext.define('swo.view.$packetName.dialogs.{$dialogType}Modal', {";
        $content .= "<br>";
        $content .= "{$this->indent(1)}extend: 'Ext.window.Window',";
        $content .= "<br>";
        $content .= "<br>";
        $content .= "{$this->indent(1)}requires: ['Ext.form.Panel', 'swo.controller.$className'],";
        $content .= "<br>";
        $content .= "{$this->indent(1)}controller: '$className',";
        $content .= "<br>";
        $content .= "<br>";
        $content .= "{$this->indent(1)}title: '<b>$dialogType Service Article<b>',";
        $content .= "<br>";
        $content .= "<br>";
        $content .= "{$this->indent(1)}modal: true,";
        $content .= "<br>";
        $content .= "{$this->indent(1)}//alwaysOnTop: true,";
        $content .= "<br>";
        $content .= "{$this->indent(1)}height: 600,";
        $content .= "<br>";
        $content .= "{$this->indent(1)}width: 500,";
        $content .= "<br>";
        $content .= "{$this->indent(1)}overflowY: auto,";

        return $content;
    }

    private function generateDialogBody($fields, $className, $requestMethod) {

        $defaults = "";
        $urlLine = "";
        $listeners = "";
        $buttons = "";
        if ($requestMethod == 'GET') {
            // info dialog
            $defaults .= "{$this->indent(2)}layout: 'vbox',";
            $defaults .= "<br>";
            $defaults .= "<br>";
            $defaults .= "{$this->indent(2)}defaults: {";
            $defaults .= "<br>";
            $defaults .= "{$this->indent(3)}labelAlign: 'left',";
            $defaults .= "<br>";
            $defaults .= "{$this->indent(3)}labelWidth: 180,";
            $defaults .= "<br>";
            $defaults .= "{$this->indent(2)}},";
            $defaults .= "<br>";

            $urlLine .= "{$this->indent(2)}url: swo.utils.Environment.getRemoteDomain().concat('$className/{0}'),";

            $listeners .= "{$this->indent(2)}listeners: {";
            $listeners .= "<br>";
            $listeners .= "{$this->indent(3)}afterrender: function(component) {";
            $listeners .= "<br>";
            $listeners .= "{$this->indent(4)}// add id to url";
            $listeners .= "<br>";
            $listeners .= "{$this->indent(4)}let selectedData = Ext.getCmp('erparticleservice_list').getSelectionModel().getSelected().items[0];";
            $listeners .= "<br>";
            $listeners .= "{$this->indent(4)}let parentWindow = component.up('window');";
            $listeners .= "<br>";
            $listeners .= "{$this->indent(4)}let form = component.form;";
            $listeners .= "<br>";
            $listeners .= "<br>";
            $listeners .= "{$this->indent(4)}form.url = Ext.String.format(form.url, selectedData.id);";
            $listeners .= "<br>";
            $listeners .= "{$this->indent(4)}form.loadRecord(selectedData);";
            $listeners .= "<br>";
            $listeners .= "<br>";
            $listeners .= "{$this->indent(4)}parentWindow.setTitle(parentWindow.getTitle().concat(` &laquo;\${Object.values(selectedData.data)[0]}&raquo;`));";
            $listeners .= "<br>";
            $listeners .= "{$this->indent(3)}}";
            $listeners .= "<br>";
            $listeners .= "{$this->indent(2)}},";
            $listeners .= "<br>";

            $buttons = "{$this->indent(2)}buttons: [";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(3)}{";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(4)}text: 'OK',";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(4)}handler: function() {";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(5)}this.up('window').close();";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(4)}}";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(3)}}";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(2)}],";
            $buttons .= "<br>";

        } else if ($requestMethod == 'PUT') {
            // edit dialog
            $defaults .= "{$this->indent(2)}defaultType: 'textfield',";
            $defaults .= "<br>";
            $defaults .= "<br>";
            $defaults .= "{$this->indent(2)}defaults: {";
            $defaults .= "<br>";
            $defaults .= "{$this->indent(3)}size: 38, // deprecated, but the only way it works... width, minWidth, etc. do not...";
            $defaults .= "<br>";
            $defaults .= "{$this->indent(3)}labelAlign: 'left',";
            $defaults .= "<br>";
            $defaults .= "{$this->indent(3)}labelWidth: 160,";
            $defaults .= "<br>";
            $defaults .= "{$this->indent(2)}},";
            $defaults .= "<br>";

            $urlLine .= "{$this->indent(2)}url: swo.utils.Environment.getRemoteDomain().concat('$className/{0}'),";
            $urlLine .= "<br>";
            $urlLine .= "{$this->indent(2)}method: '$requestMethod',";
            $urlLine .= "<br>";
            $urlLine .= "{$this->indent(2)}jsonSubmit: true,";

            $listeners .= "{$this->indent(2)}listeners: {";
            $listeners .= "<br>";
            $listeners .= "{$this->indent(3)}afterrender: function(component) {";
            $listeners .= "<br>";
            $listeners .= "{$this->indent(4)}// add id to url";
            $listeners .= "<br>";
            $listeners .= "{$this->indent(4)}let selectedData = Ext.getCmp('erparticleservice_list').getSelectionModel().getSelected().items[0];";
            $listeners .= "<br>";
            $listeners .= "{$this->indent(4)}let parentWindow = component.up('window');";
            $listeners .= "<br>";
            $listeners .= "{$this->indent(4)}let form = component.form;";
            $listeners .= "<br>";
            $listeners .= "<br>";
            $listeners .= "{$this->indent(4)}form.url = Ext.String.format(form.url, selectedData.id);";
            $listeners .= "<br>";
            $listeners .= "{$this->indent(4)}form.loadRecord(selectedData);";
            $listeners .= "<br>";
            $listeners .= "<br>";
            $listeners .= "{$this->indent(4)}parentWindow.setTitle(parentWindow.getTitle().concat(` &laquo;\${Object.values(selectedData.data)[0]}&raquo;`));";
            $listeners .= "<br>";
            $listeners .= "{$this->indent(3)}}";
            $listeners .= "<br>";
            $listeners .= "{$this->indent(2)}},";
            $listeners .= "<br>";

            $buttons = "{$this->indent(2)}buttons: [";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(3)}{";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(4)}text: 'Save',";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(4)}formBind: true,";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(4)}handler: 'handleEditSave'";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(4)}},{";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(4)}text: 'Cancel',";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(4)}handler: function() {";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(5)}this.up('window').close();";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(4)}}";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(3)}}";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(2)}],";
            $buttons .= "<br>";

        } else if ($requestMethod == 'POST') {
            // add dialog
            $defaults .= "{$this->indent(2)}defaultType: 'textfield',";
            $defaults .= "<br>";
            $defaults .= "<br>";
            $defaults .= "{$this->indent(2)}defaults: {";
            $defaults .= "<br>";
            $defaults .= "{$this->indent(3)}size: 38, // deprecated, but the only way it works... width, minWidth, etc. do not...";
            $defaults .= "<br>";
            $defaults .= "{$this->indent(3)}labelAlign: 'left',";
            $defaults .= "<br>";
            $defaults .= "{$this->indent(3)}labelWidth: 160,";
            $defaults .= "<br>";
            $defaults .= "{$this->indent(2)}},";
            $defaults .= "<br>";

            $urlLine .= "{$this->indent(2)}url: swo.utils.Environment.getRemoteDomain().concat('$className'),";
            $urlLine .= "<br>";
            $urlLine .= "{$this->indent(2)}method: '$requestMethod',";
            $urlLine .= "<br>";
            $urlLine .= "{$this->indent(2)}jsonSubmit: true,";

            $listeners .= "";

            $buttons = "{$this->indent(2)}buttons: [";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(3)}{";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(4)}text: 'Add',";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(4)}formBind: true,";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(4)}handler: 'handleCreateSave'";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(4)}},{";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(4)}text: 'Cancel',";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(4)}handler: function() {";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(5)}this.up('window').close();";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(4)}}";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(3)}}";
            $buttons .= "<br>";
            $buttons .= "{$this->indent(2)}],";
            $buttons .= "<br>";
        }

        $content = "{$this->indent(1)}items: {";
        $content .= "<br>";
        $content .= "{$this->indent(2)}xtype: 'form',";
        $content .= "<br>";
        $content .= $defaults;
        $content .= "{$this->indent(2)}bodyPadding: 10,";
        $content .= "<br>";
        $content .= "<br>";
        $content .= $urlLine;
        $content .= "<br>";
        $content .= "<br>";
        $content .= "{$this->indent(2)}items: [";
        $content .= "<br>";

        foreach ($fields as $field) {

            if ($field['DATA_TYPE'] == 'int' || $field['DATA_TYPE'] == 'float') {
                $content .= "{$this->indent(3)}{";
                $content .= "<br>";
                $content .= "{$this->indent(4)}xtype: 'numberfield'";
                $content .= "<br>";
                $content .= "{$this->indent(4)}fieldLabel: '{$field['COLUMN_NAME']}',";
                $content .= "<br>";
                $content .= "{$this->indent(4)}labelSeperator: '',";
                $content .= "<br>";
                $content .= "{$this->indent(4)}name: '{$field['COLUMN_NAME']}',";
                $content .= "<br>";
                $content .= "{$this->indent(4)}allowBlank: false,";
                $content .= "<br>";
                $content .= "{$this->indent(4)}allowDecimals: false,";
                $content .= "<br>";
                $content .= "{$this->indent(4)}allowExponential: false,";
                $content .= "<br>";
                $content .= "{$this->indent(4)}hideTrigger: true,";
                $content .= "<br>";
                $content .= "{$this->indent(4)}keyNavEnabled: false,";
                $content .= "<br>";
                $content .= "{$this->indent(4)}mouseWheelEnabled: false";
                $content .= "<br>";
                $content .= "{$this->indent(3)}},";
                $content .= "<br>";
            } else {
                $content .= "{$this->indent(3)}{";
                $content .= "<br>";
                $content .= "{$this->indent(4)}xtype: 'textfield',";
                $content .= "<br>";
                $content .= "{$this->indent(4)}fieldLabel: '{$field['COLUMN_NAME']}',";
                $content .= "<br>";
                $content .= "{$this->indent(4)}fieldSeperator: '',";
                $content .= "<br>";
                $content .= "{$this->indent(4)}name: '{$field['COLUMN_NAME']}',";
                $content .= "<br>";
                $content .= "{$this->indent(4)}allowBlank: false";
                $content .= "<br>";
                $content .= "{$this->indent(3)}},";
                $content .= "<br>";
            }
        }

        $content = $this->removeLastOccurrence($content, ',');
        $content .= "<br>";
        $content .= $listeners;
        $content .= $buttons;

        return $content;
        
    }

    private function generateFooter() : string {
        return '});';
    }


    protected function prepareFields(array $fields, array $filterOut = []): array {

        // translate SQL data types to ExtJs
        $wholeNumberSQLTypes = ['int', 'integer', 'bigint', 'smallint', 'tinyint'];
        $decimalNumberSQLTypes = ['dec', 'decimal', 'float', 'double', 'double precision'];
        $stringSQLTypes = ['char', 'varchar', 'tinyblob', 'mediumblob', 'blob', 'longblob', 'tinytext', 'mediumtext', 'text', 'longtext'];
        $dateTimeSQLTypes = ['date', 'datetime', 'timestamp', 'time', 'year'];


        foreach ($fields as &$field) {

            $type = strtolower($field['DATA_TYPE']);
            if(in_array($type, $wholeNumberSQLTypes)) {
                $field['DATA_TYPE'] = 'int';
                $field['DATA_TYPE_DISPLAY'] = 'int';

            } else if (in_array($type, $decimalNumberSQLTypes)) {
                $field['DATA_TYPE'] = 'float';
                $field['DATA_TYPE_DISPLAY'] = 'float';

            } else if (in_array($type, $stringSQLTypes)) {
                $field['DATA_TYPE'] = 'string';
                $field['DATA_TYPE_DISPLAY'] = 'string';

            } else if (in_array($type, $dateTimeSQLTypes)) {
                $field['DATA_TYPE'] = 'date';
                $field['DATA_TYPE_DISPLAY'] = 'date';
                
            } else {
                ErrorThrower::throw("Unknown type \"$type\"");
            }

        }

        return parent::prepareFields($fields, $filterOut);
    }
}