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

