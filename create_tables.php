<?php
// 1. connect to the database - done
// 2. check if tables exist in correct format - TODO
// 3. create (or update) tables to match required schema - TODO

// TODO - move connection info and object to own file
$username = "admin";

// if running locally, set these environment variables accordingly

// never, ever put a password in code. instead, read it from either a file, or an environment variable
$password = getenv("RDS_PASSWORD");

$database = getenv("RDS_DB_NAME");
$host = getenv("RDS_HOSTNAME");
$port = getenv("RDS_PORT");

// connect to database
$mysqli = new mysqli($host, $username, $password, $database, $port);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    return;
} else {
    echo "<strong>success connecting!</strong>";
}

// check tables and update if necessary
