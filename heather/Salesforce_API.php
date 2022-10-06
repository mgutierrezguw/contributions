<?php 
// Salesforce Rest API Base


/* -- Main Include -- */

function get_sf_authorization_and_tokens() {


    $base_url = 'https://xxxxxxxxxxx.salesforce.com/services/oauth2/';
    $client_id = '*********';
    $client_secret = '***********';
    $callback = 'https://gstaging.getuwired.us/engconcepts/heather/TLYCS/next-steps-tlycs/salesforce-api.php';


    // 1. check for a token.json and a refresh_token.json that would hold the access token and the refresh_token. If it doesn't exist, make one.
    $token_path = __DIR__ . '/token.json';
    $refresh_token_path = __DIR__ . '/refresh_token.json';

    if (!file_exists($token_path)) {
        fopen($token_path, 0700, true);
    }

    if (!file_exists($refresh_token_path)) {
        fopen($refresh_token_path, 0700, true);
    }

    //if access token file exists, check to see if the access code is valid.
    //If the access token is not valid, use the refresh token to get a new access token.
    if (file_exists($token_path)) {

        $current_file_contents = json_decode(file_get_contents($token_path), true);

        if(empty($current_file_contents)) {


            echo '<p>Access token is unavailable. Click below to authorize.</p>';
            echo '<p><a href="' . $base_url . 'authorize?client_id=' . $client_id . '&redirect_uri=https://gstaging.getuwired.us/engconcepts/heather/TLYCS/next-steps-tlycs/salesforce-api.php&response_type=code"> click to authorize</a></p>';
            
            if (isset($_GET['code']) || $_GET['code'] == '') {
                $authorization_code = $_GET['code'];

                $access_token_body = json_decode(get_sf_initial_access_token($authorization_code));

                $args = array('new_access_token' => $access_token_body->access_token, 'new_refresh_token' => $access_token_body->refresh_token);
                return $args;
            }
            
        } else {


            $access_token_check = json_decode(file_get_contents($token_path), true);
            $refresh_token_check = file_get_contents($refresh_token_path);

            $args = array('new_access_token' => $access_token_check['access_token'], 'new_refresh_token' => $refresh_token_check);
            
            return $args;
        }
    }

}


// This function is to get an initial access token. 
// If the refresh token no longer works, this function will have to be used.

function get_sf_initial_access_token($auth_code) {
    $client_id = '**********';
    $client_secret = '**********';
    
    $token_url = 'https://*********.salesforce.com/services/oauth2/token';
    $params = 'grant_type=authorization_code&code=' . $auth_code . '&client_id='. $client_id .'&client_secret=' . $client_secret . '&redirect_uri=https://gstaging.getuwired.us/engconcepts/heather/TLYCS/next-steps-tlycs/salesforce-api.php';

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $token_url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $params,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded',
      ),
    ));

    $response = curl_exec($curl);

    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ( $status != 200 ) {
        die("Error: call to URL failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
    } else {
        // echo "inside function <br>";    
        $arr = json_decode($response);
 
        file_put_contents( __DIR__ . '/token.json', $response);
        file_put_contents( __DIR__ . '/refresh_token.json', $arr->refresh_token);
    }

    curl_close($curl);
    return $response;

}



// Refreshes the access token if null or invalid
function get_sf_refresh_token($refresh_token, $current_access_token) {
    
    $client_id = '***********';
    $client_secret = '**********';
    
    $token_url = 'https://*******.salesforce.com/services/oauth2/token';
    $params = 'grant_type=refresh_token&client_id='. $client_id .'&client_secret=' . $client_secret . '&refresh_token=' . $refresh_token;


    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://*********.salesforce.com/services/oauth2/token',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => 'client_id=' . $client_id . '&client_secret=' . $client_secret . '&grant_type=refresh_token&refresh_token='. $refresh_token,
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . $current_access_token,
        'Content-Type: application/x-www-form-urlencoded',
      ),
    ));

    $response = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ( $status != 200 ) {        
        die("Error: call to URL failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
    }

    file_put_contents( __DIR__ . '/token.json', $response);

    curl_close($curl);

    return $response;


}


////////////



