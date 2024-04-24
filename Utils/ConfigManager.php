<?php
Namespace Utils;

use Model\Config;

class ConfigManager {

    private Array $configs = [];

    private static ConfigManager $instance;

    private static string $configFilePath = '../config/config.csv';


    private function __construct() {

        $this->loadConfigFile();

    }

    public static function getInstance(): ConfigManager {
        if(!isset(self::$instance)) {
            self::$instance = new ConfigManager();
        }
       return self::$instance;
    }
    
    
    private function loadConfigFile(): void {
        $configFile = $this->lineEndingsToCR(file_get_contents(self::$configFilePath));
        $configLines = explode(chr(ASCII::$CARRIAGE_RETURN), $configFile); // explode on newline

        if(!$configFile) {
            ErrorThrower::throw('Failed to read config-file');
        }

        //each line must have 4 semicolons - id;host;user;password;port
        foreach($configLines as $index => $line) {
            $charsInLine = count_chars($line, 1);
            // count_chars() returns assoc array - ascii_key => count of occurences
            if(!isset($charsInLine[ASCII::$SEMICOLON]) || $charsInLine[ASCII::$SEMICOLON] != 4) { 
                ErrorThrower::throw('Invalid config-file format (Line '.($index+1).')');
            }

            $config = explode(';', $line);
            $this->configs[] = new Config($config[0], $config[1], $config[2], $config[3], $config[4]);

        }
    }

    private function writeToConfigFile(Config $config): bool {

        $configLine = chr(ASCII::$LINE_FEED);
        $configLine .= "{$config->getId()};{$config->getHost()};{$config->getUsername()};{$config->getPassword()};{$config->getPort()}";
 
        return (bool) file_put_contents(self::$configFilePath, $configLine, FILE_APPEND);
    }

    public function createConfig($host, $user, $password, $port): bool {

        $id = count($this->configs) + 1;
        $config = new Config($id, $host, $user, $password, $port);

        $this->configs[] = $config;
        return $this->writeToConfigFile($config);
    }


    public function getConfigById(int $id): ?Config {

        foreach($this->configs as $config) {
            if ($config->getId() == $id) {
                return $config;
            }
        }
        return NULL;
    }


    private function lineEndingsToCR($string) {
        $linefeed = chr(ASCII::$LINE_FEED);
        $carriageReturn = chr(ASCII::$CARRIAGE_RETURN);

        $string = str_replace($linefeed.$carriageReturn, $carriageReturn, $string);
        $string = str_replace($carriageReturn.$linefeed, $carriageReturn, $string);
        $string = str_replace($linefeed, $carriageReturn, $string);

        return $string;
    }

    public function getConfigs(): array {
        return $this->configs;
    }
}

?>