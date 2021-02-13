<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Contact Manager</title>
    <link rel="stylesheet" href="../styles.css">
    <script src = '../js/functionality.js'></script>
  </head>
  <body>
    <header>
      Hello and Welcome!
    </header>
    <br><br><br><br>
    <div class="container">
      Edit Account Details:
      <form method="post">
        
        <label for="edit_first">First Name:</label>
        <input type="text" id="edit_first" name="edit_first">
        
        <br><br>
        <label for="edit_last">Last Name:</label>
        <input type="text" id="edit_last" name="edit_last">
        
        <br><br>
        <label for="edit_phone">Phone Number:</label>
        <input type="text" id="edit_phone" name="edit_phone">
        
        <br><br>
        <label for="edit_email">Email:</label>
        <input type="text" id="edit_email" name="edit_email">
        
        <br>
        
        <br>
        <input id="edit-button" type="button" onclick = "save_edit()" value="Save Changes" style="padding-right: 6px;align:center;">
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
