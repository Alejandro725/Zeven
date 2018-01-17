<?php
class Database
{
    private static $connection;
    private static $statement;
    public static $id;
    public static $error;

    private static function connect()
    {
        $server = "localhost";
        $database = "tienda";
        $username = "Melendez";
        $password = "soy_guapisimo";
        $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8");     
        try
        {
            @self::$connection = new PDO("mysql:host=".$server."; dbname=".$database, $username, $password, $options);
        }
        catch(PDOException $exception)
        {
            throw new Exception($exception->getCode());
        }
    }

    private static function desconnect()
    {
        self::$error = self::$statement->errorInfo();
        self::$connection = null;
    }

    public static function executeRow($query, $values)
    {
        self::connect();
        self::$statement = self::$connection->prepare($query);
        $state = self::$statement->execute($values);
        self::$id = self::$connection->lastInsertId();
        self::desconnect();
        return $state;
    }

    public static function getRow($query, $values)
    {
        self::connect();
        self::$statement = self::$connection->prepare($query);
        self::$statement->execute($values);
        self::desconnect();
        return self::$statement->fetch();
    }

    public static function getRows($query, $values)
    {
        self::connect();
        self::$statement = self::$connection->prepare($query);
        self::$statement->execute($values);
        self::desconnect();
        return self::$statement->fetchAll();
    }
}
?>