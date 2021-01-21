<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Brian's Testing Website</title>
    <link rel="stylesheet" href="../styles.css">
  </head>
  <body>
    <header>
      Hello and Welcome!
    </header>
    <br><br><br><br>
    <div class="container">
      Enter New Account:
      <form action = "register.php" method="get">
        <p><span class="error">* required field</span></p>
        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="first">
        <span class="error">*</span>
        <br><br>
        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="last">
        <span class = "error">*</span>
        <br><br>
        <label for="username">Username:</label>
        <input type="text" id="username" name="user">
        <span class = "error">*</span>
        <br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="pass">
        <span class = "error">*</span>
        <br><br>
        <input type="submit" value="Submit" style="padding-right: 6px;align:center;">
        <br>
      </form>
    </div>
  </body>
</html>
