<?php
// 1. connect to the database - done
// 2. check if tables exist in correct format - TODO
// 3. create (or update) tables to match required schema - TODO

require_once("db/database_connection.php");
$db = new DBConnection();

if (!$db->is_connected()) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    return;
} else {
    echo "<strong>success connecting!</strong>";
    // echo var_dump($mysqli->query("SELECT DATABASE()")->fetch_all());
}

// check tables and update if necessary
// schema from notes/db

$db->send_raw_query("
    CREATE TABLE IF NOT EXISTS users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        user_password CHAR(60) NOT NULL,
        user_username VARCHAR(255) UNIQUE NOT NULL,
        user_first_name VARCHAR(255) NOT NULL,
        user_last_name VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_on TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )  ENGINE=INNODB; 
");

$db->send_raw_query(
    "
    CREATE TABLE IF NOT EXISTS contacts (
        contact_id INT AUTO_INCREMENT PRIMARY KEY,
        user_first_name VARCHAR(255) NOT NULL,
        user_last_name VARCHAR(255),
        user_phone VARCHAR(255),
        user_email VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        contact_owner INT NOT NULL,
        FOREIGN KEY (contact_owner) REFERENCES users(user_id)
    )  ENGINE=INNODB; 
"
);
$mysqli->close();
