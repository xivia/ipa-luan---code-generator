<?php
Namespace Model;

use Utils\MysqliDB;

class Config implements \JsonSerializable {

    private int $id;

    private string   $host;
    private string   $username;
    private string   $password;
    private int      $port;

    private static int $counter = 0;


    public function __construct($id, $host, $username, $password, $port) {
        
        $this->id = $id;
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->port = $port;
    }

    public function jsonSerialize(): mixed {
        return [
            'id'       => $this->id,
            'host'     => $this->host,
            'username' => $this->username,
            'password' => $this->password,
            'port'     => $this->port
        ];
    }

    public function getId(): int {
        return $this->id;
    }

    public function getHost(): string {
        return $this->host;
    }

    public function setHost(string $host) {
        $this->host = $host;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function setUsername(string $username) {
        $this->username = $username;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password) {
        $this->password = $password;
    }

    public function getPort(): string {
        return $this->port;
    }

    public function setPort(string $port) {
        $this->port = $port;
    }
  
}

?>