<?php 

/*
Authentication for Activecampaign Api Connection
*/

define("ACTIVECAMPAIGN_URL", "https://YOURAPPID.api-us1.com/api/3/");
define("ACTIVECAMPAIGN_API_KEY", "YOURAUTHAPIKEY");

class ActiveAPI {

    public static function ac_api_call($end_point, $method = "GET", $params_data = array(), $limit = 100 ) {

        $url = ACTIVECAMPAIGN_URL . $end_point . '?limit=' . $limit;

        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array(
                'Api-Token: ' . ACTIVECAMPAIGN_API_KEY
            ),
        ));

        if(!empty($params_data)){
            $json_string = json_encode($params_data);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json_string);
        }


        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response);
    }

    public static function get_contact_by_id($contact_id){
        $response = self::ac_api_call("contacts/{$contact_id}");
        return $response;
    }

    public static function add_update_contact($contact_data = array()){
        $data = [
            'contact' => $contact_data
        ];
        $response = self::ac_api_call("contact/sync", "POST", $data);
        return $response;
    }

    public static function apply_tag($contact_id, $tag_id){
        $data = [
            "contactTag" => [
                "contact" => "{$contact_id}",
                "tag" => "{$tag_id}" 
            ]
        ];

        $response = self::ac_api_call("contactTags", "POST", $data);
        return $response;
    }

    public static function start_automation($contact_id, $automation){
        $data = [
            "contactAutomation" => [
                "contact" => "{$contact_id}",
                "automation" => "{$automation}"
            ]
        ];

        $response = self::ac_api_call("contactTags", "POST", $data);
        return $response;
    }

    public static function get_custom_field($id){
        $response = self::ac_api_call("fields/{$id}");
        return $response; 
    }

    public static function list_custom_fields(){
        $response = self::ac_api_call("fields");
        return $response; 
    }


    public static function list_all_tags(){
        $response = self::ac_api_call("tags");
        return $response; 
    }



}

function build_field_values( $field_values = array() ){

    $return_array = array();

    if(!empty($field_values) ){
        foreach ($field_values as $key => $value) {
            $return_array[] =[
                "field" => $key,
                "value" => $value
            ];
        }
    }
    return $return_array;
}