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


    $api_url = 'txxxxxxx';
    $api_token = 'xxxxxxxxxxx';
https://thelifeyoucansave.api-us1.com/api/3/

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
            // curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
   
    // return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // set headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // send the request and save response to $response
    $response = curl_exec($ch);
    $information = curl_getinfo($ch, CURLINFO_HTTP_CODE);


    $fd = @fopen("webhook.txt", "a");
fwrite($fd, "INFORMATION: : \n\n");

ob_start();
var_dump($information);
$stuff = ob_get_clean();

fwrite($fd, $stuff);

fclose($fd);


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
        $method = 'POST';
   
    $query = ''; //No query params for this endpoint
    $json_data = json_encode($data);
    
    $contact = make_api_call($api_endpoint, $query, $json_data, $method); 
    echo "<pre>";
    echo "update: <br>";
    print_r(json_decode($contact));
    echo "</pre>";

    return $contact;

}


//This function gets the schema for the SalesForce Donations Custom Object
function get_ac_custom_object_schemas($schema_id) {
    $api_endpoint = 'customObjects/schemas/'. $schema_id;

    $schema = make_api_call($api_endpoint);

    $json_schema = json_decode($schema);

echo "<pre>";
    var_dump($json_schema);

    echo "</pre>";

    return $json_schema;
}


//Donations:
//get_ac_custom_object_schemas('64a914eb-0f47-4525-9615-9e5616dc6362');


// This function checks to see if a custom object already exists with the external ID that is passed along.
function get_ac_donation_custom_object($external_id) {
    //Donation Custom Object
    // $api_endpoint = 'customObjects/records/64a914eb-0f47-4525-9615-9e5616dc6362/external/' . $external_id;
    //Salesforce Donation Custom Object
    $api_endpoint = 'customObjects/records/76a93b0b-8590-4ea5-8bd7-6e83bb06ffc3/external/' . $external_id;
    

    $donations = make_api_call($api_endpoint);
    $json_donation = json_decode($donations);


    if(isset($json_donation->message)) {
        return false;
    } else {
        return true;
    }

}


