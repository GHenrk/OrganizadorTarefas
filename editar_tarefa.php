<?php
require 'include/config.php';
require 'include/autenticacaoUsuario.php';

RedirecionaSeNaoAutenticado('login.php');

// Obtém a tarefa pelo ID passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
  header("Location: home.php");
  exit;
}

$idTarefa = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM Tarefa WHERE id = :id AND proprietario = :proprietario");
$stmt->execute([':id' => $idTarefa, ':proprietario' => $_SESSION['usuario_id']]);
$tarefa = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tarefa) {
  header("Location: home.php");
  exit;
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Organizador de tarefas - Editar Tarefa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">Organizador de tarefas</a>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active" href="home.php">Início</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="calendario.php">Calendário</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Sair</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container mt-5">
    <h2>Tarefa</h2>
    <form method="POST" action="include/processarTarefa.php">
      <input type="hidden" name="id" value="<?php echo $tarefa['id']; ?>">
      <div class="mb-3">
        <label for="titulo" class="form-label">Título</label>
        <input type="text" class="form-control w-50" id="titulo" name="titulo" required
          value="<?php echo htmlspecialchars($tarefa['titulo']); ?>">
      </div>
      <div class="mb-3">
        <label for="descricao" class="form-label">Descrição</label>
        <textarea class="form-control w-50" id="descricao" name="descricao"
          rows="3"><?php echo htmlspecialchars($tarefa['descricao']); ?></textarea>
      </div>
      <div class="mb-3">
        <label for="prioridade" class="form-label">Prioridade</label>
        <select class="form-select w-50" id="prioridade" name="prioridade" required>
          <option value="1" <?php echo $tarefa['prioridade'] == 1 ? 'selected' : ''; ?>>Baixa</option>
          <option value="2" <?php echo $tarefa['prioridade'] == 2 ? 'selected' : ''; ?>>Média</option>
          <option value="3" <?php echo $tarefa['prioridade'] == 3 ? 'selected' : ''; ?>>Alta</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-select w-50" id="status" name="status" required>
          <option value="1" <?php echo $tarefa['status'] == 1 ? 'selected' : ''; ?>>Pendente</option>
          <option value="2" <?php echo $tarefa['status'] == 2 ? 'selected' : ''; ?>>Concluída</option>
          <option value="3" <?php echo $tarefa['status'] == 3 ? 'selected' : ''; ?>>Arquivada</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="prazo" class="form-label">Prazo</label>
        <input type="datetime-local" class="form-control w-50" id="prazo" name="prazo" required
          value="<?php echo date('Y-m-d\TH:i', strtotime($tarefa['prazo'])); ?>">
      </div>
      <div class="w-50 d-flex gap-2 justify-content-end">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="home.php" class="btn btn-secondary">Voltar</a>
        <a href="excluir_tarefa.php?id=<?= $tarefa['id'] ?>" class="btn btn-danger"
          onclick="return confirm('Tem certeza que deseja excluir esta tarefa?')">Excluir</a>
      </div>
    </form>
  </div>
</body>

</html>