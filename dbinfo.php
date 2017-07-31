<?php
// Database settings
// database hostname or IP. default:localhost
// localhost will be correct for 99% of times
define("HOST", "127.0.0.1");
// Database user
define("DBUSER", "root");
// Database password
define("PASS", "");
// Database name
define("DB", "project");
 
global $link;
$link = mysqli_connect(HOST, DBUSER, PASS,DB) or  die('Could not connect !<br />Please contact the site\'s administrator.');
 

 
?>