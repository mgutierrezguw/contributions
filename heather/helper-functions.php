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


    $dt = new DateTime("now");
    $today = $dt->format("Y-m-d");    
    $sf_opp_id = "salesforce-opp-id-" . $opportunity_array->Id;

    $record_type_name = sf_get_record_type_name($opportunity_array->RecordTypeId);

    $opportunity_field_data = [ 
        "record" => [
            "externalId" => isset(  $sf_opp_id ) ? $sf_opp_id : '',
            "fields" => [ 
            [
                "id" => "charity-name",
                "value" => isset(  $opportunity_array->Campaign__c ) ? $opportunity_array->Campaign__c : ''
            ],
            [
                "id" => "total-donation-amount",
                "value" => isset(  $opportunity_array->Amount ) ? $opportunity_array->Amount : ''
            ],
            [
                "id" => "currency",
                "value" => isset(  $opportunity_array->CurrencyIsoCode ) ? $opportunity_array->CurrencyIsoCode : ''
            ],
            [
                "id" => "date-of-donation",
                "value" => isset(  $opportunity_array->Gift_Date_If_This_Year__c ) ? $opportunity_array->Gift_Date_If_This_Year__c : $today
            ],
            [
                "id" => "record-type",
                "value" => isset(  $record_type_name ) ? $record_type_name : ''
            ],
            [
                "id" => "donation-type",
                "value" => isset(  $opportunity_array->Type ) ? $opportunity_array->Type : ''
            ],
            [
                "id" => "stage",
                "value" => isset(  $opportunity_array->StageName ) ? $opportunity_array->StageName : ''
            ],
            [
                "id" => "close-date",
                "value" => isset(  $opportunity_array->CloseDate ) ? $opportunity_array->CloseDate : ''
            ],
            [
                "id" => "gross-donation-1",
                "value" => isset(  $opportunity_array->Gross_Donation__c ) ? $opportunity_array->Gross_Donation__c : ''
            ],
            [
                "id" => "acknowledgement-status",
                "value" => isset(  $opportunity_array->npsp__Acknowledgment_Status__c ) ? $opportunity_array->npsp__Acknowledgment_Status__c : ''
            ],
            [
                "id" => "payment-medium",
                "value" => isset(  $opportunity_array->Payment_Medium__c ) ? $opportunity_array->Payment_Medium__c : ''
            ],
            [
                "id" => "thank-you-sent",
                "value" => isset(  $opportunity_array->Thank_You_Sent__c ) ? $opportunity_array->Thank_You_Sent__c : ''
            ],
            [
                "id" => "external-id",
                "value" => isset(  $sf_opp_id ) ? $sf_opp_id : ''
            ],
            [
                "id" => "opportunity-id",
                "value" => isset(  $opportunity_array->Id ) ? $opportunity_array->Id : ''
            ],
            [
                "id" => "charity-name-one",
                "value" => isset(  $allocation_array[0]->Organization__c ) ? $allocation_array[0]->Organization__c : ''
            ],
            [
                "id" => "allocation-one-designation",
                "value" => isset(  $allocation_array[0]->Designation__c ) ? $allocation_array[0]->Designation__c : ''
            ],
            [
                "id" => "allocation-one-amount-1",
                "value" => isset(  $allocation_array[0]->npsp__Amount__c ) ? $allocation_array[0]->npsp__Amount__c : ''
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
                "id" => "allocation-two-amount-1",
                "value" => isset(  $allocation_array[1]->npsp__Amount__c ) ? $allocation_array[1]->npsp__Amount__c : ''
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
                "id" => "allocation-three-amount-1",
                "value" => isset(  $allocation_array[2]->npsp__Amount__c ) ? $allocation_array[2]->npsp__Amount__c : ''
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
                "id" => "allocation-four-amount-1",
                "value" => isset(  $allocation_array[3]->npsp__Amount__c ) ? $allocation_array[3]->npsp__Amount__c : ''
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
                "id" => "allocation-five-amount-1",
                "value" => isset(  $allocation_array[4]->npsp__Amount__c ) ? $allocation_array[4]->npsp__Amount__c : ''
            ],
            [
                "id" => "allocation-five-currency",
                "value" => isset(  $allocation_array[4]->CurrencyIsoCode ) ? $allocation_array[4]->CurrencyIsoCode : ''
            ],
            [
                "id" => "donation-to-fund",
                "value" => isset(  $opportunity_array->Donate_to_Fund__c ) ? $opportunity_array->Donate_to_Fund__c : ''
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




