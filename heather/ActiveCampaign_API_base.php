<?php

/* This file contains functions for API calls to ActiveCampaign that can be reused. */



function make_api_call( $api_endpoint, $query_params = '', $data = '', $method = 'GET'){
    // get cURL resource
    $ch = curl_init();

    $query_string = '';

    if( $query_params ){
        $query_params_string = http_build_query($query_params);
        $query_string = '?'.$query_params_string;
    }



    $api_url = '*****';
    $api_token = '*********';



    // set url
    curl_setopt($ch, CURLOPT_URL, 'https://'.$api_url.'.api-us1.com/api/3/'.$api_endpoint.$query_string);

    // Inital Headers in Array
    $headers = [
        'Api-Token: '.$api_token
        ];



    if($method == 'GET'){
         // set method
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    }elseif($method == 'PUT'){
        // set method
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    }elseif($method == 'POST'){
        // set method
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    }elseif ($method == 'DELETE') {
        // set method
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }


    if( $data ){
            // add additonal headers 
            $headers[] = 'Content-Type: application/json; charset=utf-8';

            // set body
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
   
    // return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // set headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // send the request and save response to $response
    $response = curl_exec($ch);

    // close curl resource to free up system resources 
    curl_close($ch);

   return $response;

}




function get_all_contacts(){
    $api_endpoint = 'contacts';
    
    $contacts = make_api_call($api_endpoint);
    return $contacts;
}

function find_contacts($query){
    $api_endpoint = 'contacts';
    
    $contacts = make_api_call($api_endpoint, $query);
    
    return json_decode($contacts);
}


function merge_fieldValues($contact, $fieldValues){

    $contact->fieldValues = array();

    foreach($fieldValues as $fieldValue){
        if( $fieldValue->contact == $contact->id){
            $contact->fieldValues[] = $fieldValue;
        }
    }

    return $contact;
}

function find_activecampaign_contact_by_email($email){
    // $params = array('email' => $email, 'include' =>'fieldValues' );
    $params = array('email' => $email);

    $results = find_contacts($params);

    $contact = false;

    if( isset($results->contacts[0]) && $results->contacts[0]){
        // $contact = merge_fieldValues( $results->contacts[0], $results->fieldValues);
        $contact = $results->contacts[0];
    }
   
	return $contact;
}


function delete_contact($id) {
    $api_endpoint = 'contacts/'.$id;
    $response = make_api_call($api_endpoint, '', '', 'DELETE');

    return $response;
}

function get_contact_by_id($id){

    $api_endpoint = 'contacts/'.$id;
    
    $contact = make_api_call($api_endpoint);


    return $contact;
}

function get_contacts_fieldValues($id) {
    $api_endpoint = 'contacts/'.$id . '/fieldValues';

    $contact = make_api_call($api_endpoint);


    return $contact;
}

function create_ac_contact($contact_data) {
    $api_endpoint = 'contact/sync';

    $results = make_api_call($api_endpoint, $query = '' , $contact_data, 'POST');

    $new_contact = json_decode($results);
	
    if($new_contact && isset($new_contact->contact->id)){
        return $new_contact->contact->id;
    }

     return false;
}

function update_contact($id, $data){
    $api_endpoint = 'contacts/'.$id;
    $query = ''; //No query params for this endpoint

    $contact = make_api_call($api_endpoint, $query, $data, 'PUT'); 

    return $contact;
}


function update_custom_field_on_contact($data) {
    $api_endpoint = 'fieldValues';
    $query = ''; //No query params for this endpoint
    $json_data = json_encode($data);
    
    $contact = make_api_call($api_endpoint, $query, $json_data, 'POST'); 
    echo "<pre>";
    echo "update: <br>";
    print_r(json_decode($contact));
    echo "</pre>";

    return $contact;

}

//This function gets the schema for the Donations Custom Object
function get_ac_custom_object_schemas($schema_id) {
    $api_endpoint = 'customObjects/schemas/'. $schema_id;

    $schema = make_api_call($api_endpoint);

    $json_schema = json_decode($schema);

    return $json_schema;
}


//Donations:
//get_ac_custom_object_schemas('64a914eb-0f47-4525-9615-9e5616dc6362');


// This function checks to see if a custom object already exists with the external ID that is passed along.
function get_ac_donation_custom_object($external_id) {
    $api_endpoint = 'customObjects/records/64a914eb-0f47-4525-9615-9e5616dc6362/external/' . $external_id;

    $donations = make_api_call($api_endpoint);
    $json_donation = json_decode($donations);


    if(isset($json_donation->message)) {
        return false;
    } else {
        return true;
    }

}

//This function creates a new Donation Custom Object if the External ID doesn't exist. 
//If the external ID exists, the Donation Custom Object already exists and is updated. 
function add_ac_donation($opportunity_array, $allocation_array, $contact_id){
    $api_endpoint = 'customObjects/records/64a914eb-0f47-4525-9615-9e5616dc6362';
    $fields = field_handler_for_opportunities($opportunity_array, $allocation_array, $contact_id);

    $opportunity = make_api_call($api_endpoint, $query = '' , $fields, 'POST');

}




