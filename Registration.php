<!DOCTYPE html>
<html lang="en" dir="ltr">
  
  <head>
    <meta charset="utf-8">
    <title>Registraion Page</title>
    <script type="text/javascript" src="js/code.js"></script>
    <link rel="stylesheet" href="styles.css">
  </head>

  <body>
    <header>
      Registraion
    </header>
    <br><br><br><br>
    <div class="container">
      Enter New Account:
      <form action="register.php" method="get">
        <label for="username">Username:</label>
        <input type="text" id="username" name="user">
        <br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="pass">
        <br><br>
        
        <label for="firstName">First Name:</label>
        <input type="firstName" id="firstName" name="first">
        <br><br>
        
        <label for="lastName">Last Name:</label>
        <input type="lastName" id="lastName" name="last">
        <br><br>
        <input type="submit" value="Submit" style="padding-right: 6px;align:center;">
        <br>
      </form>
    </div>
  </body>
</html>
