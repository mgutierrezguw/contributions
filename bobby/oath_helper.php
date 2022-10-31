<?php

/*
* Main paramerters needed by most OAuth Flows
*/

//Initial endpoint provided by API for OAUTH authorization
$auth_URL = 'https://example.com/auth';

//Token endpoint provided by API for exchanging a code for a valid token
$token_URL = 'https://example.com/auth/token';

//Client ID and Client Seceret retrieved from API's developer account

$client_id = ''; //Defined infile for quick use
$client_secret = ''; //Move outside of webroot for production use


//URL to this file to be used a redirect_uri
$this_url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; //Sometimes needs to be added to an approved listed in the API's developer account

//URI  Paramerters Required by API 
$params = [
'redirect_uri' => $this_url,
'scope' => 'ALL', //API's will list scope parameters you must past
'state' => substr(str_shuffle(MD5(microtime())), 0, 10), //A quick random string to use as a state
'client_id' => $client_id, //In the inital request only Client ID is needed
'Extra 1' => 'as needed', //Extra params can be added if needed
'Extra 2' => 'as needed' //If not, just remove from aray
];
	

if($_GET['code']){
	
    //Params explicitly set for illustration, you could just add to the existing array
	$params = [
        'grant_type' => 'authorization_code', //There are other types, this is the most common, check API's documentation
        'redirect_uri' => $this_url, //This must match the original request
        'code' => $_GET['code'], //Gets the Code sent back from the Auth request
        'client_id' => $client_id,
        'client_secret' => $client_secret, //Client secrete is needed for the code exchange
        'Extra 1' => 'as needed', //Extra params can be added if needed
        'Extra 2' => 'as needed' //If not, just remove from aray
	];
	
header('Location: '.$token_URL.'?'.http_build_query($params));
	
}else{
    echo '<a href="'.$auth_URL.'?'.http_build_query($params).'"> Request Token </a>';
}