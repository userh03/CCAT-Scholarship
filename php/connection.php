<?php
	$dbhost = "localhost";
	$dbuser = "root";
	$dbpass = "";
	$dbname = "scholarshipportal_db";

	try {
		$pdo = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		die("Connection failed: " . $e->getMessage());
	}

	if (!$con = mysqLi_connect($dbhost,$dbuser,$dbpass,$dbname))
	{
		die("connection failed!");
	}