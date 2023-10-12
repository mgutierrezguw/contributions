<?php

// https://developer.copper.com/people/create-a-new-person.html

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


$API_Key = '{key_here}';

$userEmail = 'support@getuwired.com';

$apiUrl = 'https://api.copper.com/developer_api/v1/people'; 

$data = [
    "name" => "Jimmy GetUWired",
    "emails" => [
        [
            "email" => "jjohnston@getuwired.com",
            "category" => "work"
        ]
    ],
    "address" => [
        "street" => "123 Main Street",
        "city" => "Savannah",
        "state" => "Georgia",
        "postal_code" => "31410",
        "country" => "United States"
    ],
    "phone_numbers" => [
        [
            "number" => "863-289-9026",
            "category" => "mobile"
        ]
    ]
];



// Create cURL resource
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); // Change to 'POST' or 'PUT' if needed

// Headers
$headers = [
    'X-PW-AccessToken: ' . $API_Key,
    'X-PW-Application: developer_api',
    'X-PW-UserEmail: ' . $userEmail,
    'Content-Type: application/json'
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// If you need to send JSON data in the request body
if (!empty($data)) {
    $jsonPayload = json_encode($data);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
}

// Execute cURL request and store the response
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
}

// Close cURL resource
curl_close($ch);

// Process and use the $response as needed
echo '<pre>';
var_dump($response);

?>