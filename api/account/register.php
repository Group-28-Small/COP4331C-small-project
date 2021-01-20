<?php
require_once("../../db/database_connection.php");
$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$firstname = $data['firstname'];
$lastname = $data['lastname'];
$password = password_hash($data['password'], PASSWORD_BCRYPT);
// echo $username . ' ' . $password;


$db = new DBConnection();

if (!$db->is_connected()) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    return;
}

$db->create_user($username, $password, $firstname, $lastname);
