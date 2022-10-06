<?php

require "config.php";

if($argv[1] != ""){
    $domain = $argv[1];
} else {
    die;
}

foreach($pageIDs as $id){
    $page = json_decode(getPage($id));
    if($page){
        createPage($domain, $page->title->raw, $page->content->raw);
    }
}

function getPage($id){

    $curl = curl_init();

    curl_setopt_array($curl, [
    CURLOPT_URL => "https://g84cc0.tmtdemo.getuwired.com/wp-json/wp/v2/pages/{$id}/?context=edit",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => [
        'Authorization: Basic ' //Token Goes Here
    ],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);
    $err = curl_error($curl);

    if ($err) {
        file_put_contents("log.txt", "cURL Error #:" . $err, FILE_APPEND | LOCK_EX);
        mail('jmiller@getuwired.com', "cURL Error", "page_sync cURL Error: " . $id);
        return false;
    } else {
        return $response;
    }

}

function createPage($domain, $title, $content){

    $curl = curl_init();

    $data = http_build_query([
        'title' => $title,
        'content' => $content
    ]);

    curl_setopt_array($curl, [
    CURLOPT_URL => "https://{$domain}/wp-json/wp/v2/pages/",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HTTPHEADER => [
        'Authorization: Basic ', //Token Goes Here
        'Content-Type: application/x-www-form-urlencoded'
    ],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);
    $err = curl_error($curl);

    if ($err) {
        file_put_contents("log.txt", "cURL Error #:" . $err, FILE_APPEND | LOCK_EX);
        mail('jmiller@getuwired.com', "cURL Error", "page_sync cURL Error: " . $domain);
        return false;
    } else {
        return $response;
    }
}