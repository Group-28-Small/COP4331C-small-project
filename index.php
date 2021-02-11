<!DOCTYPE html>
<html lang="en" dir="ltr">

  <head>
    <meta charset="utf-8">
    <title>Login Page</title>
    <script type="text/javascript" src="js/functionality.js"></script>
    <link rel="stylesheet" href="styles.css">
  </head>


  <body>
    <header>
      Hello and Welcome!
    </header>
    <br><br><br><br>
    <div class="container">
      Login
      <form method="post">
        <label for="loginName">Username:</label>
        <input type="text" id="loginName">
        <br><br>
        <label for="password">Password:</label>
        <input type="password" id="loginPassword">
        <br>
        <p class="error" id="loginResult"></p>
        <br>
        <input id="submit-button" type="button" onclick = "login()" value="Submit" style="padding-right: 6px;align:center;">
        <br>
        <a href="/registration/newaccount.php" style="font-size:16px;">Don't have an account? Click here!</a>
      </form>
    <script>
      var input = document.getElementsByTagName("input");
      for(var i = 0; i < input.length; i++)
      {
        input[i].addEventListener("keyup", function(event) {
          if(event.keyCode === 13)
          {
            event.preventDefault();
            document.getElementById("submit-button").click();
          }
        });
      }
    </script>
    </div>
  </body>
</html>
