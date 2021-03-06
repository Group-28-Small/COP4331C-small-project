<?php
class DBConnection
{
    private $connection;
    private $connected = false;

    private $get_user_by_username_statement;
    private $create_user_statement;
    private $create_contact_statement;
    private $get_contact_by_id_statement;
    private $delete_contact_by_id_statement;
    private $get_contacts_by_user_statement;
    private $search_contacts_statement;
    private $update_contact_by_id_statement;

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
        $this->create_contact_statement = $this->connection->prepare("INSERT INTO contacts (first_name, last_name, phone, email, contact_owner) VALUES (?, ?, ?, ?, ?) ");
        $this->get_contact_by_id_statement = $this->connection->prepare("SELECT contact_id, first_name, last_name, phone, email, created_at from contacts WHERE contact_id=?");
        $this->get_contacts_by_user_statement = $this->connection->prepare("SELECT contact_id, first_name, last_name, phone, email, created_at from contacts WHERE contact_owner=?");
        $this->search_contacts_statement = $this->connection->prepare("SELECT contact_id, first_name, last_name, phone, email, created_at from contacts WHERE contact_owner=? AND (first_name LIKE ? or last_name LIKE ? or phone LIKE ? or email LIKE ?)");
        // TODO: get_all_contacts_for_user
        // TODO: search_user_contacts (by ???)
        // TODO: create, update...

        // Deletes based off contact id and owner id
        $this->delete_contact_by_id_statement = $this->connection->prepare("DELETE from contacts WHERE contact_id=? AND contact_owner=?");

        // Edits contact based off contact id and owner id
        /*
        UPDATE table_name
        SET some_column = some_value
        WHERE some_column = some_value;
        */
        $this->update_contact_by_id_statement = $this->connection->prepare("UPDATE contacts SET first_name=?, last_name=?, phone=?, email=? WHERE contact_id=? AND contact_owner=?"); 
    }

    function is_connected()
    {
        return $this->connected;
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

    function create_contact($firstName, $lastName, $phone, $email, $contact_owner)
    {
        $this->create_contact_statement->bind_param("ssssi", $firstName, $lastName, $phone, $email, $contact_owner);
        $this->create_contact_statement->execute();
        $record_id = $this->create_contact_statement->insert_id;

        // should we check for errors? nothing really could go wrong here...
        $this->get_contact_by_id_statement->bind_param("i", $record_id);
        $this->get_contact_by_id_statement->execute();
        $result = $this->get_contact_by_id_statement->get_result();
        if ($result->num_rows != 1) {
            return ["error" => 500, "error_message" => "Internal error", "id" => -1];
        }

        $contact = $result->fetch_object();

        $message = ["error" => 0, "error_message" => ""];
        $message["contact_id"] = $contact->contact_id;
        $message["firstName"] = $contact->first_name;
        $message["lastName"] = $contact->last_name;
        $message["phone"] = $contact->phone;
        $message["email"] = $contact->email;
        $message["created_at"] = $contact->created_at;
        return $message;
    }

    function delete_contact_by_id($contact_id, $owner_id)
    {
        $this->delete_contact_by_id_statement->bind_param("ii", $contact_id, $owner_id);
        $this->delete_contact_by_id_statement->execute();

        // Check it was deleted?
        // Change contact_id to record_id for consistency?
        $this->get_contact_by_id_statement->bind_param("i", $contact_id);
        $this->get_contact_by_id_statement->execute();
        $result = $this->get_contact_by_id_statement->get_result();
        if ($result->num_rows != 0)
        {
            return ["error" => 500, "error_message" => "Internal error", "id" => -1];
        }
        // Return statement necessary?
        return true;
    }
    
    function get_contacts_by_user($user_id){
        $this->get_contacts_by_user_statement->bind_param("i", $user_id);
        $this->get_contacts_by_user_statement->execute();
        $result = $this->get_contacts_by_user_statement->get_result();
        $results = ["results"=>[]];
        $contact = null;
        while($contact = $result->fetch_object()){
            array_push($results['results'], ["contact_id" => $contact->contact_id, "firstName"=>$contact->first_name, "lastName" => $contact->last_name, "phone"=>$contact->phone, "email"=>$contact->email, "created_at"=>$contact->created_at]);
        }
        $results["error"] = 0;
        $results["error_message"]="";
        return $results;        

    }
    
    function search_contacts($user_id, $search_string){
        $search_string = '%' . $search_string . '%';
        $this->search_contacts_statement->bind_param("issss", $user_id, $search_string, $search_string, $search_string, $search_string);
        $this->search_contacts_statement->execute();
        $result = $this->search_contacts_statement->get_result();
        $results = [];
        $results['results'] = [];
        $contact = null;

        while($contact = $result->fetch_object()){
            array_push($results['results'], ["contact_id" => $contact->contact_id, "firstName"=>$contact->first_name, "lastName" => $contact->last_name, "phone"=>$contact->phone, "email"=>$contact->email, "created_at"=>$contact->created_at]);
        }
        // array_push($results, ["error"=>"", "error_message"=>""]);
        $results["error"] = 0;
        $results["error_message"] = "";
        return $results;        

    }

    function update_contact_by_id($firstName, $lastName, $phone, $email, $contact_id, $contact_owner)
    {
        $this->update_contact_by_id_statement->bind_param("ssssii", $firstName, $lastName, $phone, $email, $contact_id, $contact_owner);
        $this->update_contact_by_id_statement->execute();
        
        $this->get_contact_by_id_statement->bind_param("i", $contact_id);
        $this->get_contact_by_id_statement->execute();
        $result = $this->get_contact_by_id_statement->get_result();
        if ($result->num_rows != 1) {
            return ["error" => 500, "error_message" => "Internal error", "id" => -1];
        }

        $contact = $result->fetch_object();

        $message = ["error" => 0, "error_message" => ""];
        $message["contact_id"] = $contact->contact_id;
        $message["firstName"] = $contact->first_name;
        $message["lastName"] = $contact->last_name;
        $message["phone"] = $contact->phone;
        $message["email"] = $contact->email;
        $message["created_at"] = $contact->created_at;
        return $message;  

    }
}
