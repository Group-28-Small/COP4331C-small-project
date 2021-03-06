<?php
/*
example request:
{
    username: "kurt99"
    password: "example_password"
}

example response:
{
  "error": 0,
  "error_message": "",
  "id": 9,
  "username": "kurt99",
  "firstName": "kurt2",
  "lastName": "nonr23",
  "user_last_on": "2021-01-20 13:55:37"
}

on error:
{
  "id": -1
  "error": 403,
  "error_message": "Incorrect username or password"
}

*/
require_once("../../db/database_connection.php");
$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$password = $data['password'];
// echo $username . ' ' . $password;


$db = new DBConnection();

if (!$db->is_connected()) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    return;
}

$result = $db->login_user($username, $password);

if ($result['error'] != 0) {
    http_response_code($result['error']);
}
header('Content-Type: application/json');
echo json_encode($result);
