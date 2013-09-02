<?php

    session_start();

    // Kill the session if the last request was over 60*60*3 seconds or 3 hours ago
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 10800)) 
    {
        session_unset();     // unset $_SESSION variable for the run-time 
        session_destroy();   // destroy session data in storage
    }

    $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

    // global configuration file
    require("common.inc");
    require 'lib/klogger/KLogger.php';

    // require the general classes
    require("loader.php");
    require("controller.php");
    require("model.php");

    // require the model classes
    require("models/user.php");
    require("models/item.php");
    require("models/document.php");
    require("models/transaction.php");
    //require("models/picture.php");

    // require the controller classes
    require("controllers/user.php");
    require("controllers/item.php");
    require("controllers/document.php");
    require("controllers/transaction.php");
    //require("controllers/picture.php");
    
    // exception classes
    require("exception.php");
    
    // enums
    require("enum.php");
    
    // validator methods
    require("validator.php");

    $log = KLogger::instance('logs/', KLogger::DEBUG);

    error_log("------------------------------------------------------------");
    error_log("------------------------------------------------------------");
    error_log("post: " . print_r($_POST,true));
    error_log("get: " . print_r($_GET,true));
    error_log("files: " . print_r($_FILES,true));
    error_log("session: " . print_r($_SESSION,true));

    $loader = new Loader($_GET, $_POST, $_FILES, $_SESSION, $log);   

    $controller = $loader->createController();
    $controller->executeAction();

?>

