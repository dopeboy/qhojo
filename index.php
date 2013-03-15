<?php

session_start();

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

//require the controller classes
require("controllers/user.php");
require("controllers/item.php");
require("controllers/location.php");
require("controllers/document.php");

error_log("------------------------------------------");
error_log("post: " . print_r($_POST,true));
error_log("get: " . print_r($_GET,true));
error_log("files: " . print_r($_FILES,true));
error_log("session: " . print_r($_SESSION,true));

// If the userid is not defined, that means the user is not logged in. If the request is to login, then let it pass through in the second 'if'

// If the userid is defined, this user is logged in. 
//if (!empty($_SESSION) && isset($_SESSION['userid']))
//{
// 	error_log("userid: " .  $_SESSION['userid']);
$loader = new Loader($_GET, $_POST, $_FILES, $_SESSION);   
//}
//
//// Else if the user isn't logged in but a request was made to login, let that pass through
//else if ((strcmp($_GET['controller'],"user") == 0 && strcmp($_GET['action'],"verify") == 0) ||
//         (strcmp($_GET['controller'],"user") == 0 && strcmp($_GET['action'],"signup") == 0) ||
//         (strcmp($_GET['controller'],"user") == 0 && strcmp($_GET['action'],"login") == 0))
//{
//    error_log("Not logged in!!");
//    $loader = new Loader($_GET, $_POST, $_FILES, null);  
//}
//
//else
//{
//        error_log("Not logged in!!");
//	$loader = new Loader(null,null,null, null);
//}

$controller = $loader->createController();
$controller->executeAction();
?>

