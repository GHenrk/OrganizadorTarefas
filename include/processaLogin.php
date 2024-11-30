<?php
require 'config.php';
require 'utilsValidacao.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!ValidaInfo($_POST['email']) && !ValidaInfo($_POST['senha'])) {
    header("Location: ../login.php?error=" . urlencode("É Necessário preencher todas as informações."));
    exit;
  }

  $email = $_POST['email'];
  $senha = $_POST['senha'];

  $stmt = $pdo->prepare("SELECT * FROM Usuario WHERE email = :email");
  $stmt->execute([':email' => $email]);
  $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($usuario && password_verify($senha, $usuario['password'])) {
    session_start();
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    header("Location: ../home.php");
    exit;
  } else {
    header("Location: ../login.php?error=" . urlencode("Usuário ou senha incorreto"));
    exit;
  }
}
?>