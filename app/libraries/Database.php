<?php
class Database
{
    private $dbHost = DB_HOST;
    private $dbUser = DB_USER;
    private $dbPass = DB_PASS;
    private $dbName = DB_NAME;

    private $statement;
    private $dbHandler;
    private $error;


    // connection to database
    public function __construct()
    {
        $conn = "mysql:host=$this->dbHost;dbname=$this->dbName";
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        try {
            $this->dbHandler = new PDO($conn, $this->dbUser, $this->dbPass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    // prepare queries
    public function query($sql)
    {
        $this->statement = $this->dbhandler->prepare($sql);
    }

    // bind params to query
    public function bind($parameter, $value, $type = null)
    {
        switch (is_null($type)) {
            case is_int($value):
                $type = PDO::PARAM_INT;
                break;

            case is_bool($value):
                $type = PDO::PARAM_BOOL;
                break;

            case is_null($value):
                $type = PDO::PARAM_NULL;
                break;

            default:
                $type = PDO::PARAM_STR;
                break;
        }
        $this->statement->bindValue($parameter, $value, $type);
    }

    // execute query
    public function execute()
    {
        return $this->statement->execute();
    }

    // return all data from query execute
    public function resultSet()
    {
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_OBJ);
    }

    // return single data from query execute
    public function single()
    {
        $this->execute();
        return $this->statement->fetch(PDO::FETCH_OBJ);
    }

    // return row count
    public function rowCount()
    {
        return $this->statement->rowCount();
    }
}
