<?php
require_once("../../db/database_connection.php");
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['username']) || !isset($data['password']) || !isset($data['firstname']) || !isset($data['lastname'])) {
    http_response_code(400);
    $message = ["error" => 400, "error_message" => "Missing field"];
    header('Content-Type: application/json');
    echo json_encode($message);
    return;
}
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

$result = $db->create_user($username, $password, $firstname, $lastname);

if ($result['error'] != 0) {
    http_response_code($result['error']);
}
header('Content-Type: application/json');
echo json_encode($result);
