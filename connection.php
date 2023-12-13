<?php
// connection.php
class DB
{
    private static $instance = NULL;
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            try {
                $serverName = 'DESKTOP-E3NO62N\MSSQLSERVER01';
                $connectionOptions = array("Database" => "DBMSAssignmentOrderBook");

                self::$instance = new PDO("sqlsrv:Server=$serverName;Database={$connectionOptions['Database']}", null, null);

                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $ex) {
                die("Connection failed: " . $ex->getMessage());
            }
        }

        return self::$instance;
    }
}