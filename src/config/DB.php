<?php

class DB
{
    private string $dbhost = 'ec2-35-163-49-195.us-west-2.compute.amazonaws.com';
    private string $dbuser = 'diego';
    private string $dbpass = '761834925';
    private string $dbname = 'ayuntamiento';

    public function connect(): PDO
    {
        $mysqlConnectStr = "mysql:host=$this->dbhost;dbname=$this->dbname; charset=utf8";
        $dbConnection = new PDO($mysqlConnectStr, $this->dbuser, $this->dbpass);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbConnection;
    }
}
