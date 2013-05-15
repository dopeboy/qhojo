IPN Overview :
------------

* PayPal Instant Payment Notification is call back system that initiated once a tranction is completed  
  (eg: When ExpressCheckout completed successfully).
* you will receive the transaction related ipn variables on your call back url that you have specified in your request.
* You have to send this ipn variable back to PayPal system for varification, Upon varification PayPal will send  
  a response string "VERIFIED" or "INVALID".
* PayPal will continuously resend this ipn, if a wrong ipn is sent.

IPN configuration :
-----------------
* Ipn endpoint url is specified in 'config/sdk_config.ini' as 'service.Endpoint.IPN'. This will be used for ipn post back
    
IPN How to use
--------------
* include 'ipn/PPIPNMessage.php' in your IPN callback URL  
* 'validate()' method validates the IPM message and returns true if 'VERIFIED' or returns false if 'INVALID'  
Ex:

		$ipnMessage = new PPIPNMessage();   
		$result = $ipnMessage->validate();
		  
  Intiating IPN:
* Make an PayPal api call (eg: CreateAccount request), setting the IpnNotificationUrl field of api request   
  class to the url of deployed IPNLIstener sample(eg:https://example.com/adaptivepayments-sdk-php/IPN/IPNListener.php)  
  the IpnNotificationUrl field is in 'CreateAccountRequestDetailsType' class under API request class  
 (ex: 'CreateAccountRequestDetailsType->IpnNotificationUrl')  
* You will receive ipn call back from PayPal , which will be logged in to log file in case of IPN sample.
* see the included sample for more detal
* to access the IPN received use 'getRawData()' which give an array of received IPN variables  
ex:
		
		$ipnMessage->getRawData(); 
	       
IPN variables :
--------------
[Transaction]
-------------
* notify_version
* verify_sign
* charset
* confirmation_code
* event_type
* account_key

[BuyerInfo]
-----------
* first_name
* last_name
	 
*   For a full list of ipn variables you need to check log file, that Ipn Listener is logging into.    

IPN Reference :
--------------
*   You can refer IPN getting started guide at [https://www.x.com/developers/paypal/documentation-tools/ipn/gs_IPN]
