<pre><?php
// Zoho add project push pull test
$refresh_token = "";
$client_id = "";
$client_secret = "";
$refresh_url = "https://accounts.zoho.com/oauth/v2/token?refresh_token=$refresh_token&client_id=$client_id&client_secret=$client_secret&grant_type=refresh_token";

$data = custom_curl($refresh_url, [], "POST");

$access_token = "";

$url = "https://projectsapi.zoho.com/restapi/portals/";

$headers = [
    "Authorization: Bearer $access_token"
];

$data = custom_curl($url, $headers);

$portal_id = $data["portals"][0]["id"];

$url = "https://projectsapi.zoho.com/restapi/portal/$portal_id/projects/";

$body = [
    "name" => "Test Project",
    "description" => "Description",
    "start_date" => "09-19-2023",
    "end_date" => "10-19-2023",
    "strict_project" => "1"
];

$data = custom_curl($url, $headers, "POST", $body);
print_r($data);

function custom_curl($url, $headers=[], $type="GET", $body=[]) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
    if(count($headers) > 0) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
    }
    if(count($body)>0) {
        $postFields = http_build_query($body);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

    $response = curl_exec($ch);
    $data = json_decode($response, true);

    curl_close($ch);

    return $data;
}

?></pre>