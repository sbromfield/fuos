<?php

require_once("../../config.php");
require_once("lib.php");
//require_once("classes/vMoodle_Controller.class.php");

ini_set('display_errors',1);
error_reporting(E_ALL);

function __autoload($class_name) {

    if($class_name == "Net_SSH2")
        require_once("classes/ssh/Net/SSH2.php");
    else
        require_once('classes/' . $class_name . '.class.php');
}

/**
 * vMoodle receives a request from an User. So, the instance of vMoodle 
 * Controller is fetched and used to dispatch the request to the approriate
 * command.
 */
$v = vMoodle_Controller::getInstance();
$v->dispatch();


