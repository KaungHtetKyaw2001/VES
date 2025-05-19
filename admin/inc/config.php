<?php
// Error Reporting Turn On
ini_set('error_reporting', E_ALL);

// Setting up the time zone
date_default_timezone_set('Asia/Yangon');

// Host Name
$dbhost = 'giggagates.com:3306';

// Database Name
$dbname = 'dreamexpress_ves';

// Database Username
$dbuser = 'dreamexpress_LinnHtetAung';

// Database Password
$dbpass = 'lha317999kmd';

// Defining base url
define("BASE_URL", "http://dreamexpressdelivery.com/index.php");

// Getting Admin url
define("ADMIN_URL", BASE_URL . "admin" . "http://dreamexpressdelivery.com/admin/index.php");

try {
	$pdo = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpass);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch( PDOException $exception ) {
	echo "Connection error :" . $exception->getMessage();
}