function check_off_ac_donation_custom_object_thankyou_field($ac_contact_id) {

    $current = new DateTime();
    $current_unix = $current->getTimestamp();
    $current->sub(new DateInterval('P90D'));
    $thirty_days_ago = $current->getTimestamp();

// $api_endpoint = 'customObjects/records/64a914eb-0f47-4525-9615-9e5616dc6362?filters[relationships.primary-contact][eq]=' . $ac_contact_id . 'AND[updatedTimestamp][gte]=' . $thirty_days_ago;

    //Salesforce Donation Custom Object
    $api_endpoint = 'customObjects/records/76a93b0b-8590-4ea5-8bd7-6e83bb06ffc3?filters[relationships.primary-contact][eq]=' . $ac_contact_id;



    $fd = @fopen("log.txt", "a");
    fwrite($fd, $api_endpoint  . "\n");
    fwrite($fd, "beginning of check off function <br>");

    $donations = make_api_call($api_endpoint);


    // fwrite($fd, $donations);
    $response = json_decode($donations);





    foreach($response->records as $allrecords => $record) {
        $updated = json_encode(strtotime($record->updatedTimestamp));
        // $ext_id = $record->externalId;

        fwrite($fd, "updated " . $updated . "\n");
        fwrite($fd, "30 days ago" .  $thirty_days_ago . "\n");
        fwrite($fd, "external id " . $record->externalId . "\n");

        //Check External Id


        

        if ($updated >= $thirty_days_ago) {
            
            fwrite($fd, "field value external id " . $record->externalId . "\n");
             foreach ($record->fields as $info) {
                switch ($info->id) {
                    case 'name':
                        $name = $info->value;
                        break;
                    case 'campaign-name':
                        $charity_name = $info->value;
                        break;
                    case 'opportunity-id':
                        $opportunity_id = $info->value;
                        break;
                    case 'campaign-friendly-name':
                        $campaign_friendly_name = $info->value;
                        break;
                    case 'donation-to-fund':
                        $donation_to_fund = $info->value;
                        break;
                    case 'total-gross-donation':
                        $gross_donation_1 = $info->value;
                        break;
                    case 'total-donation-amount':
                        $total_donation_amount = $info->value;
                        break;
                    case 'currency':
                        $currency = $info->value;
                        break;
                    case 'date-of-donation':
                        $date_of_donation = $info->value;
                        break;
                    case 'record-type':
                        $record_type = $info->value;
                        break;
                    case 'donation-type':
                        $donation_type = $info->value;
                        break;
                    case 'stage':
                        $stage = $info->value;
                        break;
                    case 'close-date':
                        $close_date = $info->value;
                        break;
                    case 'acknowledgement-status':
                        $acknowledgement_status = $info->value;
                        break;
                    case 'payment-medium':
                        $payment_medium = $info->value;
                        break;
                    case 'allocation-one-charity-name':
                        $charity_name_one = $info->value;
                        break;
                    case 'allocation-one-designation':
                        $allocation_one_designation = $info->value;
                        break;
                    case 'allocation-one-amount':
                        $allocation_one_amount = $info->value;
                        break;
                    case 'allocation-one-currency':
                        $allocation_one_currency = $info->value;
                        break;
                    case 'allocation-two-charity-name':
                        $allocation_two_charity_name = $info->value;
                        break;
                    case 'allocation-two-designation':
                        $allocation_two_designation = $info->value;
                        break;
                    case 'allocation-two-amount':
                        $allocation_two_amount = $info->value;
                        break;
                    case 'allocation-two-currency':
                        $allocation_two_currency = $info->value;
                        break;
                    case 'allocation-three-charity-name':
                        $allocation_three_charity_name = $info->value;
                        break;
                    case 'allocation-three-designation':
                        $allocation_three_designation = $info->value;
                        break;
                    case 'allocation-three-amount':
                        $allocation_three_amount = $info->value;
                        break;
                    case 'allocation-three-currency':
                        $allocation_three_currency = $info->value;
                        break;
                    case 'allocation-four-charity-name':
                        $allocation_four_charity_name = $info->value;
                        break;
                    case 'allocation-four-designation':
                        $allocation_four_designation = $info->value;
                        break;
                    case 'allocation-four-amount':
                        $allocation_four_amount = $info->value;
                        break;
                    case 'allocation-four-currency':
                        $allocation_four_currency = $info->value;
                        break;
                    case 'allocation-five-charity-name':
                        $allocation_five_charity_name = $info->value;
                        break;
                    case 'allocation-five-designation':
                        $allocation_five_designation = $info->value;
                        break;
                    case 'allocation-five-amount':
                        $allocation_five_amount = $info->value;
                        break;
                    case 'allocation-five-currency':
                        $allocation_five_currency = $info->value;
                        break;
                    case 'allocation-six-charity-name':
                        $allocation_six_charity_name = $info->value;
                        break;
                    case 'allocation-six-designation':
                        $allocation_six_designation = $info->value;
                        break;
                    case 'allocation-six-amount':
                        $allocation_six_amount = $info->value;
                        break;
                    case 'allocation-six-currency':
                        $allocation_six_currency = $info->value;
                        break;
                    case 'allocation-seven-charity-name':
                        $allocation_seven_charity_name = $info->value;
                        break;
                    case 'allocation-seven-designation':
                        $allocation_seven_designation = $info->value;
                        break;
                    case 'allocation-seven-amount':
                        $allocation_seven_amount = $info->value;
                        break;
                    case 'allocation-seven-currency':
                        $allocation_seven_currency = $info->value;
                        break;
                    case 'allocation-eight-charity-name':
                        $allocation_eight_charity_name = $info->value;
                        break;
                    case 'allocation-eight-designation':
                        $allocation_eight_designation = $info->value;
                        break;
                    case 'allocation-eight-amount':
                        $allocation_eight_amount = $info->value;
                        break;
                    case 'allocation-eight-currency':
                        $allocation_eight_currency = $info->value;
                        break;
                    case 'allocation-nine-charity-name':
                        $allocation_nine_charity_name = $info->value;
                        break;
                    case 'allocation-nine-designation':
                        $allocation_nine_designation = $info->value;
                        break;
                    case 'allocation-nine-amount':
                        $allocation_nine_amount = $info->value;
                        break;
                    case 'allocation-nine-currency':
                        $allocation_nine_currency = $info->value;
                        break;
                    case 'allocation-ten-charity-name':
                        $allocation_ten_charity_name = $info->value;
                        break;
                    case 'allocation-ten-designation':
                        $allocation_ten_designation = $info->value;
                        break;
                    case 'allocation-ten-amount':
                        $allocation_ten_amount = $info->value;
                        break;
                    case 'allocation-ten-currency':
                        $allocation_ten_currency = $info->value;
                        break;
                    case 'allocation-eleven-charity-name':
                        $allocation_eleven_charity_name = $info->value;
                        break;
                    case 'allocation-eleven-designation':
                        $allocation_eleven_designation = $info->value;
                        break;
                    case 'allocation-eleven-amount':
                        $allocation_eleven_amount = $info->value;
                        break;
                    case 'allocation-eleven-currency':
                        $allocation_eleven_currency = $info->value;
                        break;
                    case 'allocation-twelve-charity-name':
                        $allocation_twelve_charity_name = $info->value;
                        break;
                    case 'allocation-twelve-designation':
                        $allocation_twelve_designation = $info->value;
                        break;
                    case 'allocation-twelve-amount':
                        $allocation_twelve_amount = $info->value;
                        break;
                    case 'allocation-twelve-currency':
                        $allocation_twelve_currency = $info->value;
                        break;
                    case 'allocation-thirteen-charity-name':
                        $allocation_thirteen_charity_name = $info->value;
                        break;
                    case 'allocation-thirteen-designation':
                        $allocation_thirteen_designation = $info->value;
                        break;
                    case 'allocation-thirteen-amount':
                        $allocation_thirteen_amount = $info->value;
                        break;
                    case 'allocation-thirteen-currency':
                        $allocation_thirteen_currency = $info->value;
                        break;
                    case 'allocation-fourteen-charity-name':
                        $allocation_fourteen_charity_name = $info->value;
                        break;
                    case 'allocation-fourteen-designation':
                        $allocation_fourteen_designation = $info->value;
                        break;
                    case 'allocation-fourteen-amount':
                        $allocation_fourteen_amount = $info->value;
                        break;
                    case 'allocation-fourteen-currency':
                        $allocation_fourteen_currency = $info->value;
                        break;
                    case 'allocation-fifteen-charity-name':
                        $allocation_fifteen_charity_name = $info->value;
                        break;
                    case 'allocation-fifteen-designation':
                        $allocation_fifteen_designation = $info->value;
                        break;
                    case 'allocation-fifteen-amount':
                        $allocation_fifteen_amount = $info->value;
                        break;
                    case 'allocation-fifteen-currency':
                        $allocation_fifteen_currency = $info->value;
                        break;
                    case 'allocation-sixteen-charity-name':
                        $allocation_sixteen_charity_name = $info->value;
                        break;
                    case 'allocation-sixteen-designation':
                        $allocation_sixteen_designation = $info->value;
                        break;
                    case 'allocation-sixteen-amount':
                        $allocation_sixteen_amount = $info->value;
                        break;
                    case 'allocation-sixteen-currency':
                        $allocation_sixteen_currency = $info->value;
                        break;
                    case 'allocation-seventeen-charity-name':
                        $allocation_seventeen_charity_name = $info->value;
                        break;
                    case 'allocation-seventeen-designation':
                        $allocation_seventeen_designation = $info->value;
                        break;
                    case 'allocation-seventeen-amount':
                        $allocation_seventeen_amount = $info->value;
                        break;
                    case 'allocation-seventeen-currency':
                        $allocation_seventeen_currency = $info->value;
                        break;
                    case 'allocation-eighteen-charity-name':
                        $allocation_eighteen_charity_name = $info->value;
                        break;
                    case 'allocation-eighteen-designation':
                        $allocation_eighteen_designation = $info->value;
                        break;
                    case 'allocation-eighteen-amount':
                        $allocation_eighteen_amount = $info->value;
                        break;
                    case 'allocation-eighteen-currency':
                        $allocation_eighteen_currency = $info->value;
                        break;
                    case 'allocation-nineteen-charity-name':
                        $allocation_nineteen_charity_name = $info->value;
                        break;
                    case 'allocation-nineteen-designation':
                        $allocation_nineteen_designation = $info->value;
                        break;
                    case 'allocation-nineteen-amount':
                        $allocation_nineteen_amount = $info->value;
                        break;
                    case 'allocation-nineteen-currency':
                        $allocation_nineteen_currency = $info->value;
                        break;
                    case 'allocation-twenty-charity-name':
                        $allocation_twenty_charity_name = $info->value;
                        break;
                    case 'allocation-twenty-designation':
                        $allocation_twenty_designation = $info->value;
                        break;
                    case 'allocation-twenty-amount':
                        $allocation_twenty_amount = $info->value;
                        break;
                    case 'allocation-twenty-currency':
                        $allocation_twenty_currency = $info->value;
                        break;
                    case 'allocation-twentyone-charity-name':
                        $allocation_twentyone_charity_name = $info->value;
                        break;
                    
                    
                    default:
                        // code...
                        break;
                }
             }   



            $field_data = [ 
                "record" => [
                    "id" => isset(  $record->id ) ? $record->id : '',
                    "externalId" => isset(  $record->externalId ) ? $record->externalId : '',
                    "fields" => [ 
                        [
                            "id" => "name",
                            "value" => isset($charity_name ) ? $charity_name : ''
                        ],
                        [
                            "id" => "campaign-name",
                            "value" => isset($charity_name ) ? $charity_name : ''
                        ],
                                                [
                            "id" => "opportunity-id",
                            "value" => isset( $opportunity_id ) ? $opportunity_id : ''
                        ],
                        [
                            "id" => "campaign-friendly-name",
                            "value" => isset( $campaign_friendly_name ) ? $campaign_friendly_name : ''
                        ],
                        [
                            "id" => "donation-to-fund",
                            "value" => isset( $donation_to_fund ) ? $donation_to_fund : ''
                        ],
                        [
                            "id" => "total-gross-donation",
                            "value" => isset( $gross_donation_1 ) ? $gross_donation_1 : ''
                        ],
                        [
                            "id" => "total-donation-amount",
                            "value" => isset( $total_donation_amount ) ? $total_donation_amount : ''
                        ],
                        [
                            "id" => "currency",
                            "value" => isset( $currency ) ? $currency : ''
                        ],
                        [
                            "id" => "date-of-donation",
                            "value" => isset( $date_of_donation ) ? $date_of_donation : $today
                        ],
                        [
                            "id" => "record-type",
                            "value" => isset( $record_type ) ? $record_type : ''
                        ],
                        [
                            "id" => "donation-type",
                            "value" => isset( $donation_type ) ? $donation_type : ''
                        ],
                        [
                            "id" => "stage",
                            "value" => isset( $stage ) ? $stage : ''
                        ],
                        [
                            "id" => "close-date",
                            "value" => isset( $close_date ) ? $close_date : ''
                        ],

                        [
                            "id" => "acknowledgement-status",
                            "value" => isset( $acknowledgement_status ) ? $acknowledgement_status : ''
                        ],
                        [
                            "id" => "payment-medium",
                            "value" => isset( $payment_medium ) ? $payment_medium : ''
                        ],
                        [
                            "id" => "thank-you-sent",
                            "value" => "yes"
                        ],
                        [
                            "id" => "external-id",
                            "value" => isset( $record->externalId ) ? $record->externalId : ''
                        ],

                        [
                            "id" => "allocation-one-charity-name",
                            "value" => isset( $charity_name_one ) ? $charity_name_one : ''
                        ],
                        [
                            "id" => "allocation-one-designation",
                            "value" => isset( $allocation_one_designation ) ? $allocation_one_designation : ''
                        ],
                        [
                            "id" => "allocation-one-amount",
                            "value" => isset( $allocation_one_amount ) ? $allocation_one_amount : ''
                        ],
                        [
                            "id" => "allocation-one-currency",
                            "value" => isset( $allocation_one_currency ) ? $allocation_one_currency : ''
                        ],
                        [
                            "id" => "allocation-two-charity-name",
                            "value" => isset( $allocation_two_charity_name ) ? $allocation_two_charity_name : ''
                        ],
                        [
                            "id" => "allocation-two-designation",
                            "value" => isset( $allocation_two_designation ) ? $allocation_two_designation : ''
                        ],
                        [
                            "id" => "allocation-two-amount",
                            "value" => isset( $allocation_two_amount ) ? $allocation_two_amount : ''
                        ],
                        [
                            "id" => "allocation-two-currency",
                            "value" => isset( $allocation_two_currency ) ? $allocation_two_currency : ''
                        ],
                        [
                            "id" => "allocation-three-charity-name",
                            "value" => isset( $allocation_three_charity_name ) ? $allocation_three_charity_name : ''
                        ],
                        [
                            "id" => "allocation-three-designation",
                            "value" => isset( $allocation_three_designation ) ? $allocation_three_designation : ''
                        ],
                        [
                            "id" => "allocation-three-amount",
                            "value" => isset( $allocation_three_amount ) ? $allocation_three_amount : ''
                        ],
                        [
                            "id" => "allocation-three-currency",
                            "value" => isset( $allocation_three_currency ) ? $allocation_three_currency : ''
                        ],
                        [
                            "id" => "allocation-four-charity-name",
                            "value" => isset( $allocation_four_charity_name ) ? $allocation_four_charity_name : ''
                        ],
                        [
                            "id" => "allocation-four-designation",
                            "value" => isset( $allocation_four_designation ) ? $allocation_four_designation : ''
                        ],
                        [
                            "id" => "allocation-four-amount",
                            "value" => isset( $allocation_four_amount ) ? $allocation_four_amount : ''
                        ],
                        [
                            "id" => "allocation-four-currency",
                            "value" => isset( $allocation_four_currency ) ? $allocation_four_currency : ''
                        ],
                        [
                            "id" => "allocation-five-charity-name",
                            "value" => isset( $allocation_five_charity_name ) ? $allocation_five_charity_name : ''
                        ],
                        [
                            "id" => "allocation-five-designation",
                            "value" => isset( $allocation_five_designation ) ? $allocation_five_designation : ''
                        ],
                        [
                            "id" => "allocation-five-amount",
                            "value" => isset( $allocation_five_amount ) ? $allocation_five_amount : ''
                        ],
                        [
                            "id" => "allocation-five-currency",
                            "value" => isset( $allocation_five_currency ) ? $allocation_five_currency : ''
                        ],
                        [
                            "id" => "allocation-six-charity-name",
                            "value" => isset( $allocation_six_charity_name ) ? $allocation_six_charity_name : ''
                        ],
                        [
                            "id" => "allocation-six-designation",
                            "value" => isset( $allocation_six_designation ) ?  $allocation_six_designation : ''
                        ],
                        [
                            "id" => "allocation-six-amount",
                            "value" => isset( $allocation_six_amount ) ? $allocation_six_amount : ''
                        ],
                        [
                            "id" => "allocation-six-currency",
                            "value" => isset( $allocation_six_currency ) ? $allocation_six_currency : ''
                        ],
                        [
                            "id" => "allocation-seven-charity-name",
                            "value" => isset( $allocation_seven_charity_name ) ? $allocation_seven_charity_name : ''
                        ],
                        [
                            "id" => "allocation-seven-designation",
                            "value" => isset( $allocation_seven_designation ) ?  $allocation_seven_designation : ''
                        ],
                        [
                            "id" => "allocation-seven-amount",
                            "value" => isset( $allocation_seven_amount ) ? $allocation_seven_amount : ''
                        ],
                        [
                            "id" => "allocation-seven-currency",
                            "value" => isset( $allocation_seven_currency ) ? $allocation_seven_currency : ''
                        ],
                        [
                            "id" => "allocation-eight-charity-name",
                            "value" => isset( $allocation_eight_charity_name ) ? $allocation_eight_charity_name : ''
                        ],
                        [
                            "id" => "allocation-eight-designation",
                            "value" => isset( $allocation_eight_designation ) ?  $allocation_eight_designation : ''
                        ],
                        [
                            "id" => "allocation-eight-amount",
                            "value" => isset( $allocation_eight_amount ) ? $allocation_eight_amount : ''
                        ],
                        [
                            "id" => "allocation-eight-currency",
                            "value" => isset( $allocation_eight_currency ) ? $allocation_eight_currency : ''
                        ],
                        [
                            "id" => "allocation-nine-charity-name",
                            "value" => isset( $allocation_nine_charity_name ) ? $allocation_nine_charity_name : ''
                        ],
                        [
                            "id" => "allocation-nine-designation",
                            "value" => isset( $allocation_nine_designation ) ?  $allocation_nine_designation : ''
                        ],
                        [
                            "id" => "allocation-nine-amount",
                            "value" => isset( $allocation_nine_amount ) ? $allocation_nine_amount : ''
                        ],
                        [
                            "id" => "allocation-nine-currency",
                            "value" => isset( $allocation_nine_currency ) ? $allocation_nine_currency : ''
                        ],

                        [
                            "id" => "allocation-ten-charity-name",
                            "value" => isset( $allocation_ten_charity_name ) ? $allocation_ten_charity_name : ''
                        ],
                        [
                            "id" => "allocation-ten-designation",
                            "value" => isset( $allocation_ten_designation ) ?  $allocation_ten_designation : ''
                        ],
                        [
                            "id" => "allocation-ten-amount",
                            "value" => isset( $allocation_ten_amount ) ? $allocation_ten_amount : ''
                        ],
                        [
                            "id" => "allocation-ten-currency",
                            "value" => isset( $allocation_ten_currency ) ? $allocation_ten_currency : ''
                        ],

                        [
                            "id" => "allocation-eleven-charity-name",
                            "value" => isset( $allocation_eleven_charity_name ) ? $allocation_eleven_charity_name : ''
                        ],
                        [
                            "id" => "allocation-eleven-designation",
                            "value" => isset( $allocation_eleven_designation ) ?  $allocation_eleven_designation : ''
                        ],
                        [
                            "id" => "allocation-eleven-amount",
                            "value" => isset( $allocation_eleven_amount ) ? $allocation_eleven_amount : ''
                        ],
                        [
                            "id" => "allocation-eleven-currency",
                            "value" => isset( $allocation_eleven_currency ) ? $allocation_eleven_currency : ''
                        ],
                        [
                            "id" => "allocation-twelve-charity-name",
                            "value" => isset( $allocation_twelve_charity_name ) ? $allocation_twelve_charity_name : ''
                        ],
                        [
                            "id" => "allocation-twelve-designation",
                            "value" => isset( $allocation_twelve_designation ) ?  $allocation_twelve_designation : ''
                        ],
                        [
                            "id" => "allocation-twelve-amount",
                            "value" => isset( $allocation_twelve_amount ) ? $allocation_twelve_amount : ''
                        ],
                        [
                            "id" => "allocation-twelve-currency",
                            "value" => isset( $allocation_twelve_currency ) ? $allocation_twelve_currency : ''
                        ],
                        [
                            "id" => "allocation-thirteen-charity-name",
                            "value" => isset( $allocation_thirteen_charity_name ) ? $allocation_thirteen_charity_name : ''
                        ],
                        [
                            "id" => "allocation-thirteen-designation",
                            "value" => isset( $allocation_thirteen_designation ) ?  $allocation_thirteen_designation : ''
                        ],
                        [
                            "id" => "allocation-thirteen-amount",
                            "value" => isset( $allocation_thirteen_amount ) ? $allocation_thirteen_amount : ''
                        ],
                        [
                            "id" => "allocation-thirteen-currency",
                            "value" => isset( $allocation_thirteen_currency ) ? $allocation_thirteen_currency : ''
                        ],
                        [
                            "id" => "allocation-fourteen-charity-name",
                            "value" => isset( $allocation_fourteen_charity_name ) ? $allocation_fourteen_charity_name : ''
                        ],
                        [
                            "id" => "allocation-fourteen-designation",
                            "value" => isset( $allocation_fourteen_designation ) ?  $allocation_fourteen_designation : ''
                        ],
                        [
                            "id" => "allocation-fourteen-amount",
                            "value" => isset( $allocation_fourteen_amount ) ? $allocation_fourteen_amount : ''
                        ],
                        [
                            "id" => "allocation-fourteen-currency",
                            "value" => isset( $allocation_fourteen_currency ) ? $allocation_fourteen_currency : ''
                        ],
                        [
                            "id" => "allocation-fifteen-charity-name",
                            "value" => isset( $allocation_fifteen_charity_name ) ? $allocation_fifteen_charity_name : ''
                        ],
                        [
                            "id" => "allocation-fifteen-designation",
                            "value" => isset( $allocation_fifteen_designation ) ?  $allocation_fifteen_designation : ''
                        ],
                        [
                            "id" => "allocation-fifteen-amount",
                            "value" => isset( $allocation_fifteen_amount ) ? $allocation_fifteen_amount : ''
                        ],
                        [
                            "id" => "allocation-fifteen-currency",
                            "value" => isset( $allocation_fifteen_currency ) ? $allocation_fifteen_currency : ''
                        ],
                        [
                            "id" => "allocation-sixteen-charity-name",
                            "value" => isset( $allocation_sixteen_charity_name ) ? $allocation_sixteen_charity_name : ''
                        ],
                        [
                            "id" => "allocation-sixteen-designation",
                            "value" => isset( $allocation_sixteen_designation ) ?  $allocation_sixteen_designation : ''
                        ],
                        [
                            "id" => "allocation-sixteen-amount",
                            "value" => isset( $allocation_sixteen_amount ) ? $allocation_sixteen_amount : ''
                        ],
                        [
                            "id" => "allocation-sixteen-currency",
                            "value" => isset( $allocation_sixteen_currency ) ? $allocation_sixteen_currency : ''
                        ],
                        [
                            "id" => "allocation-seventeen-charity-name",
                            "value" => isset( $allocation_seventeen_charity_name ) ? $allocation_seventeen_charity_name : ''
                        ],
                        [
                            "id" => "allocation-seventeen-designation",
                            "value" => isset( $allocation_seventeen_designation ) ?  $allocation_seventeen_designation : ''
                        ],
                        [
                            "id" => "allocation-seventeen-amount",
                            "value" => isset( $allocation_seventeen_amount ) ? $allocation_seventeen_amount : ''
                        ],
                        [
                            "id" => "allocation-seventeen-currency",
                            "value" => isset( $allocation_seventeen_currency ) ? $allocation_seventeen_currency : ''
                        ],
                        [
                            "id" => "allocation-eighteen-charity-name",
                            "value" => isset( $allocation_eighteen_charity_name ) ? $allocation_eighteen_charity_name : ''
                        ],
                        [
                            "id" => "allocation-eighteen-designation",
                            "value" => isset( $allocation_eighteen_designation ) ?  $allocation_eighteen_designation : ''
                        ],
                        [
                            "id" => "allocation-eighteen-amount",
                            "value" => isset( $allocation_eighteen_amount ) ? $allocation_eighteen_amount : ''
                        ],
                        [
                            "id" => "allocation-eighteen-currency",
                            "value" => isset( $allocation_eighteen_currency ) ? $allocation_eighteen_currency : ''
                        ],
                        [
                            "id" => "allocation-nineteen-charity-name",
                            "value" => isset( $allocation_nineteen_charity_name ) ? $allocation_nineteen_charity_name : ''
                        ],
                        [
                            "id" => "allocation-nineteen-designation",
                            "value" => isset( $allocation_nineteen_designation ) ?  $allocation_nineteen_designation : ''
                        ],
                        [
                            "id" => "allocation-nineteen-amount",
                            "value" => isset( $allocation_nineteen_amount ) ? $allocation_nineteen_amount : ''
                        ],
                        [
                            "id" => "allocation-nineteen-currency",
                            "value" => isset( $allocation_nineteen_currency ) ? $allocation_nineteen_currency : ''
                        ],
                        [
                            "id" => "allocation-twenty-charity-name",
                            "value" => isset( $allocation_twenty_charity_name ) ? $allocation_twenty_charity_name : ''
                        ],
                        [
                            "id" => "allocation-twenty-designation",
                            "value" => isset( $allocation_twenty_designation ) ?  $allocation_twenty_designation : ''
                        ],
                        [
                            "id" => "allocation-twenty-amount",
                            "value" => isset( $allocation_twenty_amount ) ? $allocation_twenty_amount : ''
                        ],
                        [
                            "id" => "allocation-twenty-currency",
                            "value" => isset( $allocation_twenty_currency ) ? $allocation_twenty_currency : ''
                        ]
                    
                ],
                    "relationships" => [
                        "primary-contact" => [ $ac_contact_id ]
                    ]
                ]
            ];

            fwrite($fd, "after field data array \n");



            $json_field = json_encode($field_data);

            fwrite($fd, "04/18 - after json encoding field data " . $json_field . "\n");

            update_thankyou_status_of_donation($json_field);
        }

    } 
        fclose($fd);
} 



//This function creates a new Salesforce Donation Custom Object if the External ID doesn't exist. 
//If the external ID exists, the Donation Custom Object already exists and is updated. 
function add_ac_donation($opportunity_array, $allocation_array, $contact_id){


    $api_endpoint = 'customObjects/records/76a93b0b-8590-4ea5-8bd7-6e83bb06ffc3';
    $fields = field_handler_for_opportunities($opportunity_array, $allocation_array, $contact_id);


    $fd = @fopen("webhook.txt", "a");
fwrite($fd, "AC Fields: \n\n");

ob_start();

var_dump($fields);

$datafields = ob_get_clean();

fwrite($fd, $datafields);
fclose($fd);

    $opportunity = make_api_call($api_endpoint, $query = '' , $fields, 'POST');

}

function update_thankyou_status_of_donation($data) {

    $api_endpoint = 'customObjects/records/76a93b0b-8590-4ea5-8bd7-6e83bb06ffc3';

     $opportunity = make_api_call($api_endpoint, $query = '' , $data, 'POST');


}

