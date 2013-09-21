<?php

abstract class Validator
{
    static function isNotNullAndNotEmpty ($parameter_value,$parameter_name, $method) 
    {
        if (is_array($parameter_value) && count($parameter_value) == 0)
            throw new RequiredParameterMissingException($parameter_name, $method, isset($_SESSION["USER"]["USER_ID"]) ? $_SESSION["USER"]["USER_ID"] : 0);    
            
        else if ($parameter_value == null || $parameter_value == '') 
            throw new RequiredParameterMissingException($parameter_name, $method, isset($_SESSION["USER"]["USER_ID"]) ? $_SESSION["USER"]["USER_ID"] : 0);    
    }

    static function isValidZipcode ($parameter_value,$parameter_name, $method) 
    {
        if(strlen($parameter_value) !=5 || !ctype_digit($parameter_value))
            throw new InvalidZipcodeException($method, isset($_SESSION["USER"]["USER_ID"]) ? $_SESSION["USER"]["USER_ID"] : 0);
    }        

    static function isValidDate ($parameter_value,$parameter_name, $method) 
    {
        $format = 'mm/dd/yyyy';

        if(strlen($parameter_value) >= 6 && strlen($format) == 10){ 

            // find separator. Remove all other characters from $format 
            $separator_only = str_replace(array('m','d','y'),'', $format); 
            $separator = $separator_only[0]; // separator is first character 

            if($separator && strlen($separator_only) == 2){ 
                // make regex 
                $regexp = str_replace('mm', '(0?[1-9]|1[0-2])', $format); 
                $regexp = str_replace('dd', '(0?[1-9]|[1-2][0-9]|3[0-1])', $regexp); 
                $regexp = str_replace('yyyy', '(19|20)?[0-9][0-9]', $regexp); 
                $regexp = str_replace($separator, "\\" . $separator, $regexp); 
               
                if($regexp != $parameter_value && preg_match('/'.$regexp.'\z/', $parameter_value)){ 

                    // check date 
                    $arr=explode($separator,$parameter_value); 
                    $day=$arr[1]; 
                    $month=$arr[0]; 
                    $year=$arr[2]; 

                    if(@checkdate($month, $day, $year)) 
                        return $parameter_value; 
                } 
            } 
        } 

        throw new InvalidDateException($method, isset($_SESSION["USER"]["USER_ID"]) ? $_SESSION["USER"]["USER_ID"] : 0);
    }

    static function isValidEmailAddress ($parameter_value,$parameter_name, $method) 
    {
        if(!filter_var($parameter_value, FILTER_VALIDATE_EMAIL) || !preg_match('/@.+\./', $parameter_value))
            throw new InvalidEmailException($method, isset($_SESSION["USER"]["USER_ID"]) ? $_SESSION["USER"]["USER_ID"] : 0);
    }       

    static function isValidName ($parameter_value,$parameter_name, $method) 
    {
        if(preg_match('/[A-Za-z]/', $parameter_value) == 0 || strlen($parameter_value) <= 1 || strlen($parameter_value) >= 15)
            throw new InvalidNameException($parameter_name, $method, isset($_SESSION["USER"]["USER_ID"]) ? $_SESSION["USER"]["USER_ID"] : 0);
    }    
    
    static function isValidPassword ($parameter_value,$parameter_name, $method) 
    {
        if(strlen($parameter_value) < 2 || strlen($parameter_value) >= 20)
            throw new InvalidPassword($method, isset($_SESSION["USER"]["USER_ID"]) ? $_SESSION["USER"]["USER_ID"] : 0);
    }     
    
    static function isValidReviewRating ($parameter_value,$parameter_name, $method) 
    {
        if ($parameter_value != 0 && $parameter_value != 1)
            throw new InvalidReviewRatingException($method, isset($_SESSION["USER"]["USER_ID"]) ? $_SESSION["USER"]["USER_ID"] : 0);
    }        
    
    static function isValidItemRentalRate ($parameter_value,$parameter_name, $method) 
    {
        if (!is_int(intval($parameter_value)) || $parameter_value < 1)
            throw new InvalidItemRentalRateException($method, isset($_SESSION["USER"]["USER_ID"]) ? $_SESSION["USER"]["USER_ID"] : 0);
    }
    
    static function isValidItemHoldValue ($parameter_value,$parameter_name, $method) 
    {
        if (!is_int(intval($parameter_value)) || $parameter_value < 1 || $parameter_value > 2500)
            throw new InvalidItemHoldValueException($method, isset($_SESSION["USER"]["USER_ID"]) ? $_SESSION["USER"]["USER_ID"] : 0);
    }    
    
    static function isValidDamageRating ($parameter_value,$parameter_name, $method) 
    {
        if ($parameter_value != 0 && $parameter_value != 1 && $parameter_value != 2)
            throw new InvalidDamageRatingException($method, isset($_SESSION["USER"]["USER_ID"]) ? $_SESSION["USER"]["USER_ID"] : 0);
    }        
}

?>