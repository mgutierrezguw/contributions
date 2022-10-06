<?php
include('helper-functions.php');
include('salesforce-api.php');
include('active-api.php');



/* Main App Logic */

/* Note: This is the file which imports contacts directly into ActiveCampaign. */

//https://gstaging.getuwired.us/engconcepts/heather/TLYCS/next-steps-tlycs/main-app.php

// Get a list of contacts from Salesforce from updated within the last 24 hours

// /*
//  by Date Parameters
// */


$dt = new DateTime("now");
$today = $dt->format("Y-m-d");
$end = $today . "T23:00:00Z";


$date = new DateTime();
$date->sub(new DateInterval('P1D'));
$yesterday = $date->format('Y-m-d');
$start = $yesterday . "T00:00:00Z";



echo "get updated contacts in Salesforce: ";
$sc_contact_id_array = get_sf_updated_contacts($start, $end);


d_log('Finding contacts from Salesforce API call updated in last 24 hours.</pre>');


foreach ($sc_contact_id_array as $key => $contact_id) {
	
	// echo "New Contact: " . $contact_id . "<br>";
	
	//find contact's email address
	$individual_contact_info = sf_get_contact($contact_id);

	$individual_email = $individual_contact_info->Email;

	$ac_contact = find_activecampaign_contact_by_email($individual_email);

	//if a contact is not in ActiveCampaign, create one; get the Contact Id in ActiveCampaign
	if($ac_contact == false) {

		$data = [
			'contact'=> [
				'email' => $individual_email, 
				'firstName' => $individual_contact_info->FirstName, 
				'lastName' => $individual_contact_info->LastName
			]
		];

		$json_data = json_encode($data);

		$ac_contact_id = create_ac_contact($json_data);

	} else {

		$ac_contact_id = $ac_contact->id;
	}

	echo "AC Contact Id: " . $ac_contact_id . "<br>";

	//find opportunities on that Salesforce contact
	$opportunities = get_sf_opportunities_on_contact($contact_id);
	$opportunity_array = $opportunities->records;

	foreach ($opportunity_array as $key => $val) {

		$opp_last_modified = substr($val->LastModifiedDate, 0, 10);
		$opp_created = substr($val->CreatedDate, 0, 10);
		
		//create a unique external id for each donation opportunity so that it could be updated if it is already in there
		$sf_opp_id = "salesforce-opp-id-" . $val->Id;
		// echo "External ID: " . $val->Id;

		echo "<pre>";
		echo "Opportunity: <br>";
		print_r($val);

		echo "</pre>";


		//get allocations on that contact
		$returned_allocations = get_sf_allocations_on_contact($val->Id);
		$allocations_on_opportunity = $returned_allocations->records;

		// echo "<pre>";
		// echo "Allocations on Opportunity: <br>";
		// print_r($allocations_on_opportunity);

		// echo "</pre>";

		//create a new array to push new allocations into the contact in ActiveCampaign
		$new_allocations = [];

		foreach($allocations_on_opportunity as $allocation_info => $allocation_value) {			

			$allocation_last_modified = substr($allocation_value->LastModifiedDate, 0, 10);

			//finding allocations that were modified or created in the last two days
			if($allocation_last_modified == $today || $allocation_last_modified == $yesterday) {

				array_push($new_allocations, $allocation_value);
			}
		}
		
		$charity_name = $val->Campaign__c;
		
		$latest_charity = [
			"fieldValue" => [
				"contact" => $ac_contact_id,
				"field" => 54,
				"value" => $charity_name
			]
		];

		
		// If the opportunity was last modified today or yesterday, add to ActiveCampaign
		if($opp_last_modified != $yesterday && $opp_last_modified != $today ) {

			continue;

		} else {

			//add a Donation custom object in activecampaign 
			$result = add_ac_donation($val, $new_allocations, $ac_contact_id);

			//update the latest charity fields
			update_custom_field_on_contact($latest_charity);
			continue;
		}
		
	} // End of Opportunity Loop

	// echo "status: " . $individual_contact_info->HasOptedOutOfEmail . "<br>";

	if($individual_contact_info->HasOptedOutOfEmail == 1) {
		$email_opt_out = 'true';
	} else {
		$email_opt_out = '';
	}
// echo "email status: " . $email_opt_out . "<br>";


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


}	// End of Contact Loop


?>

