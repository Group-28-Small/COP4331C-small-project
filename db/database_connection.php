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

        $this->get_user_by_username_statement = $this->connection->prepare("SELECT user_id, user_username, user_password, user_first_name, user_last_name, created_at, last_on FROM users WHERE user_username=?");
        $this->create_user_statement = $this->connection->prepare("INSERT INTO users (user_username, user_password, user_first_name, user_last_name) VALUES (?, ?, ?, ?) ");
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

    function login_user($username, $password)
    {
        $result = $this->get_user_by_username($username);
        if ($result->num_rows != 1) {
            return ["error" => 500, "error_message" => "Internal error"];
        }
        $user = $result->fetch_object();
        $real_password = $user->user_password;
        if (password_verify($password, $real_password)) {
            $message = ["error" => 0, "error_message" => ""];
            $message["user_id"] = $user->user_id;
            $message["user_username"] = $user->user_username;
            $message["user_first_name"] = $user->user_first_name;
            $message["user_last_name"] = $user->user_last_name;
            $message["user_last_on"] = $user->last_on;
            return $message;
        } else {
            return ["error" => 403, "error_message" => "Incorrect username or password"];
        }
    }

    function create_user($username, $password, $firstname, $lastname)
    {
        // check if this username exists already
        $this->get_user_by_username_statement->bind_param("s", $username);
        $this->get_user_by_username_statement->execute();
        $result = $this->get_user_by_username_statement->get_result();
        if ($result->num_rows > 0) {
            return ["error" => 409, "error_message" => "User exists already!"];
        }

        $this->create_user_statement->bind_param("ssss", $username, $password, $firstname, $lastname);
        $this->create_user_statement->execute();

        $this->get_user_by_username_statement->bind_param("s", $username);
        $this->get_user_by_username_statement->execute();
        $result = $this->get_user_by_username_statement->get_result();
        if ($result->num_rows != 1) {
            return ["error" => 500, "error_message" => "Internal error"];
        }
        $user = $result->fetch_object();
        // TODO: repeated code. move to function?
        $message = ["error" => 0, "error_message" => ""];
        $message["user_id"] = $user->user_id;
        $message["user_username"] = $user->user_username;
        $message["user_first_name"] = $user->user_first_name;
        $message["user_last_name"] = $user->user_last_name;
        $message["user_last_on"] = $user->last_on;
        return $message;
        return $result;
    }
}
