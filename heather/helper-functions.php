<?php

function pp($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    exit;
    
}

function ph($data){
    echo '<h2>'.$data.'</h2>';
}

function pp_json($data){
    pp( json_decode($data ) );
}

function dd($data){
    echo '<pre>';
    var_dump($data);
    echo '</pre>';

    exit;
}

function write($data) {
    $fd = @fopen("webhook.txt", "a");
    fwrite($fd, "Fields: \n\n");

    ob_start();

    var_dump($data);

    $write_to_file = ob_get_clean();

    fwrite($fd, $write_to_file);
    fclose($fd);
}


function d_log($message){
    $depth = 4;
    
    
    $backtraceCount = count(debug_backtrace());
    
    if($backtraceCount > $depth ){
        return;
    }
    
    
    $backtraceCount = ( ($backtraceCount - 1) <= 0 ) ? 0 : ($backtraceCount - 1);
    
    $paddingValue = 75*$backtraceCount;
    
    $styleString = 'style="padding-left: '.$paddingValue.'px;"';
    
    echo '<p '.$styleString.'>'.$message.'</p>';
    
}



/*
    Main App Helper Functions
*/

//This function maps the data from Salesforce into the correct field in the custom object in ActiveCampaign
function field_handler_for_opportunities($opportunity_array, $allocation_array, $ac_contact_id) {
    
    //When supplied with an external id, ActiveCampaign will check to see if a custom object with that external id exists;
    //If it does exist, the custom object will be updated; If it doesn't exist, it will create one.


    $fd = @fopen("webhook.txt", "a");
fwrite($fd, "Opportunity: \n\n");

ob_start();

var_dump($opportunity_array);

$stuff = ob_get_clean();

fwrite($fd, $stuff);
fclose($fd);


    $dt = new DateTime("now");
    $today = $dt->format("Y-m-d");    
    $sf_opp_id = "salesforce-opp-id-" . $opportunity_array['sfId'];

    $amount = floatval($opportunity_array['sfAmount']);
    $gross_donation = floatval($opportunity_array['sfGross_Donation__c']);


    $record_type_name = sf_get_record_type_name($opportunity_array['sfRecordTypeId']);

    $opportunity_field_data = [ 
        "record" => [
            "externalId" => isset(  $sf_opp_id ) ? $sf_opp_id : '',
            "fields" => [ 
            [
                "id" => "name",
                "value" => isset(  $opportunity_array['sfCampaign__c'] ) ? $opportunity_array['sfCampaign__c'] : ''
            ],
            [
                "id" => "campaign-name",
                "value" => isset(   $opportunity_array['sfCampaign__c'] ) ?  $opportunity_array['sfCampaign__c'] : ''
            ],
            [
                "id" => "opportunity-id",
                "value" => isset(  $opportunity_array['sfId'] ) ?  $opportunity_array['sfId']  : ''
            ],
            [
                "id" => "campaign-friendly-name",
                "value" => isset(  $opportunity_array['sfCampaign_Friendly_Name__c'] ) ? $opportunity_array['sfCampaign_Friendly_Name__c'] : ''
            ],
            [
                "id" => "donation-to-fund",
                "value" => isset(  $opportunity_array['sfDonate_to_Fund__c'] ) ?  $opportunity_array['sfDonate_to_Fund__c'] : ''
            ],
            [
                "id" => "total-gross-donation",
                "value" => isset( $gross_donation ) ? $gross_donation : 0
            ],
            [
                "id" => "total-donation-amount",
                "value" => isset( $amount ) ? $amount : 0
            ],
            [
                "id" => "currency",
                "value" => isset(  $opportunity_array['sfCurrencyIsoCode'] ) ? $opportunity_array['sfCurrencyIsoCode'] : ''
            ],
            [
                "id" => "date-of-donation",
                "value" => isset(  $opportunity_array['sfGift_Date_If_This_Year__c'] ) ? $opportunity_array['sfGift_Date_If_This_Year__c'] : $today
            ],
            [
                "id" => "record-type",
                "value" => isset(  $record_type_name ) ? $record_type_name : ''
            ],
            [
                "id" => "donation-type",
                "value" => isset(  $opportunity_array['sfType'] ) ? $opportunity_array['sfType'] : ''
            ],
            [
                "id" => "stage",
                "value" => isset(  $opportunity_array['sfStageName'] ) ? $opportunity_array['sfStageName'] : ''
            ],
            [
                "id" => "close-date",
                "value" => isset(  $opportunity_array['sfCloseDate'] ) ? $opportunity_array['sfCloseDate'] : ''
            ],
            
            [
                "id" => "acknowledgement-status",
                "value" => isset(  $opportunity_array['sfnpsp__Acknowledgment_Status__c'] ) ? $opportunity_array['sfnpsp__Acknowledgment_Status__c'] : ''
            ],
            [
                "id" => "payment-medium",
                "value" => isset(  $opportunity_array['sfPayment_Medium__c'] ) ?  $opportunity_array['sfPayment_Medium__c'] : ''
            ],
            [
                "id" => "thank-you-sent",
                "value" => isset(  $opportunity_array['sfThank_You_Sent__c'] ) ? $opportunity_array['sfThank_You_Sent__c']  : ''
            ],
            [
                "id" => "external-id",
                "value" => isset(  $sf_opp_id ) ? $sf_opp_id : ''
            ], 
            [
                "id" => "allocation-one-charity-name",
                "value" => isset(  $allocation_array[0]->Organization__c ) ? $allocation_array[0]->Organization__c : ''
            ],
            [
                "id" => "allocation-one-designation",
                "value" => isset(  $allocation_array[0]->Designation__c ) ? $allocation_array[0]->Designation__c : ''
            ],
            [
                "id" => "allocation-one-amount",
                "value" => isset(  $allocation_array[0]->npsp__Amount__c ) ? $allocation_array[0]->npsp__Amount__c : 0
            ],
            [
                "id" => "allocation-one-currency",
                "value" => isset(  $allocation_array[0]->CurrencyIsoCode ) ? $allocation_array[0]->CurrencyIsoCode : ''
            ],
            [
                "id" => "allocation-two-charity-name",
                "value" => isset(  $allocation_array[1]->Organization__c ) ? $allocation_array[1]->Organization__c : ''
            ],
            [
                "id" => "allocation-two-designation",
                "value" => isset(  $allocation_array[1]->Designation__c ) ? $allocation_array[1]->Designation__c : ''
            ],
            [
                "id" => "allocation-two-amount",
                "value" => isset(  $allocation_array[1]->npsp__Amount__c ) ? $allocation_array[1]->npsp__Amount__c : 0
            ],
            [
                "id" => "allocation-two-currency",
                "value" => isset(  $allocation_array[1]->CurrencyIsoCode ) ? $allocation_array[1]->CurrencyIsoCode : ''
            ],
            [
                "id" => "allocation-three-charity-name",
                "value" => isset(  $allocation_array[2]->Organization__c ) ? $allocation_array[2]->Organization__c : ''
            ],
            [
                "id" => "allocation-three-designation",
                "value" => isset(  $allocation_array[2]->Designation__c ) ? $allocation_array[2]->Designation__c : ''
            ],
            [
                "id" => "allocation-three-amount",
                "value" => isset(  $allocation_array[2]->npsp__Amount__c ) ? $allocation_array[2]->npsp__Amount__c : 0
            ],
            [
                "id" => "allocation-three-currency",
                "value" => isset(  $allocation_array[2]->CurrencyIsoCode ) ? $allocation_array[2]->CurrencyIsoCode : ''
            ],
            [
                "id" => "allocation-four-charity-name",
                "value" => isset(  $allocation_array[3]->Organization__c ) ? $allocation_array[3]->Organization__c : ''
            ],
            [
                "id" => "allocation-four-designation",
                "value" => isset(  $allocation_array[3]->Designation__c ) ? $allocation_array[3]->Designation__c : ''
            ],
            [
                "id" => "allocation-four-amount",
                "value" => isset(  $allocation_array[3]->npsp__Amount__c ) ? $allocation_array[3]->npsp__Amount__c : 0
            ],
            [
                "id" => "allocation-four-currency",
                "value" => isset(  $allocation_array[3]->CurrencyIsoCode ) ? $allocation_array[3]->CurrencyIsoCode : ''
            ],
            [
                "id" => "allocation-five-charity-name",
                "value" => isset(  $allocation_array[4]->Organization__c ) ? $allocation_array[4]->Organization__c : ''
            ],
            [
                "id" => "allocation-five-designation",
                "value" => isset(  $allocation_array[4]->Designation__c ) ? $allocation_array[4]->Designation__c : ''
            ],
            [
                "id" => "allocation-five-amount",
                "value" => isset(  $allocation_array[4]->npsp__Amount__c ) ? $allocation_array[4]->npsp__Amount__c : 0
            ],
            [
                "id" => "allocation-five-currency",
                "value" => isset(  $allocation_array[4]->CurrencyIsoCode ) ? $allocation_array[4]->CurrencyIsoCode : ''
            ],
            [
                "id" => "allocation-six-charity-name",
                "value" => isset(  $allocation_array[5]->Organization__c ) ? $allocation_array[5]->Organization__c : ''
            ],
            [
                "id" => "allocation-six-designation",
                "value" => isset(  $allocation_array[5]->Designation__c ) ? $allocation_array[5]->Designation__c : ''
            ],
            [
                "id" => "allocation-six-amount",
                "value" => isset(  $allocation_array[5]->npsp__Amount__c ) ? $allocation_array[5]->npsp__Amount__c : 0
            ],
            [
                "id" => "allocation-six-currency",
                "value" => isset(  $allocation_array[5]->CurrencyIsoCode ) ? $allocation_array[5]->CurrencyIsoCode : ''
            ],
            [
                "id" => "allocation-seven-charity-name",
                "value" => isset(  $allocation_array[6]->Organization__c ) ? $allocation_array[6]->Organization__c : ''
            ],
            [
                "id" => "allocation-seven-designation",
                "value" => isset(  $allocation_array[6]->Designation__c ) ? $allocation_array[6]->Designation__c : ''
            ],
            [
                "id" => "allocation-seven-amount",
                "value" => isset(  $allocation_array[6]->npsp__Amount__c ) ? $allocation_array[6]->npsp__Amount__c : 0
            ],
            [
                "id" => "allocation-seven-currency",
                "value" => isset(  $allocation_array[6]->CurrencyIsoCode ) ? $allocation_array[6]->CurrencyIsoCode : ''
            ],
            [
                "id" => "allocation-eight-charity-name",
                "value" => isset(  $allocation_array[7]->Organization__c ) ? $allocation_array[7]->Organization__c : ''
            ],
            [
                "id" => "allocation-eight-designation",
                "value" => isset(  $allocation_array[7]->Designation__c ) ? $allocation_array[7]->Designation__c : ''
            ],
            [
                "id" => "allocation-eight-amount",
                "value" => isset(  $allocation_array[7]->npsp__Amount__c ) ? $allocation_array[7]->npsp__Amount__c : 0
            ],
            [
                "id" => "allocation-eight-currency",
                "value" => isset(  $allocation_array[7]->CurrencyIsoCode ) ? $allocation_array[7]->CurrencyIsoCode : ''
            ],
            [
                "id" => "allocation-nine-charity-name",
                "value" => isset(  $allocation_array[8]->Organization__c ) ? $allocation_array[8]->Organization__c : ''
            ],
            [
                "id" => "allocation-nine-designation",
                "value" => isset(  $allocation_array[8]->Designation__c ) ? $allocation_array[8]->Designation__c : ''
            ],
            [
                "id" => "allocation-nine-amount",
                "value" => isset(  $allocation_array[8]->npsp__Amount__c ) ? $allocation_array[8]->npsp__Amount__c : 0
            ],
            [
                "id" => "allocation-nine-currency",
                "value" => isset(  $allocation_array[8]->CurrencyIsoCode ) ? $allocation_array[8]->CurrencyIsoCode : ''
            ],
            [
                "id" => "allocation-ten-charity-name",
                "value" => isset(  $allocation_array[9]->Organization__c ) ? $allocation_array[9]->Organization__c : ''
            ],
            [
                "id" => "allocation-ten-designation",
                "value" => isset(  $allocation_array[9]->Designation__c ) ? $allocation_array[9]->Designation__c : ''
            ],
            [
                "id" => "allocation-ten-amount",
                "value" => isset(  $allocation_array[9]->npsp__Amount__c ) ? $allocation_array[9]->npsp__Amount__c : 0
            ],
            [
                "id" => "allocation-ten-currency",
                "value" => isset(  $allocation_array[9]->CurrencyIsoCode ) ? $allocation_array[9]->CurrencyIsoCode : ''
            ],
            [
                "id" => "allocation-eleven-charity-name",
                "value" => isset(  $allocation_array[10]->Organization__c ) ? $allocation_array[10]->Organization__c : ''
            ],
            [
                "id" => "allocation-eleven-designation",
                "value" => isset(  $allocation_array[10]->Designation__c ) ? $allocation_array[10]->Designation__c : ''
            ],
            [
                "id" => "allocation-eleven-amount",
                "value" => isset(  $allocation_array[10]->npsp__Amount__c ) ? $allocation_array[10]->npsp__Amount__c : 0
            ],
            [
                "id" => "allocation-eleven-currency",
                "value" => isset(  $allocation_array[10]->CurrencyIsoCode ) ? $allocation_array[10]->CurrencyIsoCode : ''
            ],
            [
                "id" => "allocation-twelve-charity-name",
                "value" => isset(  $allocation_array[11]->Organization__c ) ? $allocation_array[11]->Organization__c : ''
            ],
            [
                "id" => "allocation-twelve-designation",
                "value" => isset(  $allocation_array[11]->Designation__c ) ? $allocation_array[11]->Designation__c : ''
            ],
            [
                "id" => "allocation-twelve-amount",
                "value" => isset(  $allocation_array[11]->npsp__Amount__c ) ? $allocation_array[11]->npsp__Amount__c : 0
            ],
            [
                "id" => "allocation-twelve-currency",
                "value" => isset(  $allocation_array[11]->CurrencyIsoCode ) ? $allocation_array[11]->CurrencyIsoCode : ''
            ],
            [
                "id" => "allocation-thirteen-charity-name",
                "value" => isset(  $allocation_array[12]->Organization__c ) ? $allocation_array[12]->Organization__c : ''
            ],
            [
                "id" => "allocation-thirteen-designation",
                "value" => isset(  $allocation_array[12]->Designation__c ) ? $allocation_array[12]->Designation__c : ''
            ],
            [
                "id" => "allocation-thirteen-amount",
                "value" => isset(  $allocation_array[12]->npsp__Amount__c ) ? $allocation_array[12]->npsp__Amount__c : 0
            ],
            [
                "id" => "allocation-thirteen-currency",
                "value" => isset(  $allocation_array[12]->CurrencyIsoCode ) ? $allocation_array[12]->CurrencyIsoCode : ''
            ],
            [
                "id" => "allocation-fourteen-charity-name",
                "value" => isset(  $allocation_array[13]->Organization__c ) ? $allocation_array[13]->Organization__c : ''
            ],
            [
                "id" => "allocation-fourteen-designation",
                "value" => isset(  $allocation_array[13]->Designation__c ) ? $allocation_array[13]->Designation__c : ''
            ],
            [
                "id" => "allocation-fourteen-amount",
                "value" => isset(  $allocation_array[13]->npsp__Amount__c ) ? $allocation_array[13]->npsp__Amount__c : 0
            ],
            [
                "id" => "allocation-fourteen-currency",
                "value" => isset(  $allocation_array[13]->CurrencyIsoCode ) ? $allocation_array[13]->CurrencyIsoCode : ''
            ],
            [
                "id" => "allocation-fifteen-charity-name",
                "value" => isset(  $allocation_array[14]->Organization__c ) ? $allocation_array[14]->Organization__c : ''
            ],
            [
                "id" => "allocation-fifteen-designation",
                "value" => isset(  $allocation_array[14]->Designation__c ) ? $allocation_array[14]->Designation__c : ''
            ],
            [
                "id" => "allocation-fifteen-amount",
                "value" => isset(  $allocation_array[14]->npsp__Amount__c ) ? $allocation_array[14]->npsp__Amount__c : 0
            ],
            [
                "id" => "allocation-fifteen-currency",
                "value" => isset(  $allocation_array[14]->CurrencyIsoCode ) ? $allocation_array[14]->CurrencyIsoCode : ''
            ],
            [
                "id" => "allocation-sixteen-charity-name",
                "value" => isset(  $allocation_array[15]->Organization__c ) ? $allocation_array[15]->Organization__c : ''
            ],
            [
                "id" => "allocation-sixteen-designation",
                "value" => isset(  $allocation_array[15]->Designation__c ) ? $allocation_array[15]->Designation__c : ''
            ],
            [
                "id" => "allocation-sixteen-amount",
                "value" => isset(  $allocation_array[15]->npsp__Amount__c ) ? $allocation_array[15]->npsp__Amount__c : 0
            ],
            [
                "id" => "allocation-sixteen-currency",
                "value" => isset(  $allocation_array[15]->CurrencyIsoCode ) ? $allocation_array[15]->CurrencyIsoCode : ''
            ],
            [
                "id" => "allocation-seventeen-charity-name",
                "value" => isset(  $allocation_array[16]->Organization__c ) ? $allocation_array[16]->Organization__c : ''
            ],
            [
                "id" => "allocation-seventeen-designation",
                "value" => isset(  $allocation_array[16]->Designation__c ) ? $allocation_array[16]->Designation__c : ''
            ],
            [
                "id" => "allocation-seventeen-amount",
                "value" => isset(  $allocation_array[16]->npsp__Amount__c ) ? $allocation_array[16]->npsp__Amount__c : 0
            ],
            [
                "id" => "allocation-seventeen-currency",
                "value" => isset(  $allocation_array[16]->CurrencyIsoCode ) ? $allocation_array[16]->CurrencyIsoCode : ''
            ],
            [
                "id" => "allocation-eighteen-charity-name",
                "value" => isset(  $allocation_array[17]->Organization__c ) ? $allocation_array[17]->Organization__c : ''
            ],
            [
                "id" => "allocation-eighteen-designation",
                "value" => isset(  $allocation_array[17]->Designation__c ) ? $allocation_array[17]->Designation__c : ''
            ],
            [
                "id" => "allocation-eighteen-amount",
                "value" => isset(  $allocation_array[17]->npsp__Amount__c ) ? $allocation_array[17]->npsp__Amount__c : 0
            ],
            [
                "id" => "allocation-eighteen-currency",
                "value" => isset(  $allocation_array[17]->CurrencyIsoCode ) ? $allocation_array[17]->CurrencyIsoCode : ''
            ],
            [
                "id" => "allocation-nineteen-charity-name",
                "value" => isset(  $allocation_array[18]->Organization__c ) ? $allocation_array[18]->Organization__c : ''
            ],
            [
                "id" => "allocation-nineteen-designation",
                "value" => isset(  $allocation_array[18]->Designation__c ) ? $allocation_array[18]->Designation__c : ''
            ],
            [
                "id" => "allocation-nineteen-amount",
                "value" => isset(  $allocation_array[18]->npsp__Amount__c ) ? $allocation_array[18]->npsp__Amount__c : 0
            ],
            [
                "id" => "allocation-nineteen-currency",
                "value" => isset(  $allocation_array[18]->CurrencyIsoCode ) ? $allocation_array[18]->CurrencyIsoCode : ''
            ],
            [
                "id" => "allocation-twenty-charity-name",
                "value" => isset(  $allocation_array[19]->Organization__c ) ? $allocation_array[19]->Organization__c : ''
            ],
            [
                "id" => "allocation-twenty-designation",
                "value" => isset(  $allocation_array[19]->Designation__c ) ? $allocation_array[19]->Designation__c : ''
            ],
            [
                "id" => "allocation-twenty-amount",
                "value" => isset(  $allocation_array[19]->npsp__Amount__c ) ? $allocation_array[19]->npsp__Amount__c : 0
            ],
            [
                "id" => "allocation-twenty-currency",
                "value" => isset(  $allocation_array[19]->CurrencyIsoCode ) ? $allocation_array[19]->CurrencyIsoCode : ''
            ]
  
        ],
            "relationships" => [
                "primary-contact" => [ $ac_contact_id ]
            ]
        ]
    ];


    $json_opportunity_field_data = json_encode($opportunity_field_data);

    return $json_opportunity_field_data;
}


