var urlBase = 'http://' + window.location.host + '/';
//var urlBaseTest = 'localhost' + '/';
var extension = '.php';

function login()
{
    userId = 0;
    firstName = "";
    lastName = "";

	// Stores whatever is in the color search box into srch
	var username = document.getElementById("loginName").value;
	var password = document.getElementById("loginPassword").value;
	//var firstname = document.getElementById("firstName").value;
	//Clearing out ahead of time
	//document.getElementById("colorSearchResult").innerHTML = "";
	
	//Initializing color list
	//var colorList = "";
	
	//Converts into json readable text
	var jsonPayload = {
		"username": username,
		"password": password,
	}

	//Telling xhr what page to send the request to
	var url = urlBase + '/api/account/login' + extension;
	
	var xhr = new XMLHttpRequest();
	//synchronous opening is deprecated, throws up warning
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function() 
        {
            if (this.readyState == 4 && this.status == 200) 
            {
                var jsonObject = JSON.parse( xhr.responseText );
        
                userId = jsonObject.id;
                firstName = jsonObject.firstName;
                lastName = jsonObject.lastName;

                saveCookie();
    
                window.location.href = urlBase + '/contacts.php';
			} 
			else if (this.readyState == 4) {
				// error:
				var jsonObject = JSON.parse(xhr.responseText);
				var error = jsonObject.error;
				if (error != 0) {
					// login failed, notify user
					document.getElementById("loginResult").innerHTML = "User/Password combination incorrect";
				}
			}
        };
        
        xhr.send(JSON.stringify(jsonPayload));
    }
    catch(err)
    {
        document.getElementById("colorAddResult").innerHTML = err.message;
    }
}
function addContact() {
	document.getElementById("addContactResult").innerText = "Saving contact...";
	readCookie();
	var contactFirstName = document.getElementById("firstName").value;
	var contactLastName = document.getElementById("lastName").value;
	var contactPhoneNumber = document.getElementById("phoneNumber").value;
	var contactEmail = document.getElementById("emailAddr").value;

	var jsonPayload = {
		firstName: contactFirstName,
		lastName: contactLastName,
		phone: contactPhoneNumber,
		email: contactEmail,
		user_id: userId
	}

	//Telling xhr what page to send the request to
	var url = urlBase + '/api/contacts/create_contact' + extension;

	var xhr = new XMLHttpRequest();
	//synchronous opening is deprecated, throws up warning
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try {
		xhr.onreadystatechange = function () {
			if (this.readyState == 4) {
				var jsonObject = JSON.parse(xhr.responseText);
				if (jsonObject.error == 0) {
					document.getElementById("firstName").value = "";
					document.getElementById("lastName").value = "";
					document.getElementById("phoneNumber").value = "";
					document.getElementById("emailAddr").value = "";
					document.getElementById("addContactResult").innerText = "Contact added successfully";
					document.getElementById("contact-list").innerHTML += add_contact_box(jsonObject);
				} else {
					document.getElementById("addContactResult").innerText = "Error: " + jsonObject.error_message;
				}
			}
		};

		xhr.send(JSON.stringify(jsonPayload));
	}
	catch (err) {
		document.getElementById("addContactResult").innerText = "Error communicating with server";
	}

}

function register() {
	// get the data from the form
	var username = document.getElementById("username").value;
	var password = document.getElementById("password").value;
	var firstName = document.getElementById("firstName").value;
    var lastName = document.getElementById("lastName").value;
    
	// disable the button
	document.getElementById("submit-button").disabled = true;

	var jsonPayload = {
		"username": username,
		"password": password,
		"firstName": firstName,
		"lastName": lastName,
	}

	var url = urlBase + '/api/account/register' + extension;

	//Requests the data from the URL
    var xhr = new XMLHttpRequest();
    
	//initialization 
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try {

		//Doesn't run, just making the function, called the request callback, runs when the request completes
		xhr.onreadystatechange = function () {	// when the response comes from the server
			//If request was successful 
			// state 4 is DONE, 200 is successful
			if (this.readyState == 4 && this.status == 200) {
				// when the request succeeds
				// redirect to success/login
				// redirect
				window.location.href = urlBase + '/index.php';
				// return just in case
				return;
			} else if (this.readyState == 4) {
				// error:
				var jsonObject = JSON.parse(xhr.responseText);
				var error = jsonObject.error;
				if (error != 0) {
					// registration failed, possibly notify user?
					document.getElementById("userTaken").innerHTML = "Sorry! Username, \"" + username + "\", is already in use. Please try again."
					document.getElementById("submit-button").disabled = false;
				}
			}
		};
	}
	catch (err) {
		//displays error message
		document.getElementById("colorSearchResult").innerHTML = err.message;
    }
    
	// we do this last, so that the xhr client knows what to do with the response data
	//Send request to get the colorlist after defining the function because javascript is dumb
	xhr.send(JSON.stringify(jsonPayload));
}

