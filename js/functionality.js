var urlBase = 'http://' + window.location.host + '/';
//var urlBaseTest = 'localhost' + '/';
var extension = '.php';

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
	date.setTime(date.getTime()+(minutes*60*1000));	
	document.cookie = "firstName=" + firstName + ",lastName=" + lastName + ",userId=" + userId + ";expires=" + date.toGMTString();
}

function readCookie()
{
	userId = -1;
	var data = document.cookie;
	var splits = data.split(",");
	for(var i = 0; i < splits.length; i++) 
	{
		var thisOne = splits[i].trim();
		var tokens = thisOne.split("=");
		if( tokens[0] == "firstName" )
		{
			firstName = tokens[1];
		}
		else if( tokens[0] == "lastName" )
		{
			lastName = tokens[1];
		}
		else if( tokens[0] == "userId" )
		{
			userId = parseInt( tokens[1].trim() );
		}
	}
	
	if( userId < 0 )
	{
		window.location.href = "index.html";
	}
	else
	{
		document.getElementById("userName").innerHTML = "Logged in as " + firstName + " " + lastName;
	}
}


function login()
{
	// Stores whatever is in the color search box into srch
	var username = document.getElementById("username").value;
	var password = document.getElementById("password").value;
	var firstname = document.getElementById("firstName").value;
	//Clearing out ahead of time
	//document.getElementById("colorSearchResult").innerHTML = "";
	
	//Initializing color list
	//var colorList = "";
	
	//Converts into json readable text
	var jsonPayload = {
		"username": username,
		"password": password,
		//"firstName": firstName,
	}

	//Telling xhr what page to send the request to
	var url = urlBase + '/api/account/login' + extension;
	
	//Requests the data from the URL
	var xhr = new XMLHttpRequest();
	//initialization 
	xhr.open("POST", url, true);
	//initalization
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		//Doesn't run just making the function, called the request callback, runs when the request completes
		xhr.onreadystatechange = function() 
		{	
			//If request was successful 
			if (this.readyState == 4 && this.status == 200) 
			{
				//for testing
				document.getElementById("colorSearchResult").innerHTML = "Color(s) has been retrieved";

				//Converts the request to a json object
				var jsonObject = JSON.parse(xhr.responseText);
				var firstName = jsonObject.firstName;
				var lastName = jsonObject.lastName;

				//Adds the color list to the document colorList
				document.getElementsID("userGreeter")[0].innerHTML = "Hello " + firstName + " " + lastName;
			}
		};
		//Send request to get the colorlist after defining the function because javascript is dumb
		xhr.send(JSON.stringify(jsonPayload));
	}
	catch(err)
	{
		//displays error message
		document.getElementById("colorSearchResult").innerHTML = err.message;
	}
	
}