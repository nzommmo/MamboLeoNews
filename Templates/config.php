<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
ob_start();
session_start();

// Path to the .env file
$envFile = __DIR__ . '/.env';

$envVariables = parse_ini_file($envFile);

// Access the environment variables
$host = $envVariables['DB_HOST'];
$dbname = $envVariables['DB_NAME'];
$user = $envVariables['DB_USER'];
$password = $envVariables['DB_PASS'];

$dboptions = array(
  PDO::ATTR_PERSISTENT => FALSE,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password, $dboptions);

  $resp = array(
    'status' => 'success',
    'message' => 'Connected to the mysql database successfully.'
);
header('Content-Type: application/json');
//echo json_encode($resp);
} catch (Exception $ex) {
  
  $resp = array(
    'status' => 'error',
    'message' => "Connection failed: " . $ex->getMessage()
);
header('Content-Type: application/json');
echo json_encode($resp);
}

require_once 'functions.php';