<?php
include('helper-functions.php');
include('salesforce-api.php');
include('active-api.php');



//When an Opp is created or updated in Salesforce, the script is hit by a webhook and flow in Salesforce.
//Inside SF, under Setup -> Process Automation -> Workflow Actions -> Outbound Messages, we will determine what data is sent ot the webhook
//Inside SF, we'll build a Flow that handles the passing of that data to the script on our server.
//Are there any conditions on what Opportunities should get passed to the script?


//When the data comes through to this script, it will be a single Opportunity's informaton.
$xml = file_get_contents("php://input");



$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", '$1$2$3', $xml);
$xml = simplexml_load_string($xml);
$json = json_encode($xml);
$responseArray = json_decode($json, true); // true to have an array, false for an object


$fd = @fopen("webhook.txt", "a");

// fwrite($fd, "Response from Salesforce: \n\n");
// ob_start();
// var_dump($responseArray);

// $data = ob_get_clean();
// fwrite($fd, $data);

// fclose($fd);

//single opportunity
$singleOppArray = $singleOppArray = $responseArray['soapenvBody']['notifications']['Notification']['sObject'];


$singleOppId = $singleOppArray['sfId'];
$opp_last_modified = substr($singleOppArray['sfLastModifiedDate'], 0, 10);
$opp_created = substr($singleOppArray['sfCreatedDate'], 0, 10);



// Get allocations on that opportunity
$returned_allocations = get_sf_allocations_on_contact($singleOppId);
$allocations_on_opportunity = $returned_allocations->records;

// Using the email address on the Contact in SF, we will check to see if the same contact exists in AC
// If the contact does not exist in AC, we create one. If the contact does exist, we update it.
$contact_id = $singleOppArray['sfContactId'];


//find contact's email address
$individual_contact_info = sf_get_contact($contact_id);


$individual_email = $individual_contact_info->Email;

$ac_contact = find_activecampaign_contact_by_email($individual_email);

//if a contact is not in ActiveCampaign, create one; get the Contact Id in ActiveCampaign
if($ac_contact == false) {

	$data_ac_contact = [
		'contact'=> [
			'email' => $individual_email, 
			'firstName' => $individual_contact_info->FirstName, 
			'lastName' => $individual_contact_info->LastName
		]
	];

	$json_data_ac_contact = json_encode($data_ac_contact);

	$ac_contact_id = create_ac_contact($json_data_ac_contact);

} else {

	$ac_contact_id = $ac_contact->id;
}


//NEED TO FIGURE OUT LOGIC - CHECK TO SEE WHAT IS ALREADY IN THERE BECAUSE OPPS ARE GOING TO BE TRIGGERING NUMEROUS TIMES IN ONE DAY

$all_charities_last_day = [];

// This sets the Latest Charity field in AC
if($singleOppArray['sfCampaign_Friendly_Name__c'] == '') {
	$charity_name = $singleOppArray['sfCampaign__c'];
} else {
	$charity_name = $singleOppArray['sfCampaign_Friendly_Name__c'];
}

array_push($all_charities_last_day, $charity_name);

$charity_names = implode(", ", $all_charities_last_day);

$latest_charity = [
	"fieldValue" => [
		"contact" => $ac_contact_id,
		"field" => 54,
		"value" => $charity_names
	]
];


//update the latest charity fields
echo "<br> This is before the charity field update: <br>";
update_custom_field_on_contact($latest_charity);

//add a Donation custom object in activecampaign 
$result = add_ac_donation($singleOppArray, $allocations_on_opportunity, $ac_contact_id);



	if($individual_contact_info->HasOptedOutOfEmail == 1) {
		$email_opt_out = 'true';
	} else {
		$email_opt_out = 'false';
	}

	$email_opt_out_status = [
		"fieldValue" => [
			"contact" => $ac_contact_id,
			"field" => 49,
			"value" => $email_opt_out
		]
	];

	update_custom_field_on_contact($email_opt_out_status);


	$contact_owner_name = get_sf_contact_owner_name($individual_contact_info->OwnerId);
	$update_contact_owner_name = [
		"fieldValue" => [
			"contact" => $ac_contact_id,
			"field" => 36,
			"value" => $contact_owner_name
		]
	];

	update_custom_field_on_contact($update_contact_owner_name);



