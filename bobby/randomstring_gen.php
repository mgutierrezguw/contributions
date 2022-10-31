<?php

/**
 * Generates a random string from chosen character set
 *
 * @return string
 */
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

/**
 * Generates a random string with an HEX charset, using random_bytes - (cryptographic random bytes that are suitable for cryptographic use)
 *
 * @return string
 */
function quick_hex_random_string(int $length = 1) : string {
	$bytes = random_bytes($length);
	return bin2hex($bytes);
}



/**
 * Generates a random string with an MD5 charset max length of MD5 is 32-characters
 *
 * @return string
 */
function quick_md5_random_string(int $length = 1) : string {
	return substr(str_shuffle(MD5(microtime())), 0, $length);
}
