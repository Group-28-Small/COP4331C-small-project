<?php
// use get_all_contacts_for_user

require_once("../../db/database_connection.php");
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['user_id'])) {
    http_response_code(400);
    $message = ["error" => 400, "error_message" => "Missing fields"];
    header('Content-Type: application/json');
    echo json_encode($message);
    return;
}

$user_id = $data['user_id'];

$db = new DBConnection();

if (!$db->is_connected()) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    return;
}

$result = $db->get_contacts_by_user($user_id);

if ($result['error'] != 0) {
    http_response_code($result['error']);
}
header('Content-Type: application/json');
echo json_encode($result);
