<?php

$dbhost = "localhost";

if ($_SERVER['SERVER_NAME'] == "localhost" || $_SERVER['SERVER_NAME'] == "dev.qhojo.com" || $_SERVER['SERVER_NAME'] == "staging.qhojo.com")
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
    $bp_mp_uri = '/v1/marketplaces/TEST-MP1UEXukTLr6ID7auHkkCHd6';
        
    $borrower_number = '9493287319';
    $lender_number = '9494852584';    
    $domain = "http://" . $_SERVER['SERVER_NAME'];
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
    $bp_mp_uri = '/v1/marketplaces/MPX4F8Wx05ETX8BWY4aC00U';
    
    $borrower_number = '9177466195';
    $lender_number = '3476730097';    
    $domain = "https://" . $_SERVER['SERVER_NAME'];
}

$linkedInAPIKey = "s2f2hwywms37";
$linkedInSecret = "pV5m8gFUQsVDIGAu";

$TwilioAccountSid = "ACe2a25db3d82dc0e3a375905b1cf5c682";
$TwilioAuthToken = "c12a0aa08480a0eb7c21afdb1ce2e197";

$temp_dir = "/tmp";

$item_picture_path = "/uploads/item/";

$product_picture_path = "/uploads/product/";
$temp_product_picture_path = $temp_dir . $product_picture_path;

$user_picture_path = "/uploads/user/";

$transaction_picture_path = "/uploads/transaction/";
$transaction_delete_subdir = "/damage";

$item_thumb_subdir = '/thumbnail';
$user_thumb_subdir = '/thumbnail';

$item_card_subdir = '/card';
$user_card_subdir = '/card';

$stock_user_big = '/img/stock_big.png';
$stock_user_tn = '/img/stock_tn.png';
$stock_user_card = '/img/stock_card.png';

$results_per_page = 12;

$support_email = "support@qhojo.com";
$email_message_footer = "-Team Qhojo<br/><a href=\"mailto:{$support_email}\">{$support_email}</a><br/><a href=\"{$domain}\">{$domain}</a>";
$do_not_reply_email = "donotreply@qhojo.com";

$transaction_fee_variable = 0.1;
$transaction_fee_fixed = 0.00;
$maximum_rental_duration_days = 7;

function getRandomID()
{
    return rand(100000,999999);
}

function startsWith($haystack, $needle)
{
    return !strncmp($haystack, $needle, strlen($needle));
}     

function sendEmail($from, $to, $replyto, $subject, $message)
{
    global $email_message_footer;
    
    
    $headers = 'From: ' . $from . "\r\n" .
               'Reply-To: ' . $replyto . "\r\n" .
               'Content-Type: text/html; charset=UTF-8' . "\r\n" .
                'Content-Transfer-Encoding: base64' . "\r\n" . 
               'X-Mailer: PHP/' . phpversion();     

    $message = "<html><body>" . $message . "<br/><br/>" . $email_message_footer . "</body></html>";
    $message = chunk_split(base64_encode($message));
    return mail($to, $subject, $message, $headers);            
}

?>
