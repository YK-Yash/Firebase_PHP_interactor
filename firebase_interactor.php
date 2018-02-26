<?php
require_once 'firebaseInterface.php';
require_once 'firebaseLib.php';
require_once 'firebaseTest.php';
//$arduino_data = $_GET['arduino_data'];
$fb = new FirebaseTest();
$fb->setUp();
$fb->testSet();
//$response = $fb->push($firebasePath, $arduino_data);
sleep(2);
?>