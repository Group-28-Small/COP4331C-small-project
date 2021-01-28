<?php

/*
example input:
{
    firstName: "kurt"
    lastName "wilson"
    phone: "561-......"
    email: "kurt@kurtw.dev"
    user_id: 4
}

example output:
{

}

error output:
*/

require_once("../../db/database_connection.php");
$data = json_decode(file_get_contents('php://input'), true);

// validate. check if all are not blank
// TODO: check if valid phone or email?
if (
    !(isset($data['firstName']) && isset($data['lastName']) && isset($data['phone']) && isset($data['email'])) ||
    !($data['firstName'] !== '' || $data['lastName'] !== '' || $data['phone'] !== '' || $data['email'] !== '')
) {
    http_response_code(400);
    $message = ["error" => 400, "error_message" => "Missing fields"];
    header('Content-Type: application/json');
    echo json_encode($message);
    return;
}

$firstName = $data['firstName'];
$lastName = $data['lastName'];
$phone = $data['phone'];
$email = $data['email'];
$user_id = $data['user_id'];

$db = new DBConnection();

if (!$db->is_connected()) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    return;
}

$result = $db->create_contact($firstName, $lastName, $phone, $email, $user_id);

if ($result['error'] != 0) {
    http_response_code($result['error']);
}
header('Content-Type: application/json');
echo json_encode($result);
