<?
	session_start();
	$_pdo = new PDO('mysql:host=localhost;dbname=3390017;','root','');

	class connect {
		public static $db = "mysql:host=localhost;dbname=3390017;";
		public static $us = "root";
		public static $pa = "";
	}
?>