<?php

/*
example input:
{
  "username": "kurt99",
  "password": "example_password",
  "firstName": "Kurt",
  "lastName": "Wilson"
}

example output:
{
  "error": 0,
  "error_message": "",
  "id": 9,
  "username": "kurt99",
  "firstName": "kurt2",
  "lastName": "nonr23",
  "user_last_on": "2021-01-20 13:55:37"
}

error output:
{
  "id": -1,
  "error": 409,
  "error_message": "User exists already!"
}
*/

require_once("../../db/database_connection.php");
$data = json_decode(file_get_contents('php://input'), true);

// validate input - make sure that none are blank
if (!isset($data['username']) || !isset($data['password']) || !isset($data['firstName']) || !isset($data['lastName'])) {
    http_response_code(400);
    $message = ["error" => 400, "error_message" => "Missing field"];
    header('Content-Type: application/json');
    echo json_encode($message);
    return;
}

$username = $data['username'];
$firstname = $data['firstName'];
$lastname = $data['lastName'];
// hash the password with Blowfish. Salt added automatically
$password = password_hash($data['password'], PASSWORD_BCRYPT);
// echo $username . ' ' . $password;


$db = new DBConnection();

if (!$db->is_connected()) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    return;
}

$result = $db->create_user($username, $password, $firstname, $lastname);

if ($result['error'] != 0) {
    http_response_code($result['error']);
}
header('Content-Type: application/json');
echo json_encode($result);
