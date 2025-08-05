<?php

class DatabaseConnection
{
    private string $hostname = "localhost";
    private string $username = "root";
    private string $password = "";
    private string $database = "CRM_BMS";
    private ?object $connection = null;

    public function start(): object|bool
    {
        try
        {
            $this->connection = new PDO("mysql:host=$this->hostname;dbname=$this->database;charset=UTF8", $this->username, $this->password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            return $this->connection;
        }
        catch (PDOException $e)
        {
            error_log("\n\n" . date("Y-m-d H:i:s") . " | " . $e, 3, destination: "./errors.log");
            return false;
        }
    }
}
