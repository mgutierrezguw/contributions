<?php

include('active-api.php');

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	    $contact_id = $_POST['contact']['id'];
		$contact_charity = $_POST['contact']['fields']['charity_names_from_last_n_days'];
		$latest_charity_donated_to = $_POST['contact']['fields']['latest_charity_donated_to'];



			if ($contact_charity == '') {
				$combined_charities = $latest_charity_donated_to;
				echo "combined: " . $combined_charities . "<br>";
				$charity_array = explode(', ', $combined_charities);
				print_r($charity_array);
				$unique_charities = array_unique($charity_array);
				$new_list = implode(', ', $unique_charities);


			} else {
				// $new_value_charity_name = $contact_charity . ' and ' . $latest_charity_donated_to;
				$combined_charities = $contact_charity . ', ' .$latest_charity_donated_to;
				echo "combined: " . $combined_charities . "<br>";
				$simplify_charities = str_replace(' and', ', ', $combined_charities);
				echo "simplify: " . $simplify_charities . "<br>";
				$charity_array = explode(', ', $simplify_charities);
				print_r($charity_array);
				echo "<br>";
				$unique_charities = array_unique($charity_array);
				print_r($unique_charities);
				echo "<br>";
				$new_list = implode(', ', $unique_charities);
				echo "new list" . $new_list . "<br>";
				

			}

		

			if(strpos($new_list, ',') == 0) {
				$new_value_charity_name = substr_replace($new_list, ' ', strrpos($new_list, ','), 1);
			} else {
				$new_value_charity_name = substr_replace($new_list, ' and', strrpos($new_list, ','), 1);
			}



		$data_to_update = [
			"fieldValue" => [
				"contact" => $contact_id,
				"field" => 53,
				"value" => $new_value_charity_name
			]
		];


		
		//functions from active-api.php 
		update_custom_field_on_contact($data_to_update);

		$get_contact = json_decode(get_contacts_fieldValues($contact_id));


		foreach($get_contact as $fields => $value) {
			foreach($value as $val) {
			
				if($val->field == 53) {

					$all_charities = $val->value;
				}
			}
		}


		$charities_array = explode(',', $all_charities);


		if (count($charities_array) > 5) {
			$final_charities = array_slice($charities_array, -5);

			$string_charities = implode(', ', $final_charities);

			$final_data_update = [
				"fieldValue" => [
					"contact" => $contact_id,
					"field" => 53,
					"value" => $string_charities
				]
			];

			update_custom_field_on_contact($final_data_update);
		}
	    
	}







