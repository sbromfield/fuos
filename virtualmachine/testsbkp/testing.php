<?php
require_once("../../config.php");
require_once("lib.php");

ini_set('display_errors',1);
error_reporting(E_ALL);

function __autoload($class_name) {
    require_once('classes/' . $class_name . '.class.php');
}
?>
<strong>Suites</strong><br />
<a href="?suite=vMoodle_Controller">vMoodle Controller</a>
 | <a href="?suite=Commands">Commands</a>
<br /><br />
<?php

if(isset($_GET['suite']))
{
   require_once("PHPUnit.php");
   require_once("PHPUnit/GUI/HTML.php");
   
   $suites = array();
   switch($_GET['suite'])
   {
   case "vMoodle_Controller":
      require_once("tests/vMoodle_Controller_Test.php");
      $suites = new PHPUnit_TestSuite('Test_vMoodle_Controller');
      break;
      
   case "Commands";
      require_once("tests/startVM_Test.php");
      $suites = array();
      $suite2 = new PHPUnit_TestSuite('Test_Start_Virtual_Machine');
      break;

    }
    
    echo "<strong>{$_GET['suite']} tests</strong><br />";
    $result = PHPUnit::run($suites);
    echo nl2br($result->toString());

/*
    $gui = new PHPUnit_GUI_HTML($suites);

    $gui->show();
    */
}

