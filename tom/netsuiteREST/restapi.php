<?php


define('OAUTH_CONSUMER_KEY','YOUR_OAUTH_CONSUMER_KEY');
define('OAUTH_CONSUMER_SECRET','YOUR_OAUTH_CONSUMER_SECRET');
define('OAUTH_TOKEN','YOUR_OAUTH_TOKEN');
define('OAUTH_TOKEN_SECRET','YOUR_OAUTH_TOKEN_SECRET');
define('NS_ACCOUNT','YOUR_NS_ACCOUNT');

define('NS_BASE_URL','https://YOUR_ACCOUNT_ID_HERE.suitetalk.api.netsuite.com/services/rest/record/v1');


class NetSuite
{

    public function __construct()
    {
        $this->baseUrl = NS_BASE_URL;
        $this->signatureMethod = 'HMAC-SHA256';
        $this->version = '1.0';
        $this->account = NS_ACCOUNT;

        $this->consumerKey = OAUTH_CONSUMER_KEY;
        $this->tokenId = OAUTH_TOKEN;
        $this->consumerSecret = OAUTH_CONSUMER_SECRET;
        $this->tokenSecret = OAUTH_TOKEN_SECRET;
    }

    public function makeRequest($httpMethod, $path, $params_data = array(), $query_data = array() ){

        $loopCount = 0;

        do {

            $temp_query_data = array();

            $query_string = ( !empty($query_data) ) ? '?' . raw_url_encode_build_query($query_data) : '';

            $nonce = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 20);

            $timestamp = time();

            $oauth_array = array(
                'oauth_consumer_key' => $this->consumerKey,
                'oauth_nonce' => $nonce,
                'oauth_signature_method' => $this->signatureMethod,
                'oauth_timestamp' => $timestamp,
                'oauth_token' => $this->tokenId,
                'oauth_version' => $this->version
            );

            $temp_query_data = array_merge($query_data, $oauth_array);

            ksort($temp_query_data);

            $base_query_string = raw_url_encode_build_query($temp_query_data);

            $baseString = $httpMethod . '&' . rawurlencode($this->baseUrl . $path) . "&"
            . rawurlencode( $base_query_string );

            $key = rawurlencode($this->consumerSecret) . '&' . rawurlencode($this->tokenSecret);

            $signature = base64_encode(hash_hmac('sha256', $baseString, $key, true));

            $header = array(
                "Authorization: OAuth
                realm=\"$this->account\",oauth_consumer_key=\"$this->consumerKey\",oauth_token=\"$this->tokenId\",oauth_signature_method=\"HMAC-SHA256\",oauth_timestamp=\"$timestamp\",oauth_nonce=\"$nonce\",oauth_version=\"$this->version\",oauth_signature=\"$signature\"",
                "Cookie: NS_ROUTING_VERSION=LAGGING",
                'Content-Type: application/json; charset=utf-8'
            );

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->baseUrl . $path . $query_string,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $httpMethod,
                CURLOPT_HTTPHEADER => $header,
            ));

            if(!empty($params_data)){
                $json_string = json_encode($params_data);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $json_string);
            }

            $response = curl_exec($curl);

            curl_close($curl);

            $response = json_decode($response, true);

            $loopCount++;

        } while ( ( isset( $response['status'] ) && $response['status'] == '401' ) && $loopCount < 50 );

        return $response;

    }

    public function getCustomers( $query_data = array() ){

        $endpoint = "/customer";

        $response = $this->makeRequest('GET', $endpoint, array(), $query_data);

        return $response;

    }

    public function getCustomer($id){

        $endpoint = "/customer/{$id}";

        $response = $this->makeRequest('GET', $endpoint);

        return $response;

    }

    public function getCustomerIdByEmail($email){

        $query_data = array(
            'q' => 'email CONTAIN "'.$email.'"',
            'limit' => 1,
        );
        
        $response = $this->getCustomers($query_data);

        $customer_id = ( isset($response['items'][0]['id']) ) ? $response['items'][0]['id'] : 0;

        return $customer_id;
    }

    public function createCustomer( $params_data ){
        $endpoint = "/customer";

        $response = $this->makeRequest('POST', $endpoint, $params_data );

        return $response; 
    }


    public function updateCustomer( $id, $params_data ) {
        $endpoint = "/customer/{$id}";

        $response = $this->makeRequest('PATCH', $endpoint, $params_data);

        return $response;
    }

    public function getEmployeeIdByEmail($email){

        $query_data = array(
            'q' => 'email CONTAIN "'.$email.'"',
            'limit' => 1,
        );

        $endpoint = "/employee";

        $response = $this->makeRequest('GET', $endpoint, array(), $query_data);

        $employee_id = ( isset($response['items'][0]['id']) ) ? $response['items'][0]['id'] : 0;

        return $employee_id;
    }

    public function getEmployee($id = ''){

        $endpoint = "/employee/{$id}";

        $response = $this->makeRequest('GET', $endpoint);

        return $response;

    }

    public function getSalesOrder($id = ''){

        $endpoint = "/salesOrder/{$id}";

        $response = $this->makeRequest('GET', $endpoint);

        return $response;
    }

    public function getSalesOrderItems($id){

        $endpoint = "/salesOrder/{$id}/item";

        $response = $this->makeRequest('GET', $endpoint);

        return $response;
    }

    public function getSalesOrderItem($id, $item_num){

        $endpoint = "/salesOrder/{$id}/item/{$item_num}";

        $response = $this->makeRequest('GET', $endpoint);

        return $response;

    }

    public function getInventoryItem($id){

        $endpoint = "/inventoryItem/{$id}";

        $response = $this->makeRequest('GET', $endpoint);

        return $response;

    }

    public function getInventoryItems( $query_data = array() ){

        $endpoint = "/inventoryItem";

        $response = $this->makeRequest('GET', $endpoint, array(), $query_data);

        return $response;
    }

    public function getNonInventorySaleItems( $query_data = array() ){

        $endpoint = "/nonInventorySaleItem";

        $response = $this->makeRequest('GET', $endpoint, array(), $query_data);

        return $response;
    }

    public function getNonInventorySaleItem( $id ){

        $endpoint = "/nonInventorySaleItem/{$id}";

        $response = $this->makeRequest('GET', $endpoint);

        return $response;
    }

    

    public function getOtherChargePurchaseItem( $query_data = array() ){

        $endpoint = "/otherChargePurchaseItem";

        $response = $this->makeRequest('GET', $endpoint, array(), $query_data);

        return $response;

    }

    public function getOtherChargeResaleItem( $query_data = array() ){
        
        $endpoint = "/otherChargeResaleItem";

        $response = $this->makeRequest('GET', $endpoint, array(), $query_data);

        return $response;

    }

    public function getOtherChargeSaleItem( $query_data = array() ){

        $endpoint = "/otherChargeSaleItem";

        $response = $this->makeRequest('GET', $endpoint, array(), $query_data);

        return $response;

    }

    public function getSalesRole($id = ''){

        $endpoint = "/salesRole/{$id}";

        $response = $this->makeRequest('GET', $endpoint);

        return $response;

    }

    public function getSalesOrderTeam( $id, $team_id = '' ){

        $endpoint = "/salesOrder/{$id}/salesTeam/{$team_id}";

        $response = $this->makeRequest('GET', $endpoint);

        return $response;

    }

    public function updateSalesOrder($id, $data){


        $endpoint = "/salesOrder/{$id}";

        $response = $this->makeRequest('PATCH', $endpoint, $data);

        return $response;

    }

    public function createSalesOrder($data){

        $endpoint = "/salesOrder";

        $response = $this->makeRequest('POST', $endpoint, $data);

        return $response;

    }

    public function upSertSalesOrder($id, $params_data, $query_data = array() ){

        $endpoint = "/salesOrder/eid:Infusionsoft-default-{$id}";

        $response = $this->makeRequest('PUT', $endpoint, $params_data, $query_data);

        return $response;

    }

    public function updateSalesOrderTeam($id, $data, $query_data){


        $endpoint = "/salesOrder/{$id}/salesTeam";

        $response = $this->makeRequest('PATCH', $endpoint, $data, $query_data);

        return $response;

    }

}

function get_employees_array(){
    $codestring = file_get_contents('employees.json');

    $codeArray = json_decode($codestring, true);

    return $codeArray;
}

function url_build_query($query_data){
    $string = '';
    $count = 0;
    foreach ($query_data as $key => $value) {
        $has_amp = ($count > 0 ) ? '&' : '';
        $string .= $has_amp . $key . '=' . $value;
        $count++;
    }
    return $string;
}

function raw_url_encode_build_query($query_data){
    $string = '';
    $count = 0;
    foreach ($query_data as $key => $value) {
        $has_amp = ($count > 0 ) ? '&' : '';
        $string .= $has_amp . $key . '=' . rawurlencode($value);
        $count++;
    }
    return $string;
}