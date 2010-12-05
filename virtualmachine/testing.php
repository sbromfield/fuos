<?php
require_once("../../config.php");

ini_set('display_errors',1);
//error_reporting(E_ALL);

/**
 * Class autoloading
 */
function __autoload($class_name) {
    if($class_name == "Net_SSH2")
        require_once("classes/ssh/Net/SSH2.php");
    else
        require_once('classes/' . $class_name . '.class.php');
}

/**
 * Require testing library
 */
require_once('tests/simpletest/unit_tester.php');
require_once('tests/simpletest/reporter.php');

/**
 * Create and execute group test
 */
$group = new GroupTest();
$group->_label = "vMoodle Tests";
$group->addTestFile('tests/cloudManager_Test.php');
$group->addTestFile('tests/vMoodle_Controller_Test.php');
$group->run(new HtmlReporter());
