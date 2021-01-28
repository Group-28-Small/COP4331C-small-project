var urlBase = 'http://' + window.location.host + '/';
var extension = '.php';

var userId = 0;
var firstName = "";
var lastName = "";

function doLogin()
{
	userId = 0;
	firstName = "";
	lastName = "";
	
	var login = document.getElementById("loginName").value;
	var password = document.getElementById("loginPassword").value;
//	var hash = md5( password );
	
	document.getElementById("loginResult").innerHTML = "";

//	var jsonPayload = '{"login" : "' + login + '", "password" : "' + hash + '"}';
	var jsonPayload = '{"login" : "' + login + '", "password" : "' + password + '"}';

	//For us it would be api/account/login.php
	var url = urlBase + '/Login.' + extension;

	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, false);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.send(jsonPayload);
		
		//jsonObject contains various user properties
		var jsonObject = JSON.parse( xhr.responseText );
		
		//storing user id
		userId = jsonObject.id;
		
		//Checks for negative user id in case of an error
		if( userId < 1 )
		{
			document.getElementById("loginResult").innerHTML = "User/Password combination incorrect";
			return;
		}
		
		//Takes the firstName and lastName from jsonObject
		//and stores it in variables firstName and lastName
		firstName = jsonObject.firstName;
		lastName = jsonObject.lastName;

		//Used so users aren't asked to enter in credentials every time
		saveCookie();
		
		//Redirect to color.html or whatever page you want to go to
		//after logging in
		window.location.href = "color.html";
	}
	catch(err)
	{
		//Returns the error message to user and keeps them on login page
		document.getElementById("loginResult").innerHTML = err.message;
	}

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


function doLogout()
{
	userId = 0;
	firstName = "";
	lastName = "";
	document.cookie = "firstName= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
	window.location.href = "index.html";
}

function addColor()
{
	var newColor = document.getElementById("colorText").value;
	document.getElementById("colorAddResult").innerHTML = "";
	
	var jsonPayload = '{"color" : "' + newColor + '", "userId" : ' + userId + '}';
	var url = urlBase + '/AddColor.' + extension;
	
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				document.getElementById("colorAddResult").innerHTML = "Color has been added";
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("colorAddResult").innerHTML = err.message;
	}
	
}

function searchColor()
{
	// Stores whatever is in the color search box into srch
	var srch = document.getElementById("searchText").value;
	//Clearing out ahead of time
	document.getElementById("colorSearchResult").innerHTML = "";
	
	//Initializing color list
	var colorList = "";
	
	//Converts into json readable text
	var jsonPayload = '{"search" : "' + srch + '","userId" : ' + userId + '}';

	//Telling xhr what page to send the request to
	var url = urlBase + '/SearchColors.' + extension;
	
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
				var jsonObject = JSON.parse( xhr.responseText );
				
				//jsonObject has an array with the search results, it's a certian length
				for( var i=0; i<jsonObject.results.length; i++ )
				{
					//Add the color to the list 
					colorList += jsonObject.results[i];
					//if it is not the last object in the array, add a new line
					if( i < jsonObject.results.length - 1 )
					{
						//makes a break line
						colorList += "<br />\r\n";
					}
				}
				//Adds the color list to the document colorList
				document.getElementsByTagName("p")[0].innerHTML = colorList;
			}
		};
		//Send request to get the colorlist after defining the function because javascript is dumb
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		//displays error message
		document.getElementById("colorSearchResult").innerHTML = err.message;
	}
	
}

function register() {
	// get the data from the form
	var username = document.getElementById("username").value;
	var password = document.getElementById("password").value;
	var firstname = document.getElementById("firstName").value;
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
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	xhr.open("POST", url, true);
	try {
		//Doesn't run, just making the function, called the request callback, runs when the request completes
		xhr.onreadystatechange = function () {	// when the response comes from the server
			//If request was successful 
			// state 4 is DONE, 200 is successful
			if (this.readyState == 4 && this.status == 200) {
				// when the request succeeds
				// redirect to success/login
				// redirect
				window.location.href(urlBase + '/index.html');
				// return just in case
				return;
			} else if (this.readyState == 4) {
				// error:
				var jsonObject = JSON.parse(xhr.responseText);
				var error = jsonObject.error;
				if (error != 0) {
					// registration failed, possibly notify user?
					document.getElementsID("userGreeter")[0].innerHTML = "Hello " + firstName + " " + lastName;
				} else {
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

function login()
{
	// Stores whatever is in the color search box into srch
	var username = document.getElementById("username").value;
	var password = document.getElementById("password").value;
	var firstname = document.getElementById("firstName").value;
	//Clearing out ahead of time
	document.getElementById("colorSearchResult").innerHTML = "";
	
	//Initializing color list
	var colorList = "";
	
	//Converts into json readable text
	var jsonPayload = {
		"username": username,
		"password": password,
		"firstName": firstName,
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