function saveCookie()
{
	var minutes = 20;
	var date = new Date();
	date.setTime(date.getTime() + (minutes * 60 * 1000));	
	// before, we did something like "firstName=aaa,lastName=bbb", but this can cause issues with how cookies are read, since when reading we
	// get all of them back as "cookie_name=value", but a decoder could confuse our custom values with actual cookie names.
	// what we're doing now is creating one key called "account_data", and storing the account data in base64 encoded JSON, which
	// _never_ has an "=" in it, so we don't risk confusing a cookie parser
	account_data = { firstName: firstName, lastName: lastName, userId: userId }
	document.cookie = "account_data=" + btoa(JSON.stringify(account_data))+ ";expires=" + date.toGMTString();
}

function readCookie()
{
	userId = -1;
	var data = document.cookie;
	// document.cookie has data like "cookie_name=value; cookie_name=value"
	// we're looking for "account_data=some_base_64_string"
	var splits = data.split(";");
	for(var i = 0; i < splits.length; i++) 
	{
		var thisOne = splits[i].trim();
		var tokens = thisOne.split("=");
		// found it
		if( tokens[0] == "account_data" )
		{
			// now getting user data is much easier - we can treat user_data as a regular object
			user_data = JSON.parse(atob(tokens[1]));
			userId = user_data.userId;
			firstName = user_data.firstName;
			lastName = user_data.lastName;
		}
	}
	
	if( userId < 0 )
	{
		window.location.href = "index.php";
	}
	else
	{
		document.getElementById("nameDisplay").innerHTML = "Logged in as " + firstName + " " + lastName;
	}
}
function loadAllContacts() {
	readCookie();

	var jsonPayload = {
		user_id: userId,
	}

	var url = urlBase + '/api/contacts/get_all_contacts' + extension;

	//Requests the data from the URL
	var xhr = new XMLHttpRequest();

	//initialization 
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try {

		//Doesn't run, just making the function, called the request callback, runs when the request completes
		xhr.onreadystatechange = function () {	// when the response comes from the server
			//If request was successful 
			// state 4 is DONE, 200 is successful
			if (this.readyState == 4) {
				var jsonObject = JSON.parse(xhr.responseText);
				console.log(jsonObject);
				if (jsonObject.error == 0) {
					for (var i = 0; i < jsonObject.results.length; i++) {
						var contact = jsonObject.results[i];
						console.log(contact)
						document.getElementById("contact-list").innerHTML += add_contact_box(contact);

					}
				} else {
					// error
					console.log("error");
				}
			}
		};
	}
	catch (err) {
		//displays error message
		// document.getElementById("colorSearchResult").innerHTML = err.message;
	}

	// we do this last, so that the xhr client knows what to do with the response data
	//Send request to get the colorlist after defining the function because javascript is dumb
	xhr.send(JSON.stringify(jsonPayload));

}

function add_contact_box(contact) {
	return `
		<div class="contact-card" id="contact_${contact.contact_id}">
			<button type="button" onclick="toggle_block(\'contact_${contact.contact_id}\')" class="collapsible" style="align:center;width:80%">${contact.firstName + " " + contact.lastName}</button>
			<div class="contact-content" style="display:block;padding-left:2px;padding-right:2px;padding-top:2px;padding-bottom:2px;width:80%">
				<div style="font-size:24px">${contact.firstName + " " + contact.lastName}</div>
				<div style="font-size:16px"><div style="display:inline-block" onclick="copy_on_click(\'${contact.phone}\')">${contact.phone}</div> <div style="display:inline-block" onclick="copy_on_click(\'${contact.email}\')">${contact.email}</div></div>
				<button type="button">Edit</button>
				<button onclick="delete_contact(\'${contact.contact_id}\')" type="button">Delete</button>
			</div>
		</div>
       `
}

function delete_contact(id) {
	readCookie();

	var jsonPayload = {
		contact_id: id,
		owner_id: userId
	}

	var url = urlBase + '/api/contacts/delete' + extension;

	//Requests the data from the URL
	var xhr = new XMLHttpRequest();

	//initialization 
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

	try {
		// when the response comes from the server
		xhr.onreadystatechange = function () {	
			//If request was successful 
			// state 4 is DONE, 200 is successful
			if (this.readyState == 4) {
				var response = xhr.response;
				console.log(response);
				if (response) {
					// dunno what to do here
					var card = document.getElementById("contact_" + id);
					card.remove();  
					console.log("success");
				} else {
					// error
					console.log("error");
				}
			}
		};
	}
	catch (err) {
		//displays error message
		// document.getElementById("colorSearchResult").innerHTML = err.message;
	}

	xhr.send(JSON.stringify(jsonPayload));
}

function toggle_block(id) {
	console.log("toggling " + id);
	var blk = document.getElementById(id);
	blk.children[0].classList.toggle("active");
	var content = blk.children[1];
	if (content.style.display === "block") {
		content.style.display = "none";
	} else {
		content.style.display = "block";
	}
}

function copy_on_click(text) {
	toastr.info("Copied " + text);
	var dummy = document.createElement("textarea");
	// https://stackoverflow.com/questions/33855641/copy-output-of-a-javascript-variable-to-the-clipboard
	document.body.appendChild(dummy);
	dummy.value = text;
	dummy.select();
	document.execCommand("copy");
	document.body.removeChild(dummy);

}