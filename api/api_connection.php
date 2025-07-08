<?php

error_reporting(0);
ob_start();
session_start();

header("Content-Type: text/html;charset=UTF-8");

// Live Server
DEFINE('DB_USER', 'root');
DEFINE('DB_PASSWORD', '');
DEFINE('DB_HOST', 'localhost'); //host name depends on server
DEFINE('DB_NAME', 'policies');

$host = DB_HOST;
$dbname = DB_NAME;
$user = DB_USER;
$pass = DB_PASSWORD;
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

