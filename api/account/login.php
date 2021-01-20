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

$result = $db->login_user($username, $password);

if ($result['error'] != 0) {
    http_response_code($result['error']);
}
header('Content-Type: application/json');
echo json_encode($result);
