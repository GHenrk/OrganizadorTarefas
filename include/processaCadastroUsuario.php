<?php 
require 'config.php';
require 'utilsValidacao.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  $nome;
  $email;
  $senha;
  
  if (ValidaInfo($_POST['nome']) && ValidaInfo($_POST['senha']) &&  ValidaInfo($_POST['email'])){
      $nome = $_POST['nome'];
      $senha = $_POST['senha'];
      $email = $_POST['email'];
  }
  else {
    header("Location: ../cadastroUsuario.php?error=".urlencode("É Necessário preencher todos os dados do formulário!"));
    exit;
  }
  
  $stmt = $pdo->prepare("SELECT COUNT(*) FROM Usuario WHERE email = :email");
  $stmt->execute(
    [':email' => $email]
  );

  if ($stmt->fetchColumn() > 0){
      header("Location: ../cadastroUsuario.php?error=".urlencode("Email já cadastrado!"));
      exit;
  }

  $hashSenha = password_hash($senha, PASSWORD_DEFAULT);

  $stmt = $pdo->prepare("INSERT INTO Usuario (nome, email, password) VALUES (:nome, :email, :password)"); 
  $stmt->execute([
    ':nome' => $nome,
    ':email' => $email,
    ':password' => $hashSenha
  ]);
  header("Location: ../cadastroUsuario.php?success=".urlencode("Usuário cadastrado com sucesso!"));
  exit;
}
?> 
