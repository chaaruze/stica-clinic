<?php

class Database
{
    private $dbPath;
    private $dbh;
    private $stmt;
    private $error;

    public function __construct()
    {
        // SQLite database path
        $this->dbPath = DB_PATH;

        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        // Create PDO instance for SQLite
        try {
            $this->dbh = new PDO('sqlite:' . $this->dbPath, null, null, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die("Database Connection Failed: " . $this->error . " <br>Please run: php database/migrate.php");
        }
    }

    public function query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function prepare($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
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
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute()
    {
        return $this->stmt->execute();
    }

    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    // Get raw PDO connection (for migrations or special queries)
    public function getConnection()
    {
        return $this->dbh;
    }
}
