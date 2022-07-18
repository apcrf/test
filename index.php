<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>test</title>
	<link rel="icon" type="image/x-icon" href="favicon.ico"/>
</head>
<body>
<?php
	//********************************************************************************************

	// Errors
	error_reporting(E_ALL);
	ini_set("display_errors", 1);

	// Hello
	echo "Hello world!";
	echo "<br>\n";
	echo "Привет мир!";
	echo "<br><br>\n\n";

	echo "***** Apache version: " . $_SERVER["SERVER_SOFTWARE"];
	echo "<br><br>\n\n";

	echo "***** PHP version: " . phpversion();
	echo "<br><br>\n\n";

	//**********************************************************************************************

	// $dsn
	require "config.php";

	// Создаётся объект PDO
	echo "***** Объект PDO: " . $dsn;
	echo "<br><br>\n\n";
	$opt = array(
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => true,
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+00:00'",
	);
	try {
		$pdo = new PDO($dsn, DB_USER, DB_PASS, $opt);
	}
	catch (PDOException $e) {
		http_response_code(500);
		die($e->getMessage());
	}

	// Версия MySQL
	$sql = "
		SELECT VERSION()
	";
	$stmt = $pdo->query($sql);
	$col = $stmt->fetchColumn();
	echo "***** MySQL version: " . $col;
	echo "<br><br>\n\n";

	// TABLES
	$sql = "
		SHOW TABLES
	";
	$stmt = $pdo->query($sql);
	$rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
	foreach ( $rows as $k=>$v ) {
		echo $v . " &nbsp; ";
	}
	echo "<br><br>\n\n";

	//**********************************************************************************************

	// $_SERVER
	echo '***** $_SERVER';
	echo "<br>\n";

	foreach ( $_SERVER as $k=>$v ) {
		echo $k . " --- " . $_SERVER[$k] . "<br>";
	}
/*
	$keys = ["HTTP_USER_AGENT", "HTTP_SEC_CH_UA_PLATFORM", "HTTP_SEC_CH_UA", "USER_NICKNAME", "CURRENT_VERSION_ID","HTTP_X_APPENGINE_USER_EMAIL"];
	foreach ( $keys as $k ) {
		echo $k . " --- " . $_SERVER[$k] . "<br>";
	}
*/
	echo "<br>\n";

	//**********************************************************************************************

	echo "***** cURL";
	echo "<br>\n";

	// Формирование URI и запрос
	$url = "http://www.example.com/";
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	$result = curl_exec($curl);
	curl_close($curl);

	var_dump($result);

	//**********************************************************************************************

	//phpinfo();

	//**********************************************************************************************
?>
</body>
</html>
