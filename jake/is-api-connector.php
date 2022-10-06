<?php

if(empty(session_id())) session_start();

require_once 'infusionsoft-php/vendor/autoload.php';

$servername = "localhost";
$username = "";
$password = "";
$dbname = "";

$infusionsoft = new \Infusionsoft\Infusionsoft(array(
	'clientId'     => '',
	'clientSecret' => '',
	'redirectUri'  => '',
));

// If we are returning from Infusionsoft we need to exchange the code for an
// access token.
if(isset($_GET['code']) && !$infusionsoft->getToken()){
	$_SESSION['token'] = serialize($infusionsoft->requestAccessToken($_GET['code']));
	$infusionsoft->setToken(unserialize($_SESSION['token']));
	updateTokenTable($servername, $username, $password, $dbname);
}

// If the serialized token is available in the session storage, we tell the SDK
// to use that token for subsequent requests.
if(isset($_SESSION['token']) && !$infusionsoft->getToken()){
	$tokenObj = unserialize($_SESSION['token']);
	$infusionsoft->setToken($tokenObj);
} else {
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "SELECT token FROM Tokens WHERE ID = 1";
	
	if($result = $conn->query($sql)){
		$tokenObject = $result->fetch_assoc()['token'];
		$_SESSION['token'] = $tokenObject;
		$infusionsoft->setToken(unserialize($_SESSION['token']));
	}

	$conn->close();
}


if($infusionsoft->getToken() && !$infusionsoft->isTokenExpired()){
	// Save the serialized token to the current session for subsequent requests
	$_SESSION['token'] = serialize($infusionsoft->getToken());

} else if($infusionsoft->getToken() && $infusionsoft->isTokenExpired()){
	try{
		$infusionsoft->refreshAccessToken();
		$_SESSION['token'] = serialize($infusionsoft->getToken());
		updateTokenTable($servername, $username, $password, $dbname);
	} catch(\Infusionsoft\TokenExpiredException $e) {
		echo '<a href="' . $infusionsoft->getAuthorizationUrl() . '">Click here to authorize</a>';
	} catch(\Exception $e){
		echo '<a href="' . $infusionsoft->getAuthorizationUrl() . '">Click here to authorize</a>';
	}
} else {
	echo '<a href="' . $infusionsoft->getAuthorizationUrl() . '">Click here to authorize</a>';
}

function updateTokenTable($servername, $username, $password, $dbname){
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	$token = addslashes($_SESSION['token']);
	$sql = "REPLACE INTO Tokens VALUES (1, '$token')";
	$conn->query($sql);
	$conn->close();
}

?>