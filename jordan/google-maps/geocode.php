<?php
function geocodeLatLong($lat, $long, $apiKey)
{
    $url = "https://maps.googleapis.com/maps/api/geocode/json?&location_type=ROOFTOP&latlng=$lat,$long&key=$apiKey";
    $address = file_get_contents($url);
    $address = json_decode($address, true);
    $formattedAddress = $address['results'][0]['formatted_address'];
    if ($address['status'] == 'OK') {
        return $address['results'][0]['formatted_address'];
    }
    return false;
}

function geocodeAddress($address, $apiKey)
{
    $address = urlencode($address);
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=$apiKey";
    $coords = file_get_contents($url);
    $coords = json_decode($coords, true);
    if ($coords['status'] == 'OK') {
        return $coords['results'][0]['geometry']['location'];
    }
    return false;
}
