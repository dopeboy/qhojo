<?php

$dbhost = "localhost";

if ($_SERVER['SERVER_NAME'] == "localhost" || $_SERVER['SERVER_NAME'] == "staging.qhojo.com")
{
    $dbname = "qhojo_staging";
    $demo = true;
    $dbuser = "qhostaging";
    $dbpass = 'Z69afsGKVPHJjsO';
    
    $paypal_environment = "sandbox"; 
    $paypal_username = 'demo_api1.qhojo.com';
    $paypal_password = '1368646290';
    $paypal_signature = 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-A4GQwNItFRcHwhMoa0YSuyJs7BJP'; 
    $paypal_appid = "APP-80W284485P519543T";
    
    $bp_api_key = "3ec0da1cb80e11e2bb37026ba7d31e6f";
    
    $borrower_number = '9493287319';
    $lender_number = '9494852584';    
}

else if ($_SERVER['SERVER_NAME'] == "qhojo.com")
{
    $dbname = "qhojo_production";
    $demo = false;
    $dbuser = "qhoprod";
    $dbpass = 'Gk1BLp5J6u5MsQr';
    
    $paypal_environment = "live";
    $paypal_username = 'manish_api1.qhojo.com';
    $paypal_password = 'B6N846FPXC5PQLT8';
    $paypal_signature = 'AF7jlDuoQteYjEgQ3PedOaXwgaERAMnh-YCySl0ucSxMvw7PXJ9BBfH6';
    $paypal_appid = "APP-9RX52455H6095371W";
    
    $bp_api_key = "1f2306b8be5711e2ac7e026ba7f8ec28";
    
    $borrower_number = '9177466195';
    $lender_number = '3476730097';        
}

$version =  shell_exec('git describe | cut -d \'-\' -f 1-2');

$TwilioAccountSid = "ACe2a25db3d82dc0e3a375905b1cf5c682";
$TwilioAuthToken = "c12a0aa08480a0eb7c21afdb1ce2e197";

function getRandomID()
{
    return rand(100000,999999);
}

function getRandomGUID()
{
    return uniqid();
}

function startsWith($haystack, $needle)
{
    return !strncmp($haystack, $needle, strlen($needle));
}     

$transaction_fee_variable = 0.08;
$transaction_fee_fixed = 0.50;

?>
