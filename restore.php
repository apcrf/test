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

	if ( !isset($_REQUEST["Project_ID"]) ) {
		exit("Необходимый параметр: Project_ID");
	}

	// Errors
	error_reporting(E_ALL);
	ini_set("display_errors", 1);

	// Help
	echo "<br><br>\n\n";
	echo "Скрипт восстановления файлов приложения из архива в БД из таблицы project_backups";
	echo "<br><br>\n\n";
	echo "http://test/restore.php?Project_ID=4";
	echo "<br><br>\n\n";

	//**********************************************************************************************
	// $dsn
	//**********************************************************************************************

/*
	// Local MySQL
	define("DB_HOST", "127.0.0.1");
	define("DB_NAME", "monte_db_w");
	define("DB_CHAR", "utf8mb4");
	define("DB_USER", "root");
	define("DB_PASS", "");
*/

	// Cloud SQL
	define("DB_HOST", "/cloudsql/monte-storage:europe-west1:monte-sql-80");
	define("DB_NAME", "monte_db_w");
	define("DB_CHAR", "utf8mb4");
	define("DB_USER", "root");
	define("DB_PASS", "O11kZqQotYN7W1TLXEra");

	// $dsn
	$dsn = "mysql:unix_socket=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHAR;

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

	//**********************************************************************************************

	// Загрузка файлов из архива в БД
	$sql = "
		SELECT *
		FROM project_backups
		WHERE Project_Backup_Project_ID = ?
	";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([ $_REQUEST["Project_ID"] ]);
	while ( $row = $stmt->fetch() ) {
		// dir & file
		$dir = "restore" . $_REQUEST["Project_ID"];
		$dirname = dirname($row["Project_Backup_File"]);
		if ( $dirname != "." ) $dir .= "/" . $dirname;
		$file = basename($row["Project_Backup_File"]);
		$dirfile = $dir . "/" . $file;
		echo "dir: " . $dir . " --- ";
		echo "file: " . $file;
		echo "<br>\n";
		// If dir doesn't exist, make it
		if (!is_dir($dir)) {
			mkdir($dir, 0777, true);
		}
		// Save file
		if ( !empty($row["Project_Backup_Text"]) ) {
			file_put_contents($dirfile, $row["Project_Backup_Text"]);
		}
		if ( !empty($row["Project_Backup_Blob"]) ) {
			file_put_contents($dirfile, $row["Project_Backup_Blob"]);
		}
	}

	echo "<br><br>\n\n";

	//**********************************************************************************************
?>
</body>
</html>
