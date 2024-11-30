<?php
require 'include/config.php';
require 'include/autenticacaoUsuario.php';

RedirecionaSeNaoAutenticado("login.php");

$usuarioId = $_SESSION['usuario_id'];
$nomeUsuario = $_SESSION['usuario_nome'];

//filtro usuario
$filtro = "WHERE proprietario = :proprietario";
$temFiltroStatus = false;

//filtro status
$status = null;
if (!empty($_GET["status"]) && !($_GET["status"] == 4)) {
  $status = (INT) $_GET["status"];
  $temFiltroStatus = true;
} else if (empty($_GET["status"])) {
  $status = 1;
  $_GET["status"] = 1;
  $temFiltroStatus = true;
}
if ($temFiltroStatus) {
  $filtro .= " AND status = :status ";
}

//Filtro paginacao
$limite = 20;
$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$offset = ($pagina - 1) * $limite;


//Verifica quantidade total.
$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM Tarefa $filtro");
if ($temFiltroStatus) {
  $totalStmt->bindValue(':status', $status, PDO::PARAM_INT);
}
$totalStmt->bindValue(':proprietario', $usuarioId, PDO::PARAM_INT);
$totalStmt->execute();
$totalTarefas = $totalStmt->fetchColumn();


//Adiciona 
$stmt = $pdo->prepare("
    SELECT *
    FROM Tarefa 
    $filtro 
    ORDER BY DATE(prazo) ASC, prioridade DESC
    LIMIT :limite OFFSET :offset
");
$stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
if ($temFiltroStatus) {
  $stmt->bindValue(':status', $status, PDO::PARAM_INT);
}
$stmt->bindValue(':proprietario', $usuarioId, PDO::PARAM_INT);
$stmt->execute();
$tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<?php
$currentDate = null;
?>

<!DOCTYPE html>
<html>

<head>
  <title>Organizador de tarefas - Minhas Tarefas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    .table td,
    .table th {
      white-space: nowrap;
      text-overflow: ellipsis;
      overflow: hidden;
      max-width: 25vw;
    }

    .mh-25 {
      max-height: 25px;
    }

    .mw-25 {
      max-width: 25vw;
    }

    .w-25 {
      max-width: 25vw;
    }

    .table-actions .btn {
      padding: 0.25rem 0.5rem;
      font-size: 0.8rem;
      display: inline-block;
    }

    .table-actions {
      display: flex;
      justify-content: flex-end;
      gap: 0.5rem;
    }

    .table-secondary {
      margin-top: 32px;
      margin-bottom: 16px;
      width: 100%;
    }


    .table-secondary .btn-link {
      font-size: 1.2rem;
      color: #6c757d;
    }

    .table-secondary .btn-link:hover {
      color: #343a40;
    }

    .table-secondary i {
      transition: transform 0.3s ease-in-out;
    }

    /* .btnCollapse[aria-expanded="true"] i {
      /* transform: rotate(-180deg); */


    .barra_SeparacaoDados {
      width: 100%;
      text-align: center;
      background-color: white;
    }

    .table-barra {
      width: 100%;
      height: 100%;
      text-align: center;
      padding-top: 32px;
      padding-bottom: 16px;
      background-color: white;
      /* display: block; */
    }

    tr:hover {
      cursor: pointer;
      background-color: #d5d5d5;
      transition: 0.5s;
    }
  </style>
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
    <div class="d-flex justify-content-between">

      <form method="GET" class="d-flex w-25">
        <select name="status" class="form-select me-2" onchange="this.form.submit()">
          <option value="4" <?= isset($_GET['status']) && $_GET['status'] == '4' ? 'selected' : '' ?>>Todos</option>
          <option value="1" <?= isset($_GET['status']) && $_GET['status'] == '1' ? 'selected' : '' ?>>Pendente</option>
          <option value="2" <?= isset($_GET['status']) && $_GET['status'] == '2' ? 'selected' : '' ?>>Concluída
          </option>
          <option value="3" <?= isset($_GET['status']) && $_GET['status'] == '3' ? 'selected' : '' ?>>Arquivada
          </option>
        </select>
      </form>
      <a href='cadastrar_tarefa.php' class="btn btn-primary btn-lg">Adicionar tarefa</a>
    </div>
    <table class="table table-bordered table-striped mt-5">
      <thead>
        <tr>
          <th class="overflow-hidden mh-25 mw-25">Título</th>
          <th class="overflow-hidden mh-25 mw-25">Descrição</th>
          <th>Prioridade</th>
          <th>Prazo</th>
          <th>Status</th>
          <th class="w-25">Ações</th>
        </tr>
      </thead>

      <?php if (count($tarefas) > 0): ?>
        <?php foreach ($tarefas as $tarefa): ?>
          <?php
          $tarefaDate = date('d/m/Y', strtotime($tarefa['prazo']));
          if ($currentDate !== $tarefaDate):
            $currentDate = $tarefaDate;
            ?>
            <tr class="table-barra">
              <td colspan="6" class="barra_SeparacaoDados">
                <div class="d-flex justify-content-between">
                  <strong>Dia <?= $currentDate ?></strong>
                  <button class="btn btn-link text-decoration-none p-0 btnCollapse" type="button" data-bs-toggle="collapse"
                    data-bs-target="#day-<?= str_replace('/', '-', $currentDate) ?>" aria-expanded="true"
                    aria-controls="day-<?= str_replace('/', '-', $currentDate) ?>">
                    <i class="bi bi-chevron-down"></i>
                  </button>
                </div>
              </td>
            </tr>
          <?php endif; ?>
          <tbody id="day-<?= str_replace('/', '-', $currentDate) ?>" class="collapse show">
            <tr>
              <a href="editar_tarefa.php?id=<?= $tarefa['id'] ?>">
                <td class="overflow-hidden mh-25 mw-25"><?= htmlspecialchars($tarefa['titulo']) ?></td>
                <td class="overflow-hidden mh-25 mw-25"><?= htmlspecialchars($tarefa['descricao']) ?></td>
                <td>
                  <?php
                  switch ($tarefa['prioridade']) {
                    case 3:
                      echo '<span class="badge bg-danger w-100">Alta</span>';
                      break;
                    case 2:
                      echo '<span class="badge bg-warning w-100 text-dark">Média</span>';
                      break;
                    case 1:
                      echo '<span class="badge bg-primary w-100">Baixa</span>';
                      break;
                  }
                  ?>
                </td>
                <td><?= $tarefaDate ?></td>
                <td>
                  <?php
                  switch ($tarefa['status']) {
                    case 1:
                      echo '<span class="badge bg-warning w-100  text-dark">Pendente</span>';
                      break;
                    case 2:
                      echo '<span class="badge bg-success w-100">Concluída</span>';
                      break;
                    case 3:
                      echo '<span class="badge bg-primary w-100">Arquivada</span>';
                      break;
                  }
                  ?>
                </td>
                <td class="table-actions">
                  <a href="editar_tarefa.php?id=<?= $tarefa['id'] ?>" style="display:none" id="btnEditar">Editar</a>
                  <?php if ($tarefa["status"] != 2): ?>
                    <a href="concluir_tarefa.php?id=<?= $tarefa['id'] ?>" class="btn btn-success btn-sm">Concluir</a>
                  <?php endif; ?>
                  <?php if ($tarefa["status"] != 3): ?>
                    <a href="arquivar_tarefa.php?id=<?= $tarefa['id'] ?>" class="btn btn-primary btn-sm">Arquivar</a>
                  <?php endif; ?>
                </td>
              </a>
            </tr>
          </tbody>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="6" class="text-center">Nenhuma tarefa encontrada.</td>
        </tr>
      <?php endif; ?>

    </table>
  </div>
  <div class="container w-100 mt-5 mb-5 d-flex justify-content-end ">
    <nav>
      <ul class="pagination">
        <?php
        $totalPaginas = ceil($totalTarefas / $limite);
        for ($i = 1; $i <= $totalPaginas; $i++): ?>
          <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
            <a class="page-link" href="?pagina=<?= $i ?>&status=<?= $_GET['status'] ?? '' ?>">
              <?= $i ?>
            </a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
  <script>

    window.onload = () => {
      const botoesAlternaveis = document.querySelectorAll('.btnCollapse');
      botoesAlternaveis.forEach(btn => {
        btn.addEventListener('click', () => {
          const icon = btn.querySelector('i');
          const expandido = btn.getAttribute('aria-expanded') === 'true';
          if (expandido) { icon.classList.remove('bi-chevron-left'); icon.classList.add('bi-chevron-down'); }
          else { icon.classList.remove('bi-chevron-down'); icon.classList.add('bi-chevron-left'); }
        })
      });


      const tablesRows = document.querySelectorAll('tr');
      tablesRows.forEach(row => {
        row.addEventListener('click', () => {
          const aLinkEditar = row.querySelector('#btnEditar');
          if (aLinkEditar)
            aLinkEditar.click();
        })
      });


    }
  </script>
</body>

</html>