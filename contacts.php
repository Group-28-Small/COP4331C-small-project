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
	 <script
			  src="https://code.jquery.com/jquery-3.5.1.min.js"
			  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
			  crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="styles.css"/>
  </head>
  <body>
    <br><br><br><br>
    <div class="container">
      <h2><u>Contact Manager</u></h2>
	  <span id="nameDisplay"><script>readCookie();</script></span>
	  <div><button onclick="logout()" style="padding-right: 6px;align:center;">Log Out</button></div>
      <form>
		<p>New Contact</p>
        <input type="text" name="firstName" id="firstName" placeholder="First Name" class="contact-entry-input"><br>
        <input type="text" name="lastName" id="lastName" placeholder="Last Name" class="contact-entry-input"><br>
		<input type="text" name="phoneNumber" id="phoneNumber" placeholder="Phone Number (e.g. 123-456-7890)" class="contact-entry-input"><br>
		<input type="text" name="emailAddr" id="emailAddr" placeholder="Email (e.g. example@email.com)" class="contact-entry-input"><br>
        
        <input type="button" value="Add New Contact!" style="padding-right: 6px;align:center;" onclick="addContact()">
		<br/>
		<span id="addContactResult"></span>
        
      </form>
	  <form>
	  <p>Search Contacts</p>
		<input type="text" name="searchBar" id="searchBar" placeholder="Search..." class="search-input">
		<input type="button" onclick="searchContacts()" value="Search">
		<input type="button" onclick="resetSearchList()" value="Show All"><br><br>
		<span id="contactSearchList"></span><br>
		
	  </form>
		<div id="contact-list">
	</div>
	
	
    </div>
	
  </body>
</html>