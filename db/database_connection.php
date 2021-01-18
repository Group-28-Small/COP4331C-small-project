<?php
class DBConnection
{
    private $connection;
    private $connected = false;
    function __construct()
    {
        $username = "admin";

        // if running locally, set these environment variables accordingly

        // never, ever put a password in code. instead, read it from either a file, or an environment variable
        $password = getenv("RDS_PASSWORD") ?: 'my-sql-password';

        $database = getenv("RDS_DB_NAME") ?: 'contacts';
        $host = getenv("RDS_HOSTNAME") ?: 'localhost';
        $port = getenv("RDS_PORT") ?: false;

        // connect to database
        $this->connection = new mysqli($host, $username, $password, $database, $port);

        if ($this->connection->connect_errno) {
            echo "Failed to connect to MySQL: " . $this->connection->connect_error;
            return;
        } else {
            $this->connected = true;
        }
    }

    function is_connected()
    {
        return $this->connected;
    }

    function send_raw_query($query)
    {
        if (!$this->connected) {
            throw new Exception("not connected!");
        }
        return $this->connection->query($query);
    }
}
