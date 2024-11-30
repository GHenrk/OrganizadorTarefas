<?php

session_set_cookie_params([
  'lifetime' => 0,
  'httponly' => true,
]);

session_start();


$host = '';
$dbName = '';
$user = '';
$password = '';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbName", $user, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $ex) {
  die("Erro ao conectar ao banco de dados: " . $ex->getMessage());
}
?>