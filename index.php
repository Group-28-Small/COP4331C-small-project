<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Brian's Testing Website</title>
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <header>
      Hello and Welcome!
    </header>
    <br><br><br><br>
    <div class="container">
      Login
      <form action="api/account/login.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="user">
        <br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="pass">
        <br><br>
        <input type="submit" value="Submit" style="padding-right: 6px;align:center;">
        <br>
        <a href="http://cop4331small-env.eba-tiprek3s.us-east-1.elasticbeanstalk.com/registration/newaccount.php" style="font-size:16px;">Don't have an account? Click here!</a>
      </form>
    </div>
  </body>
</html>
