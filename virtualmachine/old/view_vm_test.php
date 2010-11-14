<?php

require_once("../../config.php");
require_once("lib.php");
require_once("classes/vMoodle_Controller.class.php");


ini_set('display_errors',1);
error_reporting(E_ALL);

require_once "classes/viewVM_cmd.class.php";

$obj = new viewVM_cmd();
$obj->execute();

echo "Scope Hit";

?>

