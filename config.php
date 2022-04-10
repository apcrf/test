<?php
//****************************************************************************************************

if ( $_SERVER["SERVER_SOFTWARE"] == "Apache/2.4.29 (Ubuntu)" ) {
	// ASUS PN40 MySQL
	define("DB_HOST", "192.168.1.55");
	define("DB_NAME", "trevdo_db");
	define("DB_CHAR", "utf8mb4");
	define("DB_USER", "root");
	define("DB_PASS", "2.");
	$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHAR;
}
else {
	// Local MySQL
	define("DB_HOST", "127.0.0.1");
	define("DB_NAME", "monte_db_w");
	define("DB_CHAR", "utf8mb4");
	define("DB_USER", "root");
	define("DB_PASS", "");
	$dsn = "mysql:unix_socket=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHAR;
}

//****************************************************************************************************
?>
