<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <style>
      body {background-color: light-gray;}
    </style>
  </head>
  <body>
    <?php

      //connect
      require_once("../db/database_connection.php");
      $mysqli = new DBConnection();

      //create the mySQL query based on input from newaccount.php
      $sql = "INSERT INTO users (user_username, user_password, user_first_name, user_last_name)
              VALUES (
                '" . $_GET['user'] . "',
                '" . $_GET['pass'] . "',
                '" . $_GET['first'] . "',
                '" . $_GET['last'] . "'
                );
              ";

      if ($mysqli->send_raw_query($sql)) {
        //if query worked, tell user, return to login page after 3 seconds
        echo "Account Created! ";
        echo "Returning to login page...";
        header("refresh:3;url=index.php");
      } else {
        //else, print the error, return to registration page after 6 seconds
        echo "Error creating account: " . $mysqli->error;
        echo " Returning to registration page...";
        header("refresh:6;url=newaccount.php");
      }

      $mysqli->close();
    ?>
  </body>
</html>
