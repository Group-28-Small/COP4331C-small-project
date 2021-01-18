<?php
class DBConnection
{
    private $connection;
    private $connected = false;

    private $get_user_by_username_statement;
    private $create_user_statement;

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

        $this->get_user_by_username_statement = $this->connection->prepare("SELECT (user_id, user_username, user_first_name, user_last_name, created_at, last_on) FROM (users) WHERE username=?");
        $this->create_user_statement = $this->connection->prepare("INSERT INTO users (user_password, user_username, user_first_name, user_last_name) VALUES (?, ?, ?, ?) ");
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

    function get_user_by_username($user_name)
    {
        $this->get_user_by_username_statement->bind_param("s", $user_name);
        $this->get_user_by_username_statement->execute();
        $result = $this->get_user_by_username_statement->get_result();
        return $result;
    }

    function create_user($username, $password, $firstname, $lastname)
    {
        $this->create_user_statement->bind_param("ssss", $username, $password, $firstname, $lastname);
        $this->get_user_by_id_statement->execute();

        $this->get_user_by_username_statement->bind_param("s", $username);
        $this->get_user_by_username_statement->execute();
        $result = $this->get_user_by_username_statement->get_result();
        return $result;
    }
}
