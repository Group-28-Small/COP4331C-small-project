<?php

/*
example input:
{
    firstName: "kurt"
    lastName "wilson"
    phone: "561-......"
    email: "kurt@kurtw.dev"
    contact_id: 420
    owner_id: 69
}

example output:
{

}

error output:
*/

require_once("../../db/database_connection.php");
$data = json_decode(file_get_contents('php://input'), true);

$firstName = $data['firstName'];
$lastName = $data['lastName'];
$phone = $data['phone'];
$email = $data['email'];
$contact_id = $data['contact_id'];
$owner_id = $data['owner_id'];

$db = new DBConnection();

$result = $db->update_contact_by_id($firstName, $lastName, $phone, $email, $contact_id, $owner_id);

if (!$db->is_connected()) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    return;
}

/*
if ($result['error'] != 0) {
    http_response_code($result['error']);
}
header('Content-Type: application/json');
*/
echo json_encode($result);
