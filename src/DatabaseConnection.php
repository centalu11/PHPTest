<?php

namespace Cent\PhpTest;

class DatabaseConnection
{
    private $host;
    private $username;
    private $password;
    private $dbName;
    private $connection;

    public function __construct()
    {
        $this->host = $_ENV['CONNECTION_HOST'] ?? 'localhost';
        $this->username = $_ENV['CONNECTION_USERNAME'] ?? 'root';
        $this->password = $_ENV['CONNECTION_PASSWORD'] ?? '';
        $this->dbName = $_ENV['DATABASE_NAME'] ?? 'phptest';
    }

    public function connect()
    {
        try {
            $this->connection = new \mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->dbName
            );

            if ($this->connection->connect_errno) {
                throw new \Exception('Failed to connect to database: ' . $this->connection->connect_errno);
            }

            return $this;
        } catch (\Exception $err) {
            throw $err;
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
