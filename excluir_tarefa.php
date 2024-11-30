<?php
require 'include/config.php';
require 'include/autenticacaoUsuario.php';

RedirecionaSeNaoAutenticado('login.php');

$tarefaId = $_GET['id'] ?? null;
$usuarioId = $_SESSION['usuario_id'];

if ($tarefaId) {
  $stmt = $pdo->prepare("DELETE FROM tarefa WHERE id = :id AND proprietario = :proprietario");
  $stmt->execute([':id' => $tarefaId, ':proprietario' => $usuarioId]);
}
header("Location: home.php");
exit;
