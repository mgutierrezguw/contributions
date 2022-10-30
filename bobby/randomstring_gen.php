<?php

function gen_random_string(int $length = 1, int $mode = 1) : string {
    
    //Write down all the chars
    $char_set = [
        'abcdefghijklmnopqrstuvwxyz',
        'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        '01234567890',
        '~!@#$%^&*()_+-=',
        ' <>?/{}[]|,.'
        ];
    
    //Put the chars in the bag    
    $char_set_str = implode( array_slice($char_set, 0, $mode ) );
    
    //Shake the bag
    $char_set_str = str_shuffle($char_set_str);

    $random_string = '';

    //Pick the winners
    for( $i = 0; $i < $length; $i++)
    {
        $random_string .= $char_set_str[random_int(0, strlen($char_set_str) - 1)];
    }

    
        
	return  $random_string;

}


/*
* @returns random hex string
* Can be used to generate a quick string when the HEX charset is enough
*/
function gen_random_hex(int $length = 1) : string {
	$bytes = random_bytes($length);
	return bin2hex($bytes);
}

