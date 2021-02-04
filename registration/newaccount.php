<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Contact Manager</title>
    <link rel="stylesheet" href="../styles.css">
    <script src = '../js/code.js'></script>
  </head>
  <body>
    <header>
      Hello and Welcome!
    </header>
    <br><br><br><br>
    <div class="container">
      Enter New Account:
      <form method="post">
        <p><span class="error">* required field</span></p>
        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="firstName">
        <span class="error">*</span>
        <br><br>
        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="lastName">
        <span class = "error">*</span>
        <br><br>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username">
        <span class = "error">*</span>
        <br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <span class = "error">*</span>
        <br>
        <p id = "userTaken" class = "error"></p>
        <br>
        <input id="submit-button" type="button" onclick = "register()" value="Submit" style="padding-right: 6px;align:center;">
        <br>
      </form>
    </div>
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
  </body>
</html>
