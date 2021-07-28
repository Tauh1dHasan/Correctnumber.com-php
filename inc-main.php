<?php 
mb_http_input("utf-8");
mb_http_output("utf-8");
?>
<?php 
///////SettingsLock/////////////////
$SettingsLocked="true"; // true or false
//If about setting is set to false, System Settings General and Tax Settings can be changed by administrator user.
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
//Time Zone Settings
//Get List of Time Zones from http://php.net/manual/en/timezones.php
date_default_timezone_set("Asia/Colombo");

//error_reporting(0);

//DEFINE COMMON VARIABLES

 ?>
