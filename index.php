<?php

session_start();

// Kill the session if the last request was over 30 minutes ago
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) 
{
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp


if ($_COOKIE['iwashere'] != "yes") 
{ 
    //setcookie("iwashere", "yes", time()+315360000);  
    //header("Location: http://example.com/index-first-time-visitor.php"); 
}


// global configuration file
require("common.inc");

//require the general classes
require("loader.php");
require("controller.php");
require("model.php");

//require the model classes
require("models/user.php");
require("models/item.php");
require("models/location.php");
require("models/document.php");
require("models/picture.php");

//require the controller classes
require("controllers/user.php");
require("controllers/item.php");
require("controllers/location.php");
require("controllers/document.php");
require("controllers/picture.php");

error_log("------------------------------------------");
error_log("post: " . print_r($_POST,true));
error_log("get: " . print_r($_GET,true));
error_log("files: " . print_r($_FILES,true));
error_log("session: " . print_r($_SESSION,true));

$loader = new Loader($_GET, $_POST, $_FILES, $_SESSION);   

$controller = $loader->createController();
$controller->executeAction();

?>

