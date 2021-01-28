<?php
class DBConnection
{
    private $connection;
    private $connected = false;

    private $get_user_by_username_statement;
    private $create_user_statement;
    private $create_contact_statement;
    private $get_contact_by_id_statement;

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
        $this->create_contact_statement = $this->connection->prepare("INSERT INTO contacts (user_first_name, user_last_name, user_phone, user_email) VALUES (?, ?, ?, ?) ");
        $this->get_contact_by_id_statement = $this->connection->prepare("SELECT contact_id, user_first_name, user_last_name,user_phone, user_email, created_at from contacts WHERE contact_id=?");
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
            $message["id"] = $user->user_id;
            $message["username"] = $user->user_username;
            $message["firstName"] = $user->user_first_name;
            $message["lastName"] = $user->user_last_name;
            $message["user_last_on"] = $user->last_on;
            return $message;
        } else {
            return ["error" => 403, "error_message" => "Incorrect username or password", "id" => -1];
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
            return ["error" => 500, "error_message" => "Internal error", "id" => -1];
        }
        $user = $result->fetch_object();
        // TODO: repeated code. move to function?
        $message = ["error" => 0, "error_message" => ""];
        $message["id"] = $user->user_id;
        $message["username"] = $user->user_username;
        $message["firstName"] = $user->user_first_name;
        $message["lastName"] = $user->user_last_name;
        $message["user_last_on"] = $user->last_on;
        return $message;
    }

    function create_contact($firstName, $lastName, $phone, $email)
    {
        $this->create_contact_statement->bind_param("ssss", $firstName, $lastName, $phone, $email);
        $this->create_contact_statement->execute();
        $record_id = $this->create_contact_statement->insert_id;
        // should we check for errors? nothing really could go wrong here...
        $this->get_contact_by_id->bind_param("i", $record_id);
        $this->get_contact_by_id->execute();
        $result = $this->get_contact_by_id_statement->get_result();
        $contact = $result->fetch_object();

        $message = ["error" => 0, "error_message" => ""];
        $message["contact_id"] = $contact->contact_id;
        $message["firstName"] = $contact->user_first_name;
        $message["lastName"] = $contact->user_last_name;
        $message["phone"] = $contact->user_phone;
        $message["email"] = $contact->user_email;
        $message["created_at"] = $contact->created_at;
        return $message;
    }
}
