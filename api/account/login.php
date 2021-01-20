<?php
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

$db->login_user($username, $password);
