<?php
// 1. connect to the database - done
// 2. check if tables exist in correct format - TODO
// 3. create (or update) tables to match required schema - TODO

// TODO - move connection info and object to own file
$username = "admin";

// if running locally, set these environment variables accordingly

// never, ever put a password in code. instead, read it from either a file, or an environment variable
$password = getenv("RDS_PASSWORD") ?: 'my-sql-password';

$database = getenv("RDS_DB_NAME") ?: 'contacts';
$host = getenv("RDS_HOSTNAME") ?: 'localhost';
$port = getenv("RDS_PORT") ?: false;

// connect to database
$mysqli = new mysqli($host, $username, $password, $database, $port);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    return;
} else {
    echo "<strong>success connecting!</strong>";
    // echo var_dump($mysqli->query("SELECT DATABASE()")->fetch_all());
}

// check tables and update if necessary
// schema from notes/db

$mysqli->query("
    CREATE TABLE IF NOT EXISTS users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        user_username VARCHAR(255) UNIQUE NOT NULL,
        user_first_name VARCHAR(255) NOT NULL,
        user_last_name VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_on TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )  ENGINE=INNODB; 
");

$mysqli->query(
    "
    CREATE TABLE IF NOT EXISTS contacts (
        contact_id INT AUTO_INCREMENT PRIMARY KEY,
        user_first_name VARCHAR(255) NOT NULL,
        user_last_name VARCHAR(255),
        user_phone VARCHAR(255),
        user_email VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        owner INT,
        FOREIGN KEY (owner) REFERENCES users(user_id)
    )  ENGINE=INNODB; 
"
);
$mysqli->close();
