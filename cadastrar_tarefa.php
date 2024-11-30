<?php
require 'include/config.php';
require 'include/autenticacaoUsuario.php';

RedirecionaSeNaoAutenticado('login.php');
?>

<!DOCTYPE html>
<html>

<head>
  <title>Adicionar Nova Tarefa</title>
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
    <h2>Adicionar tarefa</h2>
    <form method="POST" action="include/processarTarefa.php">
      <div class="mb-3">
        <label for="titulo" class="form-label">Título</label>
        <input type="text" class="form-control w-50" id="titulo" name="titulo" placeholder="Insira o título">
      </div>
      <div class="mb-3">
        <label for="descricao" class="form-label">Descrição</label>
        <textarea class="form-control w-50" id="descricao" name="descricao" placeholder="Insira a descrição"
          rows="3"></textarea>
      </div>
      <div class="mb-3">
        <label for="prioridade" class="form-label">Prioridade</label>
        <select class="form-select w-50" id="prioridade" name="prioridade" required>
          <option value="1">Baixa</option>
          <option value="2">Média</option>
          <option value="3">Alta</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-select w-50" id="status" name="status" required>
          <option value="1">Pendente</option>
          <option value="2">Concluída</option>
          <option value="3">Arquivada</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="prazo" class="form-label">Prazo</label>
        <input type="datetime-local" class="form-control w-50" id="prazo" name="prazo" required value="<?php
        $date = new DateTime();
        echo $date->format("Y-m-d H:i") ?>">
      </div>
      <div class="w-50 d-flex gap-2 justify-content-end">
        <button type="submit" class="btn btn-primary">Salvar Tarefa</button>
        <a href="home.php" class="btn btn-secondary">Cancelar</a>
      </div>
    </form>
  </div>
</body>

</html>