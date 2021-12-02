<?php

class Connection
{
    private $_dbHostname = "127.0.0.1";
    private $_dbName = "";
    private $_userName = "root";
    private $_dbPassword = "bcd127";
    private $_conexao;

    public function __construct()
    {

        try {
            $this->_conexao = new PDO(
                "mysql:host=$this->_dbHostname;dbname=$this->_dbName;",
                $this->_userName,
                $this->_dbPassword
            );
        } catch (PDOException $e) {

            echo "Connection error: " . $e->getMessage();
        }
    }

    public function returnConnection()
    {
        return $this->_conexao;
    }
}
