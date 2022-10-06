<?php

    /**
     * Used to help format responses given by the send_response function.
     *
     * @author Cody Brant <cbrant@getuwired.com>
     */ 
    abstract class RESPONSE_FORMAT {
        const ARRAY = 0;
        const ARRAY_STRING = 1;
        const ASSOCIATIVE_ARRAY = 2;
        const ASSOCIATIVE_ARRAY_STRING = 3;
        const JSON_STRING = 4;
        const OBJECT = 5;
        const PLAIN_TEXT = 6;
        const URL_PARAMETERS = 7;
    }


    /**
     * Used to determine the type of url encoding on url_encode and url_decode.
     *
     * @author Cody Brant <cbrant@getuwired.com>
     */ 
    abstract class URI_ENCODING {
        const LEGACY = 0;
        const MODERN = 1;
        const RFC_1738 = 2;
        const RFC_3986 = 3;
    }


    /**
     * Used to help with adjusting or formatting the different parts of a date using the
     * date_set, date_adjust, now_adjust, and today_adjust functions.
     *
     * @author Cody Brant <cbrant@getuwired.com>
     */ 
    abstract class DATE_PART {
        const YEAR = "year";
        const MONTH = "month";
        const DAY = "day";
        const HOUR = "hour";
        const MINUTE = "minute";
        const SECOND = "second";
    }


    /**
     * Used to help format dates using the format function.
     *
     * @author Cody Brant <cbrant@getuwired.com>
     */ 
    abstract class DATE_FORMAT {
        const ISO_8601 = "c";
        const ATOM_W3C = "Y-m-d\TH:i:sP";
        const COOKIE = "l, d-M-Y H:i:s e";
        const RSS = "D, d M Y H:i:s O";
        const MYSQL_DEFAULT_COLLATION = "Y-m-dTH:i:s";
        const ORACLE_SQL_DEFAULT_COLLATION = "Y-M-d H:i:s";
        const MS_SQL_DEFAULT_COLLATION = "Y-m-d H:i:s.v";
        const DESCENDING_FULL_12HOUR = "Y-m-d h:i:sA";
        const DESCENDING_FULL_24HOUR = "Y-m-d H:i:s";
        const DESCENDING_DATE = "Y-m-d";
        const MILITARY_TIME = "H:i:s";
        const DESCENDING_TIME_12HOUR = "h:i:sA";
        const MILITARY_DATE_TIME = "m/d/Y H:i:s";
        const AMERICAN_CIVILIAN_FULL_12HOUR = "m/d/Y g:i:sA";
        const AMERICAN_CIVILIAN_DATE = "m/d/Y";
        const AMERICAN_CIVILIAN_WRITTEN_FULL_24HOUR = "F j, Y \a\\t G:i:s";
        const AMERICAN_CIVILIAN_WRITTEN_FULL_12HOUR = "F j, Y \a\\t g:i:sA";
        const AMERICAN_CIVILIAN_WRITTEN_DATE = "F j, Y";
        const INTERNATIONAL_CIVILIAN_FULL_24HOUR = "d.m.Y H:i:s";
        const INTERNATIONAL_CIVILIAN_FULL_12HOUR = "d.m.Y g:i:sA";
        const INTERNATIONAL_CIVILIAN_DATE = "d.m.Y";
        const INTERNATIONAL_CIVILIAN_WRITTEN_FULL_24HOUR = "j F Y G:i:s";
        const INTERNATIONAL_CIVILIAN_WRITTEN_FULL_12HOUR = "j F Y g:i:sA";
        const INTERNATIONAL_CIVILIAN_WRITTEN_DATE = "j F Y";
        const CIVILIAN_TIME_24HOUR = "G:i:s";
        const CIVILIAN_TIME_12HOUR = "g:i:sA";
    }



    /**
     * Turns PHP debugging errors and messages on or off
     *
     * @param bool $on Whether to turn debugging messages on or off
     * 
     * @author Cody Brant <cbrant@getuwired.com>
     */ 
    function debugging($on) {
        if ($on) {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
            ini_set('display_startup_errors', 0);
            error_reporting(E_ALL & ~E_NOTICE);
        }
    }

    

    /**
     * Checks for nullness and returns true if nullness is found.
     * 
     * Nullness includes:
     * * Variable not set
     * * Variable == \0
     * * Variable == null
     * * Variable == ""
     * * Variable is an empty array
     * * Variable is an array with all nullsy values
     *
     * @param mixed $variable The variable to check for nullness
     * 
     * @author Cody Brant <cbrant@getuwired.com>
     * @return bool Returns true if variable is nullsy
     */ 
    function is_nullsy($variable) {
        if (!isset($variable)) return true;
        if ($variable == "\0") return true;
        if ($variable == "") return true;
        if (gettype($variable) == "array") {
            if (count($variable) == 0) return true;
            else {
                foreach ($variable as $key=>$value) {
                    if (isnt_nullsy($value)) return false;
                }
                return true;
            }
        }

        return false;
    }



    /**
     * Checks for nullness and returns true if nullness is not found.
     * 
     * Nullness includes:
     * * Variable not set
     * * Variable == \0
     * * Variable == null
     * * Variable == ""
     * * Variable is an empty array
     * * Variable is an array with all nullsy values
     *
     * @param mixed $variable The variable to check for nullness
     * 
     * @author Cody Brant <cbrant@getuwired.com>
     * @return bool Returns true if variable is not nullsy
     */ 
    function isnt_nullsy($variable) {
        $variableType = gettype($variable);
        if (isset($variable) && $variable != "\0" && $variable != "") {
            if ($variableType == "array") {
                if (count($variable) > 0) {
                    foreach($variable as $key=>$value) {
                        if (isnt_nullsy($value)) return true;
                    }
                    return false;
                }
            }
            if ($variableType != "array") return true;
        }

        return false;
    }



    /**
     * Checks that a variable is either nullsy or some version of false
     * 
     * Falsey includes:
     * * Nullsy (see is_nullsy)
     * * The boolean false
     * * The number 0
     * * The string "false"
     * * The string "0"
     *
     * @param mixed $variable The variable to check for falseness
     * 
     * @author Cody Brant <cbrant@getuwired.com>
     * @return bool Returns true if variable is nullsy or contains a false value
     */ 
    function is_falsey($variable) {
        if (gettype($variable) == "boolean") return ($variable ? false : true);
        else if (is_nullsy($variable)) return true;
        else if ($variable == 0) return true;
        else if ($variable == "false") return true;
        else if ($variable == "0") return true;
        else return false;
    }



    /**
     * Checks that a variable is not nullsy and contains some version of true
     * 
     * Truesy must be all of the following:
     * * NOT Nullsy (see is_nullsy)
     * * Is the number 1, is a string "1", is a string "true", or is a boolean true
     *
     * @param mixed $variable The variable to check for truthfulness
     * 
     * @author Cody Brant <cbrant@getuwired.com>
     * @return bool Returns true if variable is not nullsy and contains a true value
     */ 
    function is_truesy($variable) {
        if (gettype($variable) == "boolean") return ($variable ? true : false);
        if (isnt_nullsy($variable) && ($variable == 1 || $variable == "1" || $variable == "true")) return true;
        else return false;
    }



    /**
     * Checks that a variable contains a number
     *
     * @param mixed $variable The variable to check for numbers
     * 
     * @author Cody Brant <cbrant@getuwired.com>
     * @return bool Returns true if variable is an integer, double, float or a string that contains only a numerical value
     */ 
    function is_number($variable) {
        $variableType = gettype($variable);
        if ($variableType == "integer" || $variableType == "double") return true;
        else if ($variableType == "string") return (ctype_digit(str_replace("-", "", str_replace(".", "", $variable))) && substr_count($variable, ".") <= 1 && ((substr_count($variable, "-") == 1 && str_starts_with($variable, "-")) || substr_count($variable, "-") == 0));
        else return false;
    }



    /**
     * Forces a variable to become a double or integer (check with is_number if using on strings)
     *
     * @param mixed $variable The variable to force into an integer or double
     * 
     * @author Cody Brant <cbrant@getuwired.com>
     * @return boolean Returns the integer or double on success, errors if unsuccessful or unknown variable type
     */ 
    function force_number($variable) {
        $variableType = gettype($variable);
        if ($variableType == "boolean") return ($variable ? 1 : 0);
        else if ($variableType == "string" && strpos($variable, ".") == false) return (int) filter_var($variable, FILTER_SANITIZE_NUMBER_INT);
        else if ($variableType == "string") return (double) floatval(str_replace(",", "", $variable));
        else if ($variableType == "integer" || $variableType == "double") return $variable;
        else {
            echo "function force_number errored with:\n\n<br /><br />" . $variable . "\n<br />" . $variableType;
            exit;
        }
    }



    /**
     * Forces a variable to become a string
     *
     * @param mixed $variable The variable to force into a string (objects are jsonified)
     * 
     * @author Cody Brant <cbrant@getuwired.com>
     * @return bool Returns the string on success, errors if unsuccessful or unknown variable type
     */ 
    function force_str($variable) {
        $variableType = gettype($variable);
        if ($variableType == "boolean") return ($variable ? "true" : "false");
        else if ($variableType == "string") return $variable;
        else if ($variableType == "integer" || $variableType == "double") return strval($variable);
        else if ($variableType == "object" || $variableType == "array") return jsonify($variable);
        else if ($variableType == "NULL") return "null";
        else {
            echo "function force_number errored with:\n\n<br /><br />" . $variable . "\n<br />" . $variableType;
            exit;
        }
    }



    /**
     * A singular format function for all common data types. Takes an integer, double, 
     * boolean, string or date variable, and returns its value formatted as given by 
     * the format string.
     * 
     * For integer and double values, use the following formatting rules:
     * * Use a # for an optional number, 0 for a required number, and . for decimal
     * * The first char in the format string, if not one of the above, is a left leaning padding
     * * The last char in the format string, if not one of the above, is a right leaning padding
     * * The first char found that is not any of the above becomes the thousandths delimiter
     * * Example #1: format(100000.567, "#,##0.00") ==> "100,000.57"
     * * Example #2: format(100000.567, "000.0") ==> "100000.6"
     * * Example #3: format(100000.567, "&nbsp;&nbsp;&nbsp;##,##0.0") ==> "&nbsp;&nbsp;100,000.6"
     * * Example #4: format(0.567, ".00&nbsp;") ==> ".57&nbsp;"
     * * Example #5: format(0.567, "0.00####&nbsp;&nbsp;") ==> "0.567&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
     * 
     * For boolean values, use the following formatting rules:
     * * Consists of two phrases, delimited by a comma
     * * Example: format(boolean, "this is a true value,this is a false value")
     * 
     * For string values, use the following formatting rules:
     * * Use % to represent a string or char, all other chars in the format string are treated as literals
     * * If a single % is given in the format string, then the entire string value will be inserted
     * * Example #1: format("14838970134", "+% (%%%) %%%-%%%%") ==> "+1 (483) 897-0134"
     * * Example #2: format("Cody", "Hello %!") ==> "Hello Cody!"
     * 
     * For DateTimes and DateTimeImmutables, use the following formatting rules:
     * * Use a formatting string such as "m/d/Y H:i:s" or one of the DATE_FORMAT enumerations
     *
     * @param mixed $value The variable whose value should be formatted
     * @param mixed $formatString The string representation of the format to be applied
     * 
     * @author Cody Brant <cbrant@getuwired.com>
     * @return string Returns the formatted string on success, errors if unsuccessful or unknown variable type
     */ 
    function format($value, $formatString) {
        $valueType = gettype($value);
        $formatStringLength = strlen(force_str($formatString));
        $returnString = "";

        switch ($valueType) {
            // decimal format, # = optional number, 0 = required number, . = decimal
            // first char, if not one of the above, is a left leaning padding
            // last char, if not one of the above, and if no left leaning is found, is a right leaning padding
            // first char found that is not the first or last char, is the thousandths delimeter
            // examples: format(100000.567, "#,##0.00") ==> "100,000.57"
            // examples: format(100000.567, "000.0") ==> "100000.6"
            // examples: format(100000.567, "   ##,##0.0") ==> "  100,000.6"
            // examples: format(0.567, ".00 ") ==> ".57 "
            // examples: format(0.567, "0.00####  ") ==> "0.567     " 
            case "integer":
                $leftLeaningPadding = null;
                $rightLeaningPadding = null;
                $thousandthsDelimiter = "";
                $numberOfDecimals = 0;

                if (!str_starts_with($formatString, "#") && !str_starts_with($formatString, "0") && !str_starts_with($formatString, ".")) {
                    // get padding character
                    $leftLeaningPadding = substr($formatString, 0, 1);
                }

                // keep searching through string til a thousandths delimeter is found
                for ($i = 0; $i < $formatStringLength; $i++) {
                    $char = substr($formatString, $i, 1);
                    if ($char != "0" && $char != "#" && $char != "." && ($leftLeaningPadding == null || $char != $leftLeaningPadding)) {
                        $thousandthsDelimiter = $char;
                        break;
                    }
                }

                if ($leftLeaningPadding == null && !str_ends_with($formatString, "#") && !str_ends_with($formatString, "0") && !str_ends_with($formatString, ".")) {
                    // get padding character
                    $rightLeaningPadding = substr($formatString, -1, 1);
                }

                if (str_contains($formatString, ".")) {
                    $numberOfDecimals = (strrpos($formatString, "#", strrpos($formatString, ".")) != false ? strrpos($formatString, "#", strrpos($formatString, ".")) : (strrpos($formatString, "0", strrpos($formatString, ".")) ? strrpos($formatString, "0", strrpos($formatString, ".")) : strlen($formatString) - 1)) - strrpos($formatString, ".");
                }

                // build string in requested format
                $returnString = number_format($value, $numberOfDecimals, ".", $thousandthsDelimiter);

                // remove leading 0 if format starts with "."
                if (str_starts_with($returnString, "0") && strpos(substr($formatString, 0, strrpos($formatString, ".")), "0") == false) {
                    $returnString = substr($returnString, strpos($returnString, "."));
                }

                // remove 0 value decimal points if listed as optional (#)
                if (str_contains($formatString, ".") && strrpos($formatString, "0", strrpos($formatString, ".")) != false) {
                    $lastRequiredDecimal = $formatStringLength - strrpos($formatString, "0", strrpos($formatString, "."));
                    //echo $lastRequiredDecimal . " ";
                } else if (str_contains($formatString, ".")) {
                    $lastRequiredDecimal = $formatStringLength - strrpos($formatString, ".") + 1;
                }

                if ($rightLeaningPadding != null) {
                    $lastRequiredDecimal -= $formatStringLength - strpos($formatString, $rightLeaningPadding) - 1;
                    //echo $rightLeaningPadding . " ";
                }
                
                //echo $lastRequiredDecimal . " ";

                $allZeroes = true;

                for ($i = 1; $i < $lastRequiredDecimal; $i++) {
                    //echo substr($returnString, -1*$i, 1);
                    if (substr($returnString, -1*$i, 1) != "0") {
                        $returnString = substr($returnString, 0, strlen($returnString) - (1*$i - 1));
                        $i = $lastRequiredDecimal;
                        $allZeroes = false;
                    }
                }

                if ($allZeroes) {
                    //echo "all zeroes! ";
                    $returnString = substr($returnString, 0, strlen($returnString) - (1*$lastRequiredDecimal) + 1 + ($rightLeaningPadding != null ? 1 : 0));
                }
                
                if (str_ends_with($returnString, ".")) $returnString = substr($returnString, 0, -1);

                // build left leaning padding
                if ($leftLeaningPadding != null) {
                    $returnString = str_pad($returnString, $formatStringLength, $leftLeaningPadding, STR_PAD_LEFT);
                }

                // build right leaning padding
                if ($rightLeaningPadding != null) {
                    $returnString = str_pad($returnString, $formatStringLength, $rightLeaningPadding, STR_PAD_RIGHT);
                }

                return $returnString;
                break;

            case "double":
                $leftLeaningPadding = null;
                $rightLeaningPadding = null;
                $thousandthsDelimiter = "";
                $numberOfDecimals = 0;

                if (!str_starts_with($formatString, "#") && !str_starts_with($formatString, "0") && !str_starts_with($formatString, ".")) {
                    // get padding character
                    $leftLeaningPadding = substr($formatString, 0, 1);
                }

                // keep searching through string til a thousandths delimeter is found
                for ($i = 0; $i < $formatStringLength; $i++) {
                    $char = substr($formatString, $i, 1);
                    if ($char != "0" && $char != "#" && $char != "." && ($leftLeaningPadding == null || $char != $leftLeaningPadding)) {
                        $thousandthsDelimiter = $char;
                        break;
                    }
                }

                if ($leftLeaningPadding == null && !str_ends_with($formatString, "#") && !str_ends_with($formatString, "0") && !str_ends_with($formatString, ".")) {
                    // get padding character
                    $rightLeaningPadding = substr($formatString, -1, 1);
                }

                if (str_contains($formatString, ".")) {
                    $numberOfDecimals = (strrpos($formatString, "#", strrpos($formatString, ".")) != false ? strrpos($formatString, "#", strrpos($formatString, ".")) : (strrpos($formatString, "0", strrpos($formatString, ".")) ? strrpos($formatString, "0", strrpos($formatString, ".")) : strlen($formatString) - 1)) - strrpos($formatString, ".");
                }

                // build string in requested format
                $returnString = number_format($value, $numberOfDecimals, ".", $thousandthsDelimiter);

                // remove leading 0 if format starts with "."
                if (str_starts_with($returnString, "0") && strpos(substr($formatString, 0, strrpos($formatString, ".")), "0") == false) {
                    $returnString = substr($returnString, strpos($returnString, "."));
                }

                // remove 0 value decimal points if listed as optional (#)
                if (str_contains($formatString, ".") && strrpos($formatString, "0", strrpos($formatString, ".")) != false) {
                    $lastRequiredDecimal = $formatStringLength - strrpos($formatString, "0", strrpos($formatString, "."));
                    //echo $lastRequiredDecimal . " ";
                } else if (str_contains($formatString, ".")) {
                    $lastRequiredDecimal = $formatStringLength - strrpos($formatString, ".") + 1;
                }

                if ($rightLeaningPadding != null) {
                    $lastRequiredDecimal -= $formatStringLength - strpos($formatString, $rightLeaningPadding) - 1;
                    //echo $rightLeaningPadding . " ";
                }
                
                //echo $lastRequiredDecimal . " ";

                $allZeroes = true;

                for ($i = 1; $i < $lastRequiredDecimal; $i++) {
                    //echo substr($returnString, -1*$i, 1);
                    if (substr($returnString, -1*$i, 1) != "0") {
                        $returnString = substr($returnString, 0, strlen($returnString) - (1*$i - 1));
                        $i = $lastRequiredDecimal;
                        $allZeroes = false;
                    }
                }

                if ($allZeroes) {
                    //echo "all zeroes! ";
                    $returnString = substr($returnString, 0, strlen($returnString) - (1*$lastRequiredDecimal) + 1 + ($rightLeaningPadding != null ? 1 : 0));
                }
                
                if (str_ends_with($returnString, ".")) $returnString = substr($returnString, 0, -1);

                // build left leaning padding
                if ($leftLeaningPadding != null) {
                    $returnString = str_pad($returnString, $formatStringLength, $leftLeaningPadding, STR_PAD_LEFT);
                }

                // build right leaning padding
                if ($rightLeaningPadding != null) {
                    $returnString = str_pad($returnString, $formatStringLength, $rightLeaningPadding, STR_PAD_RIGHT);
                }

                return $returnString;
                break;

            // boolean format, two phrases delimited by a comma
            // example: format(boolean, "this is a true value,this is a false value")
            case "boolean":
                return ($value ? substr($formatString, 0, strpos($formatString, ",")) : substr($formatString, strpos($formatString, ",") + 1));
                break;

            // string format, % = char, all other chars are treated as literals
            // if a single % is given in the format, then the entire string value will be inserted
            // examples: format("14838970134", "+% (%%%) %%%-%%%%") ==> "+1 (483) 897-0134"
            // examples: format("Cody", "Hello %!") ==> "Hello Cody!"
            case "string":
                $simple = (substr_count($formatString,"%") == 1 ? true : false);
                $stringArray = str_split($value);
                $formatArray = str_split($formatString);
                $j = 0;

                if ($simple) {
                    return substr($formatString, 0, strpos($formatString, "%")) . $value . substr($formatString, strpos($formatString, "%")+1);
                    break;
                } else {
                    foreach ($formatArray as $i=>$char) {
                        if ($formatArray[$i] == '%') {
                            $formatArray[$i] = $stringArray[$j];
                            $j++;
                        }
                    }

                    return implode("", $formatArray);
                    break;
                }

            default:
                if (get_class($value) == "DateTimeImmutable" || get_class($value) == "DateTime") { 
                    if (gettype($formatString) == "object") $formatString = $formatString->value;
                    return $value->format($formatString);
                }
            
        }
    }



    /**
     * Expanded url encode functionality. Uses arrays in addition to urlencode to offer
     * more types of url encoding. If passed a string, will return a url encoded version of 
     * the string. If passed an array, will return the array with all strings inside the 
     * array changed to a url encoded version of their prior value. If passed an object, 
     * will return the object with all strings inside the object changed to a url encoded 
     * version of their prior value.
     *
     * @param mixed $object A string, array or object.
     * @param object $type An encoding type. Use the URI_ENCODE enumeration.
     * 
     * @author Cody Brant <cbrant@getuwired.com>
     * @return mixed Returns the string, array or object with all textual data points url encoded
     */
    function url_encode($object, $type = URI_ENCODING::MODERN) {
        $replacements = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%23', '%5B', '%5D');
        $entities = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "#", "[", "]");

        $objectIsArray = (gettype($object) == "array");
        $objectIsObject = (gettype($object) == "object");

        if ($objectIsArray) {
            foreach ($object as $key=>$value) {
                $object[$key] = url_encode($value);
            }

            return $object;
        }

        if ($objectIsObject) {
            foreach ($object as $key=>$value) {
                $object[$key] = url_encode($value);
            }

            return $object;
        }

        if (!$objectIsObject && !$objectIsArray) {
            switch($type) {
                case (URI_ENCODING::MODERN): 
                    return str_replace($entities, $replacements, urlencode($object));
                    break;

                case (URI_ENCODING::LEGACY): 
                    return urlencode($object);
                    break;

                case (URI_ENCODING::RFC_1738): 
                    return urlencode($object);
                    break;

                case (URI_ENCODING::RFC_3986): 
                    return str_replace($entities, $replacements, urlencode($object));
                    break;
            }
        }
    }



    /**
     * Expanded url decode functionality. Uses arrays in addition to urldecode to offer
     * more types of url decoding. If passed a string, will return a url decoded version of 
     * the string. If passed an array, will return the array with all strings inside the 
     * array changed to a url decoded version of their prior value. If passed an object, 
     * will return the object with all strings inside the object changed to a url decoded 
     * version of their prior value.
     *
     * @param mixed $object A string, array or object.
     * @param object $type An decoding type. Use the URI_ENCODE enumeration.
     * 
     * @author Cody Brant <cbrant@getuwired.com>
     * @return mixed Returns the string, array or object with all textual data points url decoded
     */
    function url_decode($object, $type = URI_ENCODING::MODERN) {
        $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
        $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");

        $objectIsArray = (gettype($object) == "array");
        $objectIsObject = (gettype($object) == "object");

        if ($objectIsArray) {
            foreach ($object as $key=>$value) {
                $object[$key] = url_decode($value);
            }

            return $object;
        }

        if ($objectIsObject) {
            foreach ($object as $key=>$value) {
                $object[$key] = url_decode($value);
            }

            return $object;
        }

        if (!$objectIsObject && !$objectIsArray) {
            switch($type) {
                case (URI_ENCODING::MODERN): 
                    return str_replace($entities, $replacements, urldecode(strval($object)));
                    break;

                case (URI_ENCODING::LEGACY): 
                    return urldecode(strval($object));
                    break;

                case (URI_ENCODING::RFC_1738): 
                    return urldecode(strval($object));
                    break;

                case (URI_ENCODING::RFC_3986): 
                    return str_replace($entities, $replacements, urldecode(strval($object)));
                    break;
            }
        }
    }



    /**
     * Returns a given variable as a string of url parameters for use in POST bodies or 
     * for appending to the end of a GET url. If passed a single data point, a parameter
     * name can (and should) also be passed. Else, if passed an array, each element of the 
     * array is appended in a "key=value&" pair. If an object is passed, it will be 
     * jsonified and then url_encoded. A parameter name should also be given when passing 
     * a PHP object. The end result is a string which can be appended to a url endpoint. 
     * The leading question mark to denote the start of a url query is not included in the 
     * returned string.
     *
     * @param mixed $resource A boolean, integer, double, string, object or array.
     * @param string $parameterName An optional name for the data point. Only used when 
     * not passing an array. Defaults to "parameter."
     * 
     * @author Cody Brant <cbrant@getuwired.com>
     * @return mixed Returns a string in the url query string format without the leading 
     * question mark.
     */
    function urlify($resource, $parameterName = "parameter") {
        $resourceType = gettype($resource);
        $resourceIsDataPoint = ($resourceType == "boolean" || $resourceType == "integer" || $resourceType == "double" || $resourceType == "string" || $resourceType == "NULL");
        $resourceIsObject = ($resourceType == "object");
        $resourceIsArray = ($resourceType == "array");
        $parameterName = url_encode($parameterName);
        $urlString = "";

        if ($resourceIsDataPoint) {
            if ($resourceType == "boolean") $urlString = $parameterName . "=" . ($resource ? "true" : "");
            else if ($resourceType == "integer") $urlString = $parameterName . "=" . $resource;
            else if ($resourceType == "double") $urlString = $parameterName . "=" . $resource;
            else if ($resourceType == "string") $urlString = $parameterName . "=" . url_encode($resource);
            else if ($resourceType == "NULL") $urlString = $parameterName . "=";
        } else if ($resourceIsArray) {
            $keys = array_keys($resource);
            for($i=0; $i<count($resource); $i++) {
                $urlString .= url_encode($keys[$i]) . "=" . (gettype($resource[$keys[$i]]) == "array" || gettype($resource[$keys[$i]]) == "object" ? jsonify($resource[$keys[$i]],0) : url_encode($resource[$keys[$i]])) . "&";
            }
            $urlString = substr($urlString,0,-1);
        } else if ($resourceIsObject) {
            $urlString = $parameterName . "=" . url_encode(jsonify($resource),0);
        } else {
            echo "function urlify errored with:\n\n<br /><br />{resource} = " . $resource . "\n<br />{resourceType} = " . $resourceType;
            exit;
        }

        return $urlString;
    }



    /**
     * Returns a given variable as a PHP object. If the resource given is a singular data 
     * point, the returned object will only have a single member variable given the 
     * name of the type of value it is (eg: obj->boolean, obj->double, etc) or the variable 
     * name specified.
     * 
     * If the variable is an array, the function will by default take the key=>value pairs 
     * from the array and turn them into member variables (eg: obj->key = value). However, 
     * if the flattenIfArray flag is set to false, an object with a single member variable 
     * of "array" or the variable name specified is set equal to the array given.
     * 
     * If the variable is an object, it is simply returned unchanged.
     * 
     * If the variable is a string and isn't a json string, then it is treated as any other 
     * singular data point. If the string is a json string, it is json decoded into a PHP
     * object. The behavior of the json decoding is affected by the isJsonArray flag, which 
     * acts identically to json_decode's associative flag. 
     *
     * @param mixed $resource A boolean, integer, double, string, object or array.
     * @param string $variableName An optional name for the data point. Only used when 
     * not passing an array, object or json string. Defaults to the resource's data type.
     * @param bool $flattenIfArray Determines how to handle arrays.
     * @param string $isJsonArray Determines if json strings are decoded as associative or not.
     * 
     * @author Cody Brant <cbrant@getuwired.com>
     * @return object An object made from an array or json string, or an object containing 
     * the data point passed.
     */
    function objectify($resource, $variableName = null, $flattenIfArray = true, $isJsonArray = false) {
        $resourceType = gettype($resource);
        if ($variableName == null) $variableName = $resourceType;
        $resourceIsDataPoint = ($resourceType == "boolean" || $resourceType == "integer" || $resourceType == "double" || $resourceType == "string" || $resourceType == "NULL");
        $resourceIsObject = ($resourceType == "object");
        $resourceIsArray = ($resourceType == "array");
        $genericObject = new stdClass();

        if ($resourceIsDataPoint) {
            if ($resourceType == "boolean") $genericObject->$variableName = ($resource ? 1 : 0);
            else if ($resourceType == "integer") $genericObject->$variableName = $resource;
            else if ($resourceType == "double") $genericObject->$variableName = $resource;
            else if ($resourceType == "string") {
                $temp = json_decode($resource, $isJsonArray);
                if ($temp == NULL) {
                    $genericObject->$variableName = $resource;
                } else {
                    $genericObject = $temp;
                }
            } else if ($resourceType == "NULL") $genericObject->value = $resource;
        } else if ($resourceIsArray) {
            if ($flattenIfArray) {
                $keys = array_keys($resource);
                $genericObject = (array)$genericObject;
                for($i=0; $i<count($resource); $i++) {
                    echo $resource[$i];
                    $genericObject[$keys[$i]] = $resource[$keys[$i]];
                }
                $genericObject = (object)$genericObject;
            } else {
                $genericObject->$variableName = $resource;
            }
        } else if ($resourceIsObject) {
            $genericObject = $resource;
        } else {
            echo "function objectify errored with:\n\n<br /><br />{resource} = " . $resource . "\n<br />{resourceType} = " . $resourceType;
            exit;
        }

        return $genericObject;
    }



    /**
     * Returns a resource as a json string. If the resource is not an object or array, it 
     * is objectified first. By default, all the HEX flags are used when json encoding.
     *
     * @param mixed $resource The variable to force into a json string (non-objects are
     * objectified first).
     * @param object $flags The json encoding flags to use. By default, uses all HEX flags.
     * 
     * @author Cody Brant <cbrant@getuwired.com>
     * @return bool Returns the resource as a json string
     */ 
    function jsonify($resource, $flags = JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) {
        $resourceType = gettype($resource);
        if ($resourceType != "array" && $resourceType != "object") $resource = objectify($resource);
        return json_encode($resource, $flags);
    }



    /**
     * Returns a DateTimeImmutable object with the current timestamp
     * 
     * @author Cody Brant <cbrant@getuwired.com>
     * @return DateTimeImmutable The current time
     */
    function now() {
        return new DateTimeImmutable("now");
    }



    /**
     * Returns a DateTimeImmutable object with the current timestamp, adjusted by some amount
     * 
     * @param mixed $amount The amount to add. Accepts integers, doubles, or strings with 
     * valid integer or double values. Also accepts values allowed by DateTime->modify 
     * member function, like the strings "+1" or "-1"
     * @param mixed $interval The interval to increase by a given amount. Accepts enum 
     * values from DATE_PART.
     *
     * @author Cody Brant <cbrant@getuwired.com>
     * @return DateTimeImmutable The current time, adjusted by some amount.
     */
    function now_adjust($amount, $interval) {
        if (gettype($interval) == "object") $interval = $interval->value;
        $datetime = now();
        return date_adjust($datetime, $amount, $interval);
    }



    /**
     * Returns a DateTimeImmutable object with the current date and time set at 00:00:00
     *
     * @author Cody Brant <cbrant@getuwired.com>
     * @return DateTimeImmutable The current date with time set at 00:00:00
     */
    function today() {
        $datetime = new DateTimeImmutable("now");
        $datetime = date_set($datetime, 0, DATE_PART::HOUR);
        $datetime = date_set($datetime, 0, DATE_PART::MINUTE);
        return date_set($datetime, 0, DATE_PART::SECOND);
    }



    /**
     * Returns a DateTimeImmutable object with the current date and time set at 00:00:00, adjusted by some amount
     * 
     * @param mixed $amount The amount to add. Accepts integers, doubles, or strings with 
     * valid integer or double values. Also accepts values allowed by DateTime->modify 
     * member function, like the strings "+1" or "-1"
     * @param mixed $interval The interval to increase by a given amount. Accepts enum 
     * values from DATE_PART.
     *
     * @author Cody Brant <cbrant@getuwired.com>
     * @return DateTimeImmutable The current date with time set at 00:00:00, adjusted by some amount.
     */
    function today_adjust($amount, $interval) {
        if (gettype($interval) == "object") $interval = $interval->value;
        $datetime = today();
        return date_adjust($datetime, $amount, $interval);
    }



    /**
     * Returns a DateTimeImmutable object with yesterday's date and time set at 00:00:00
     *
     * @author Cody Brant <cbrant@getuwired.com>
     * @return DateTimeImmutable Yesterday's date with time set at 00:00:00
     */
    function yesterday() {
        $datetime = new DateTimeImmutable("now");
        $datetime = $datetime->modify('-1 day');
        $datetime = date_set($datetime, 0, DATE_PART::HOUR);
        $datetime = date_set($datetime, 0, DATE_PART::MINUTE);
        return date_set($datetime, 0, DATE_PART::SECOND);
    }



    /**
     * Returns a DateTimeImmutable object with tomorrow's date and time set at 00:00:00
     *
     * @author Cody Brant <cbrant@getuwired.com>
     * @return DateTimeImmutable Tomorrow's date with time set at 00:00:00
     */
    function tomorrow() {
        $datetime = new DateTimeImmutable("now");
        $datetime = $datetime->modify('+1 day');
        $datetime = date_set($datetime, 0, DATE_PART::HOUR);
        $datetime = date_set($datetime, 0, DATE_PART::MINUTE);
        return date_set($datetime, 0, DATE_PART::SECOND);
    }



    /**
     * Attempts to return a DateTimeImmutable object parsed from the given string, where 
     * date format is unknown.
     * 
     * Important Notice: This function assumes that dates, months, hours, minutes 
     * and seconds are all 2-digit groupings and year is a 4-digit grouping. Words and 
     * textual representations are generally unsupported. This function is designed for 
     * parsing when delimiters and date part order is unknown. More complex or textual 
     * formats will likely result in error or an erroneous return value.
     * 
     * @param string $string The string to try and parse a date from.
     * @param bool $preferInternationalFormats Whether to prefer international date 
     * formats like d.m.Y over North American ones like m/d/Y in cases where the date parts 
     * cannot be determined (eg: day is less than or equal to 12, which could also be a 
     * month).
     *
     * @author Cody Brant <cbrant@getuwired.com>
     * @return DateTimeImmutable The current date with time set at 00:00:00, adjusted by some amount.
     */
    function date_create_from_string($string, $preferInternationalFormats = true) {
        $acceptableBreakers = ["/", ".", "-", " ", ":"];
        $year = 0;
        $month = 0;
        $day = 0;
        $hour = 0;
        $minute = 0;
        $second = 0;
        $temp1 = 0;
        $temp2 = 0;
        $returnDate = null;
        $stringArray = str_split($string);
        foreach ($stringArray as $digit=>$char) {
            if ($digit == 2) {
                if (in_array($char, $acceptableBreakers)) {
                    // first grouping of letters is day or month
                    $temp1 = force_number(substr($string, 0, 2));
                }
            }

            if ($digit == 5) {
                if (in_array($char, $acceptableBreakers)) {
                    // second grouping of letters is day or month
                    $temp2 = force_number(substr($string, 3, 2));
                }

                if ($temp1 > 12 && $temp2 <= 12) {
                    $day = $temp1;
                    $month = $temp2;
                } else if ($temp1 <= 12 && $temp2 > 12) {
                    $day = $temp2;
                    $month = $temp1;
                } else if ($preferInternationalFormats) {
                    $day = $temp1;
                    $month = $temp2;
                } else {
                    $day = $temp2;
                    $month = $temp1;
                }
            }

            if ($digit == 4) {
                if (in_array($char, $acceptableBreakers)) {
                    // first grouping of letters is year
                    $year = force_number(substr($string, 0, 4));
                }
            }

            if ($digit == 7) {
                if (in_array($char, $acceptableBreakers)) {
                    // second grouping of letters is month
                    $month = force_number(substr($string, 5, 2));
                }
            }

            if (($digit == 10 && in_array($char, $acceptableBreakers)) || ($digit == 9 && $digit+1 == count($stringArray))) {
                // third grouping of letters is year if we have month and day,
                // or day if we have month and year
                if ($month > 0 && $day > 0) {
                    $year = force_number(substr($string, 6, 4));
                } else if ($month > 0 && $year > 0) {
                    $day = force_number(substr($string, 8, 2));
                }
            }

            if ($digit == 13) {
                if (in_array($char, $acceptableBreakers)) {
                    // fourth grouping of letters is hour
                    $hour = force_number(substr($string, 11, 2));
                }
            }

            if ($digit == 16) {
                if (in_array($char, $acceptableBreakers)) {
                    // fifth grouping of letters is minute
                    $minute = force_number(substr($string, 14, 2));
                }
            }

            if ($digit == 17 && $digit+2 == strlen($string)) {
                // sixth and final grouping of letters is second
                $second = force_number(substr($string, 17, 2));
            }
        }

        $returnDate = now();
        $returnDate = date_set($returnDate, $year, DATE_PART::YEAR);
        $returnDate = date_set($returnDate, $month, DATE_PART::MONTH);
        $returnDate = date_set($returnDate, $day, DATE_PART::DAY);
        $returnDate = date_set($returnDate, $hour, DATE_PART::HOUR);
        $returnDate = date_set($returnDate, $minute, DATE_PART::MINUTE);
        return date_set($returnDate, $second, DATE_PART::SECOND);
    }



    /**
     * Returns a DateTimeImmutable object with the given DateTime's timestamp, with a given
     * interval / date part set to a new given value.
     * 
     * @param mixed $datetime The DateTime or DateTimeImmutable to get a timestamp from.
     * @param mixed $value The value to set the interval / date part to. Accepts integers, 
     * doubles and strings with valid integer or double values.
     * @param mixed $interval The interval to set to the given value. Accepts enum 
     * values from DATE_PART.
     *
     * @author Cody Brant <cbrant@getuwired.com>
     * @return DateTimeImmutable The given DateTime's timestamp, with the given 
     * interval / date part set to the new given value.
     */
    function date_set($datetime, $value, $interval) {
        if (gettype($interval) == "object") $interval = $interval->value;
        $interval = strtolower($interval);
        $y = force_number($datetime->format('Y'));
        $m = force_number($datetime->format('m'));
        $d = force_number($datetime->format('d'));
        $h = force_number($datetime->format('H'));
        $i = force_number($datetime->format('i'));
        $s = force_number($datetime->format('s'));
        $newDatetime = $datetime->setDate((str_starts_with($interval, "year") ? force_number($value) : $y), (str_starts_with($interval, "month") ? force_number($value) : $m), (str_starts_with($interval, "day") ? force_number($value) : $d));
        $newDatetime = $newDatetime->setTime((str_starts_with($interval, "hour") ? force_number($value) : $h), (str_starts_with($interval, "minute") ? force_number($value) : $i), (str_starts_with($interval, "second") ? force_number($value) : $s));

        return $newDatetime;
    }



    /**
     * Returns a DateTimeImmutable object with the given DateTime's timestamp, adjusted by 
     * some amount
     * 
     * @param mixed $datetime The DateTime or DateTimeImmutable to get a timestamp from.
     * @param mixed $amount The amount to add. Accepts integers, doubles, or strings with 
     * valid integer or double values. Also accepts values allowed by DateTime->modify 
     * member function, like the strings "+1" or "-1"
     * @param mixed $interval The interval to set to the given value. Accepts enum 
     * values from DATE_PART.
     *
     * @author Cody Brant <cbrant@getuwired.com>
     * @return DateTimeImmutable The given DateTime's timestamp, adjusted by some amount.
     */
    function date_adjust($datetime, $amount, $interval) {
        if (gettype($interval) == "object") $interval = $interval->value;
        $interval = strtolower($interval);
        if (str_starts_with(strval($amount), "+") || str_starts_with(strval($amount), "-")) {
            $newDatetime = $datetime->modify($amount . " " . $interval . (str_ends_with($interval, "s") ? "" : "s"));
        } else {
            $y = force_number($datetime->format('Y'));
            $m = force_number($datetime->format('m'));
            $d = force_number($datetime->format('d'));
            $h = force_number($datetime->format('H'));
            $i = force_number($datetime->format('i'));
            $s = force_number($datetime->format('s'));
            $newDatetime = $datetime->setDate((str_starts_with($interval, "year") ? $y + force_number($amount) : $y), (str_starts_with($interval, "month") ? $m + force_number($amount) : $m), (str_starts_with($interval, "day") ? $d + force_number($amount) : $d));
            $newDatetime = $newDatetime->setTime((str_starts_with($interval, "hour") ? $h + force_number($amount) : $h), (str_starts_with($interval, "minute") ? $i + force_number($amount) : $i), (str_starts_with($interval, "second") ? $s + force_number($amount) : $s));
        }

        return $newDatetime;
    }


    
    /**
     * Creates a request of a specified type with specified content for the body or for appending 
     * to the url. Content type defaults to the typical urlencoded format, but can be 
     * changed. Can optionally be returned a handle for the curl call, but returns the 
     * request's response by default.
     * 
     * @param string $requestType The type of request. Should usually be "GET" or "POST"
     * @param string $location The endpoint / url to send the request to
     * @param string $content The content to put into the body or append to the url. Note 
     * that when a GET request is made and the content is appended to the url, the leading ? 
     * is automatically added to the end of the url, before content is appended
     * @param string $authorization Any authorization line that should be put in the header. 
     * If this is null, it is simply not appended to the header. If included, please pass 
     * the entire authorization line (eg: "Authorization: Bearer api_key")
     * @param string $contentType The Content-Type to put into the header of the request. 
     * Defaults to "application/x-www-form-urlencoded"
     * @param bool $returnHandle Whether to return the handle for the curl call, or the 
     * request response received after the request is made. Default is to return the response. 
     * If set to true, the curl call is not executed, so that you can further customize the 
     * call and execute it yourself using the returned curl call handle.
     *
     * @author Cody Brant <cbrant@getuwired.com>
     * @return mixed Either the handle for the curl call, or the response to the request
     */
    function make_request($requestType, $location, $content, $authorization = null, $contentType = "application/x-www-form-urlencoded", $returnHandle = false) {
        // instantiate temporary variables
        $curl = curl_init();
        $request = strtoupper($requestType);
        $header = [
            "Accept: */*", 
            "Content-Type: " . $contentType
        ];

        if (isnt_nullsy($authorization)) {
            array_push($header, $authorization);
        }

        //set curl options
        curl_setopt($curl, CURLOPT_URL, $location . ($request == "GET" ? "?" . $content : ""));
        
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($curl, CURLOPT_TIMEOUT, 120);

        if ($requestType != "GET") {
            if ($requestType == "POST") curl_setopt($curl, CURLOPT_POST, true);
            else curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $requestType);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        }

        curl_setopt($curl, CURLOPT_HEADEROPT, CURLHEADER_UNIFIED);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        if ($returnHandle) return $curl;

        // make request
        $response = curl_exec($curl);

        // post errors
        if (curl_error($curl)) {
            echo curl_error($curl);
        }
        
        curl_close($curl);

        return $response;
    }


    
    /**
     * Takes a response and echoes it out, attempting to format the response according to 
     * the specified format.
     * 
     * Formatting works as follows:
     * 
     * * ARRAY: Should be used for debugging. Uses print_r to print an actual array object
     * to the screen. Note that PHP uses an iterative integer as a key, whereas the array
     * string option below does not operate on key=>value pairs.
     * 
     * * ARRAY_STRING: Creates an array from values in reponse. If response is a singular 
     * data point, it is simply put into an array. If a response is already an array, it 
     * does not modify it. If response is an object, it is jsonified, then put into a
     * array. The array is then printed out as a string in the format:
     * [value, value, value ...]
     * 
     * * ASSOCIATIVE_ARRAY: Should be used for debugging. Uses print_r to print an actual
     * array object to the screen.
     * 
     * * ASSOCIATIVE_ARRAY_STRING: Creates an array from values in reponse. If response is 
     * a singular data point, it is simply put into an array with a key of "parameter." If a 
     * response is already an array, it does not modify it. If response is an object, it is
     * jsonified, then put into an array and given a key of "parameter" like other data 
     * points. The array is then printed out as a string in the format:
     * ["key"=>value, "key"=>value ...]
     * 
     * * JSON_STRING: A jsonified version of the response is created.
     * 
     * * OBJECT: Should be used for debugging. The response is objectified, and then printed 
     * to the screen with print_r.
     * 
     * * PLAIN_TEXT: The given response is styled into a text string that attempts to 
     * be as close as possible to a PHP textual representation of the response. Booleans, 
     * integers, doubles, and strings are run through strval. Objects are jsonified. Arrays are
     * shown in the same way they would be for ASSOCIATIVE_ARRAY_STRING.
     * 
     * * URL_PARAMETERS: The response is urlified and echoed.
     * 
     * @param mixed $response Some type of object, array, or data point to echo as a response.
     * @param object $format an enumerated value from RESPONSE_FORMAT.
     *
     * @author Cody Brant <cbrant@getuwired.com>
     */
    function send_response($response, $format = RESPONSE_FORMAT::PLAIN_TEXT) {
        $responseType = gettype($response);
        $responseIsBoolean = ($responseType == "boolean");
        $responseIsDataPoint = ($responseType == "boolean" || $responseType == "integer" || $responseType == "double" || $responseType == "string" || $responseType == "NULL");
        $responseIsObject = ($responseType == "object");
        $responseIsArray = ($responseType == "array");
        $responseText = "";

        switch($format) {

            case (RESPONSE_FORMAT::OBJECT):
                $responseText = print_r(objectify($response));
                break;

            case (RESPONSE_FORMAT::JSON_STRING):
                $responseText = jsonify($response);
                break;

            case (RESPONSE_FORMAT::URL_PARAMETERS):
                $responseText = urlify($response);
                break;

            case (RESPONSE_FORMAT::ARRAY):
                if ($responseIsDataPoint) {
                    $responseText = print_r([($responseIsBoolean ? ($response ? 1 : 0) : $response)]);
                } else if ($responseIsObject) {
                    $responseText = print_r([$response]);
                } else if ($responseIsArray) {
                    $responseText = print_r($response);
                }
                break;

            case (RESPONSE_FORMAT::ARRAY_STRING):
                if ($responseIsDataPoint) {
                    $responseText = "[" . ($responseIsBoolean ? ($response ? 1 : 0) : ($responseType == "string" ? "\"" . $response . "\"" : $response)) . "]";
                } else if ($responseIsObject) {
                    $responseText = "[" . jsonify($response) . "]";
                } else if ($responseIsArray) {
                    $keys = array_keys($response);
                    $responseText = "[";
                    for($i=0; $i<count($response); $i++) {
                        $responseText .= (gettype($response[$keys[$i]]) == "string" ? "\"" : "") . (gettype($response[$keys[$i]]) == "boolean" ? ($response[$keys[$i]] ? 1 : 0) : (gettype($response[$keys[$i]]) != "array" ? $response[$keys[$i]] : jsonify($response[$keys[$i]],0))) . (gettype($response[$keys[$i]]) == "string" ? "\"" : "") . ", ";
                    }
                    $responseText = substr($responseText, 0, -2) . "]";
                }
                break;

            case (RESPONSE_FORMAT::ASSOCIATIVE_ARRAY):
                if ($responseIsDataPoint) {
                    $responseText = print_r(["parameter" => ($responseIsBoolean ? ($response ? 1 : 0) : $response)]);
                } else if ($responseIsObject) {
                    $responseText = print_r(["parameter" => $response]);
                } else if ($responseIsArray) {
                    $responseText = print_r($response);
                }
                break;

            case (RESPONSE_FORMAT::ASSOCIATIVE_ARRAY_STRING):
                if ($responseIsDataPoint) {
                    $responseText = "[\"parameter\"=>" . ($responseIsBoolean ? ($response ? 1 : 0) : ($responseType == "string" ? "\"" . $response . "\"" : $response)) . "]";
                } else if ($responseIsObject) {
                    $responseText = "[\"parameter\"=>" . jsonify($response) . "]";
                } else if ($responseIsArray) {
                    $keys = array_keys($response);
                    $responseText = "[";
                    for($i=0; $i<count($response); $i++) {
                        $responseText .= "\"" . $keys[$i] . "\"=>" . (gettype($response[$keys[$i]]) == "string" ? "\"" : "") . (gettype($response[$keys[$i]]) == "boolean" ? ($response[$keys[$i]] ? 1 : 0) : (gettype($response[$keys[$i]]) != "array" ? $response[$keys[$i]] : jsonify($response[$keys[$i]],0))) . (gettype($response[$keys[$i]]) == "string" ? "\"" : "") . ", ";
                    }
                    $responseText = substr($responseText, 0, -2) . "]";
                }
                break;

            case (RESPONSE_FORMAT::PLAIN_TEXT):
                if ($responseIsDataPoint) $responseText = strval(($responseIsBoolean ? ($response ? 1 : 0) : $response));
                else if ($responseIsObject) jsonify($response);
                else if ($responseIsArray) {
                    $keys = array_keys($response);
                    $responseText = "[";
                    for($i=0; $i<count($response); $i++) {
                        $responseText .= "\"" . $keys[$i] . "\"=>" . (gettype($response[$keys[$i]]) == "string" ? "\"" : "") . (gettype($response[$keys[$i]]) == "boolean" ? ($response[$keys[$i]] ? 1 : 0) : (gettype($response[$keys[$i]]) != "array" ? $response[$keys[$i]] : jsonify($response[$keys[$i]],0))) . (gettype($response[$keys[$i]]) == "string" ? "\"" : "") . ", ";
                    }
                    $responseText = substr($responseText, 0, -2) . "]";
                }
                break;
       }

       echo $responseText;
    }



    /**
     * Opens a sql connection and then returns the connection object.
     * 
     * @param string $servername The name or IP address of the server MySQL is on
     * @param string $username The name of the user on the MySQL server to log in as 
     * @param string $password The password for the MySQL server user
     * @param string $database The database on the MySQL server to login to initially
     * @param integer $port The port number to access the MySQL server database by (Default: 3306)
     *
     * @author Cody Brant <cbrant@getuwired.com>
     * @return mysqli The connection object to pass to other functions or use member functions
     * of
     */
    function sql_connect($servername, $username, $password, $database, $port = 3306) {
        $connection = new mysqli($servername, $username, $password, $database, $port);

        if ($connection->connect_error) {
            echo "function sql_query errored with:\n\n<br /><br />" . $connection->connect_error;
            exit;
        }

        return $connection;
    }

    

    /**
     * Allows for quick and safe querying of a MySQL Database via parameterized prepared 
     * SQL queries which would return at least one result set. 
     * 
     * Parameters can be null if none are required, can be a one dimensional 
     * array when only one statement should be run, or can be a two dimensional array when 
     * multiple similar statements need to be run.
     * 
     * Statements should be parameterized and parameters should be passed as the third 
     * argument in order to protect against SQL injection attacks.
     * 
     * The returned object will be a two or three dimensional array depending on the number 
     * of times the statement ran, based upon the object passed in the parameters. If it is 
     * a two dimensional array, then it is a single result set where the first array is the 
     * result set row, and the second is the particular field to be accessed. The field can 
     * be accessed by number (eg: results[1][2]) or accessed by key, which will be the 
     * column name (eg: results[1]["FirstName"]). If the query is run multiple times, then 
     * the returned object is a three dimensional array, where the first array is the query 
     * number. The next two arrays act identically to a single result set array (eg: 
     * results[3][1]["FirstName"] would be the third query ran, the second row, value in 
     * field "FirstName").
     * 
     * @param mysqli $connection The connection object to run the query against
     * @param string $sql The parameterized sql statement
     * @param mixed $parameters An array of parameters or an array of parameter arrays. Null 
     * can be passed if the statement does not require any parameters.
     *
     * @author Cody Brant <cbrant@getuwired.com>
     * @return mixed A two dimensional array of results or a three dimensional array of results 
     * for each query ran
     */
    function sql_query($connection, $sql, $parameters = null) {
        $multipleQueries = false;
        try {
            if (isset($parameters) && count($parameters) > 0) {
                // create variables for later
                $parameterString = "";
                $bindArguments = [];

                //if this is a 2d array
                if (gettype($parameters[0]) == "array") {
                    $multipleQueries = true;

                    for ($i = 0; $i < count($parameters); $i++) {
                        array_push($bindArguments, []);

                        // create list of param types
                        foreach ($parameters[$i] as $parameter) {
                            $parameterType = gettype($parameter);
                            if ($parameterType == "integer") $parameterString .= "i";
                            else if ($parameterType == "double") $parameterString .= "d";
                            else $parameterString .= "s";  
                            array_push($bindArguments[$i], $parameter);
                        }

                        array_unshift($bindArguments[$i], $parameterString);
                        $parameterString = "";
                    }
                } else {

                    // create list of param types
                    foreach ($parameters as $parameter) {
                        $parameterType = gettype($parameter);
                        if ($parameterType == "integer") $parameterString .= "i";
                        else if ($parameterType == "double") $parameterString .= "d";
                        else $parameterString .= "s";  
                        array_push($bindArguments, $parameter);
                    }

                    array_unshift($bindArguments, $parameterString);
                }
            }
                
            // create and execute statement
            $statement = $connection->prepare($sql);

            if ($multipleQueries) {
                $returnArray = [];

                for ($i = 0; $i < count($parameters); $i++) {
                    if (isset($parameters[$i]) && count($parameters[$i]) > 0) call_user_func_array(array($statement, 'bind_param'), $bindArguments[$i]);
                    $statement->execute();

                    // create return array
                    $results = $statement->get_result();
                    array_push($returnArray, []);
            
                    if ($results->num_rows > 0) {
                        while($row = $results->fetch_assoc()) {
                            $rowArray = [];
                            foreach ($row as $field=>$value) {
                                $rowArray[$field] = $value;
                            }
                            array_push($returnArray[$i], $rowArray);
                        }
                    } else {
                        while($row = $results->fetch_assoc()) {
                            $rowArray = [];
                            foreach ($row as $field=>$value) {
                                $rowArray[$field] = "N/A";
                            }
                            array_push($returnArray[$i], $rowArray);
                        }
                    }
                }
            } else {
                if (isset($parameters) && count($parameters) > 0) call_user_func_array(array($statement, 'bind_param'), $bindArguments);
                $statement->execute();

                // create return array
                $results = $statement->get_result();
        
                $returnArray = [];
        
                if ($results->num_rows > 0) {
                    while($row = $results->fetch_assoc()) {
                        $rowArray = [];
                        foreach ($row as $field=>$value) {
                            $rowArray[$field] = $value;
                        }
                        array_push($returnArray, $rowArray);
                    }
                }
            }
    
            $statement->close();
            return $returnArray;
        } catch(Exception $e) {
            echo "function sql_query errored with:\n\n<br /><br />" . $e->getMessage();
            if (isnt_nullsy($statement)) $statement->close();
            exit;
        }
    }



    /**
     * Allows for quick and safe execution of SQL commands which would not return a 
     * result set against a MySQL server. 
     * 
     * Parameters can be null if none are required, can be a one dimensional 
     * array when only one statement should be run, or can be a two dimensional array when 
     * multiple similar statements need to be run.
     * 
     * Statements should be parameterized and parameters should be passed as the third 
     * argument in order to protect against SQL injection attacks.
     * 
     * Unlike sql_query, no results are returned, thus saving performance if a result 
     * set is not required.
     * 
     * @param mysqli $connection The connection object to run the query against
     * @param string $sql The parameterized sql statement
     * @param mixed $parameters An array of parameters or an array of parameter arrays. Null 
     * can be passed if the statement does not require any parameters.
     *
     * @author Cody Brant <cbrant@getuwired.com>
     */
    function sql_command($connection, $sql, $parameters = null) {
        $multipleQueries = false;
        try {
            if (isset($parameters) && count($parameters) > 0) {
                // create variables for later
                $parameterString = "";
                $bindArguments = [];

                //if this is a 2d array
                if (gettype($parameters[0]) == "array") {
                    $multipleQueries = true;

                    for ($i = 0; $i < count($parameters); $i++) {
                        array_push($bindArguments, []);

                        // create list of param types
                        foreach ($parameters[$i] as $parameter) {
                            $parameterType = gettype($parameter);
                            if ($parameterType == "integer") $parameterString .= "i";
                            else if ($parameterType == "double") $parameterString .= "d";
                            else $parameterString .= "s";  
                            array_push($bindArguments[$i], $parameter);
                        }

                        array_unshift($bindArguments[$i], $parameterString);
                        $parameterString = "";
                    }
                } else {

                    // create list of param types
                    foreach ($parameters as $parameter) {
                        $parameterType = gettype($parameter);
                        if ($parameterType == "integer") $parameterString .= "i";
                        else if ($parameterType == "double") $parameterString .= "d";
                        else $parameterString .= "s";  
                        array_push($bindArguments, $parameter);
                    }

                    array_unshift($bindArguments, $parameterString);
                }
            }
                
            // create and execute statement
            $statement = $connection->prepare($sql);

            if ($multipleQueries) {
                $returnArray = [];

                for ($i = 0; $i < count($parameters); $i++) {
                    if (isset($parameters[$i]) && count($parameters[$i]) > 0) call_user_func_array(array($statement, 'bind_param'), $bindArguments[$i]);
                    $statement->execute();
                }
            } else {
                if (isset($parameters) && count($parameters) > 0) call_user_func_array(array($statement, 'bind_param'), $bindArguments);
                $statement->execute();
            }
    
            $statement->close();
        } catch(Exception $e) {
            echo "function sql_query errored with:\n\n<br /><br />" . $e->getMessage();
            if (isnt_nullsy($statement)) $statement->close();
            exit;
        }
    }



    /**
     * Closes a sql connection via a mysqli object.
     * 
     * @param mysqli $connection The connection to close
     *
     * @author Cody Brant <cbrant@getuwired.com>
     * of
     */
    function sql_close($connection) {
        $connection->close();
    }

?>