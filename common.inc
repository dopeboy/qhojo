<?php

$dbhost = "localhost";
$dbuser = "qho";
$dbpass = 'admin';

if ($_SERVER['SERVER_NAME'] == "localhost" || $_SERVER['SERVER_NAME'] == "demo.qhojo.com")
    $dbname = "qhojo";
else if ($_SERVER['SERVER_NAME'] == "yc.qhojo.com")
    $dbname = "qhojo2";

$version =  shell_exec('git describe | cut -d \'-\' -f 1-2');

$TwilioAccountSid = "ACe2a25db3d82dc0e3a375905b1cf5c682";
$TwilioAuthToken = "c12a0aa08480a0eb7c21afdb1ce2e197";

$paypal_environment = "sandbox";

$borrower_number = '9493287319';
$lender_number = '9494852584';

function getRandomID()
{
    return rand(100000,999999);
}

$transaction_fee_variable = 0.6;
$transaction_fee_fixed = 0.50;

?>