function make_salesforce_api_call($api_endpoint = '', $data = '', $method = 'GET') {

    //need a function to be called to GET the token here
    $token_array = get_sf_authorization_and_tokens();
    $token = $token_array['new_access_token'];
    $refresh = $token_array['new_refresh_token'];

    $curl = curl_init();

    $api_url = 'https://*********.salesforce.com/services/data/v55.0/';

    // set url
    curl_setopt($curl, CURLOPT_URL, $api_url . $api_endpoint . $query_string);

    // Inital Headers in Array
    $headers = [
        'Authorization: Bearer ' . $token,
        ];

    // set method
    if($method == 'GET'){
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    }

    // return the transfer as a string
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    // set headers
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    // send the request and save response to $response
    $response = curl_exec($curl);

    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ( $status != 200 ) {
        $err = json_decode($response);
        echo "<pre>";
        echo $err[0]->errorCode;
        echo "</pre>";

        if($err[0]->errorCode == 'INVALID_SESSION_ID') {
            
            get_sf_refresh_token($refresh, $token);

            //HIT THE MAIN APP AGAIN WITH THE NEW TOKEN 
            mail('hconley@getuwired.com', 'Salesforce API Script Refresh Token', 'The Salesforce API script grabbed a new refresh token');
        } else {
            mail('hconley@getuwired.com', 'Salesforce API Script', 'The Salesforce API script for TLYCS needs new authorization');
            die("Error: call to URL failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }
        
    }


    // close curl resource to free up system resources 
    curl_close($curl);

    $response_array = json_decode($response);
    
    return $response_array;

}


/* -- Api Call Definitions -- */



function sf_get_sobjects() {

    $api_endpoint = 'sobjects/';
    $objects = make_salesforce_api_call($api_endpoint);
    return $objects;

}

// echo "here's the objects: \r\n";

// sf_get_sobjects();


function sf_get_single_account($account_id) {

    $api_endpoint = 'sobjects/Account/' . $account_id;

    $single_account = make_salesforce_api_call($api_endpoint);

    return $single_account;
}

// echo "GET SINGLE ACCOUNT \r\n";
// sf_get_single_account('0018M000004idnIQAQ');

function sf_get_contact($contact_id) {

    $api_endpoint = 'sobjects/Contact/' . $contact_id;

    $single_contact = make_salesforce_api_call($api_endpoint);

    return $single_contact;
}


// echo "GET SINGLE CONTACT";
// sf_get_contact('0038M000003S7WaQAK');


function sf_get_single_opportunity($opportunity_id) {
    $api_endpoint = 'sobjects/Opportunity/' . $opportunity_id;

    $single_opportunity = make_salesforce_api_call($api_endpoint);

    return $single_opportunity;

}

// echo "GET SINGLE Opportunity \r\n";
// sf_get_single_opportunity('0068M0000038A1CQAU');


function sf_get_single_allocation($allocation_id) {

    $api_endpoint = 'sobjects/npsp__Allocation__c/' . $allocation_id;
    
    $single_allocation = make_salesforce_api_call($api_endpoint);

    return $single_allocation;

}

// echo "GET SINGLE ALLOCATION \r\n"; 

// sf_get_single_allocation('a0b8M000000ID6QQAW');


function sf_get_single_gau($gau_id) {

    $api_endpoint = 'sobjects/npsp__General_Accounting_Unit__c/' . $gau_id;

    $single_gau = make_salesforce_api_call($api_endpoint);

    return $single_gau;
}

// echo "GET SINGLE GAU \r\n"; 

// sf_get_single_gau('a0ef400000DL5kLAAT');


function sf_get_record_type_name($record_type_id) {
    $api_endpoint = 'sobjects/RecordType/' . $record_type_id;

    $single_record = make_salesforce_api_call($api_endpoint);

    $record_name = $single_record->Name;

    return $record_name;
}

//sf_get_record_type_name('012f4000000obS7AAI');


function get_sf_updated_contacts($start, $end) {
    $api_endpoint = 'sobjects/Contact/updated/?start=' . $start . '&end=' . $end;

    $updated_contacts = make_salesforce_api_call($api_endpoint);

    return $updated_contacts->ids;
}

function get_sf_contact_owner_name($owner_id) {
    $api_endpoint = 'sobjects/User/' . $owner_id;

    $owner = make_salesforce_api_call($api_endpoint);

    return $owner->Name;
}
// get_sf_contact_owner_name('005f4000000deZmAAI');

function get_sf_opportunities_on_contact($ac_contact_id) {

    $api_endpoint = 'query/?q=SELECT+Id,ContactId,Name,Campaign__c,Amount,CurrencyIsoCode,Gift_Date_If_This_Year__c,RecordTypeId,Type,StageName,CloseDate,Gross_Donation__c,npsp__Acknowledgment_Status__c,Payment_Medium__c,Thank_You_Sent__c,LastModifiedDate,CreatedDate+FROM+Opportunity+WHERE+ContactId=\''.$ac_contact_id.'\'';

    $filtered_opportunities = make_salesforce_api_call($api_endpoint);
    return $filtered_opportunities;

}

function get_sf_allocations_on_contact($opp_id) {
    $api_endpoint = 'query/?q=SELECT+Id,npsp__Opportunity__c,Organization__c,Designation__c,npsp__Amount__c,LastModifiedDate,CurrencyIsoCode+FROM+npsp__Allocation__c+WHERE+npsp__Opportunity__c=\''.$opp_id.'\'';

    $allocations_on_opp = make_salesforce_api_call($api_endpoint);

    return $allocations_on_opp;


}




?>