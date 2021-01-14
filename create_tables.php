<?php
$username="admin";
$password=getenv("RDS_PASSWORD");
$database=getenv("RDS_DB_NAME");
$host=getenv("RDS_HOSTNAME");
$port=getenv("RDS_PORT");
$mysqli = new mysqli($host, $username, $password, $database, $port);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}else{
    echo "success connecting";
}

?>