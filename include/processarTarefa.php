<?php
require 'config.php';
require 'autenticacaoUsuario.php';
require 'utilsValidacao.php';

RedirecionaSeNaoAutenticado("login.php");

const TAREFA_STATUS = [
  'pendente' => 1,
  'concluida' => 2,
  'arquivada' => 3,
];

const TAREFA_PRIORIDADE = [
  'baixa' => 1,
  'media' => 2,
  'alta' => 3,
];



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // if (!ValidaInfo($_POST['titulo'])) {
  //   header('Location: ../cadastrar_tarefa.php');
  //   exit;
  // }
  $id = $_POST['id'] ?? null;
  $titulo = $_POST['titulo'];
  $descricao = $_POST['descricao'] ?? '';
  $prioridade = $_POST['prioridade'] ?? TAREFA_PRIORIDADE['baixa'];
  $prazo = $_POST['prazo'] ?? new DateTime();
  $usuarioId = $_SESSION['usuario_id'];
  $status = $_POST['status'] ?? TAREFA_STATUS['pendente'];

  try {
    if ($id) {
      $stmt = $pdo->prepare("
      UPDATE tarefa set
      titulo = :titulo,
      descricao = :descricao,
      prazo = :prazo,
      status = :status,
      prioridade = :prioridade
      where id = :id AND proprietario = :proprietario");

      $stmt->execute(
        [
          ':titulo' => $titulo,
          ':descricao' => $descricao,
          'prazo' => $prazo,
          ':status' => $status,
          ':prioridade' => $prioridade,
          ':id' => $id,
          ':proprietario' => $usuarioId,
        ],
      );
      header('Location: ../home.php');
      exit;
    } else {

      $stmt = $pdo->prepare("
      INSERT INTO Tarefa (titulo, descricao, prazo, status, prioridade, proprietario)
      VALUES (:titulo, :descricao, :prazo, :status, :prioridade, :proprietario)
      ");
      $stmt->execute([
        ':titulo' => $titulo,
        ':descricao' => $descricao,
        ':prazo' => $prazo,
        ':status' => $status,
        ':prioridade' => $prioridade,
        ':proprietario' => $usuarioId,
      ]);
      header("Location: ../home.php?success=Tarefa adicionada com sucesso!");
      exit;
    }


  } catch (PDOException $e) {
    if ($id)
      header("Location: ../editar_tarefa.php?error=Erro ao salvar a tarefa.");
    else
      header("Location: ../cadastrar_tarefa.php?error=Erro ao salvar a tarefa.");
    exit;
  }
} else {
  header("Location: ../home.php");
  exit;
}
