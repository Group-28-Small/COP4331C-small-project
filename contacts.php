<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Contacts page</title>
	<script type="text/javascript" src="js/functionality.js"></script>
	<script>
		document.addEventListener("DOMContentLoaded", function(){
			loadAllContacts();
		});
	</script>
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <br><br><br><br>
    <div class="container">
      <h2><u>Contact Manager</u></h2>
	  <span id="nameDisplay"><script>readCookie();</script></span>
      <form>
		<p>New Contact</p>
        <input type="text" name="firstName" id="firstName" placeholder="First Name"><br>
        <input type="text" name="lastName" id="lastName" placeholder="Last Name"><br>
		<input type="text" name="phoneNumber" id="phoneNumber" placeholder="Phone Number (e.g. 123-456-7890)"><br>
		<input type="text" name="emailAddr" id="emailAddr" placeholder="Email (e.g. example@email.com)"><br>
        
        <input type="button" value="Add New Contact!" style="padding-right: 6px;align:center;" onclick="addContact()">
		<br/>
		<span id="addContactResult"></span>
        
      </form>
	  <form>
	  <p>Search Contacts</p>
		<input type="text" name="searchBar" id="searchBar" placeholder="Search...">
		<input type="submit" action="searchContacts()" style="padding-right: 6px;align:center;"><br><br>
		<span id="contactSearchList"></span><br>
		
	  </form>
		<button type="button" class="collapsible" style="align:center;width:80%">Contact 1</button>
		<div class="content" style="padding-left:2px;paddng-right:2px;padding-top:2px;padding-bottom:2px;width:80%">
			<p style="font-size:16px">Collapsible Contact info.</p>
			<button type="button">Edit</button>
			<button type="button">Delete</button>
		</div>
		<br>
	
	
    </div>
	
  <script>
	var coll = document.getElementsByClassName("collapsible");
	var i;

	for (i = 0; i < coll.length; i++) {
	  coll[i].addEventListener("click", function() {
		this.classList.toggle("active");
		var content = this.nextElementSibling;
		if (content.style.display === "block") {
		  content.style.display = "none";
		} else {
		  content.style.display = "block";
		}
	  });
	}
  </script>
  </body>
</html>