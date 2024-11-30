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
  $status = 1; //Status padrao 'pendente' caso nao seja enviado nenhum;
  $temFiltroStatus = true;
}

if ($temFiltroStatus) {
  $filtro .= " AND status = :status ";
}


//Parametros Calendário
$meses = [1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro"];
$mesCalendario = isset($_GET["mes"]) ? $_GET["mes"] : date("m");
$anoCalendario = isset($_GET["ano"]) ? $_GET["ano"] : date("Y");
$filtro .= " AND MONTH(prazo) = :mes AND YEAR(prazo) = :ano";
$qntdadeAnosListar = 10;
$variacaoMaisEMenos = $qntdadeAnosListar / 2;
$anoInicial = $anoCalendario - $variacaoMaisEMenos;
$anoFinal = $anoCalendario + $variacaoMaisEMenos;

//Adiciona 
$stmt = $pdo->prepare("
    SELECT *
    FROM Tarefa 
    $filtro 
    ORDER BY DATE(prazo) ASC, prioridade DESC
");

if ($temFiltroStatus) {
  $stmt->bindValue(':status', $status, PDO::PARAM_INT);
}
$stmt->bindValue(':proprietario', $usuarioId, PDO::PARAM_INT);
$stmt->bindValue(':mes', $mesCalendario, PDO::PARAM_INT);
$stmt->bindValue(':ano', $anoCalendario, PDO::PARAM_INT);
$stmt->execute();
$tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);


function gerarCalendario($ano, $mes)
{
  $primeiroDia = new DateTime("$ano-$mes-01");
  $ultimoDia = clone $primeiroDia;
  $ultimoDia->modify("last day of this month");

  $diasNoMes = (int) $ultimoDia->format("d");
  $inicioSemana = (int) $primeiroDia->format("w");
  $ultimoDiaSemana = (int) $ultimoDia->format("w");
  $acrescimoDiasFimSemana = 6 - $ultimoDiaSemana;



  for ($i = 0; $i < ($diasNoMes + $inicioSemana + $acrescimoDiasFimSemana); $i++) {
    $dia = $i - $inicioSemana + 1;
    $calendario[] = $dia > 0 && $dia <= $diasNoMes ? $dia : "";
  }
  return $calendario;
}

?>

<?php
$calendario = gerarCalendario($anoCalendario, $mesCalendario);
$currentDate = null;
?>

<!DOCTYPE html>
<html>

<head>
  <title>Organizador de tarefas - Calendário</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .calendar-container {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 10px;
      margin: 20px 0;
    }

    .calendar-card {
      border: 1px solid #ddd;
      border-radius: 5px;
      padding: 10px;
      text-align: center;
      height: 120px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .empty-card {
      background-color: #f9f9f9;
    }

    .day-number {
      font-weight: bold;
    }

    .weekday-header {
      text-align: center;
      font-weight: bold;
      background-color: #f1f1f1;
      padding: 5px;
    }

    .tarefas-do-dia {
      max-height: 150px;
      overflow-y: auto;
    }

    .btnLink {
      font-style: none;
      text-decoration: none;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">Organizador de Tarefas</a>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="home.php">Início</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="calendario.php">Calendário</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Sair</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
    <form method="GET" class="row g-4 w-100">
      <div class="d-flex g-4">
        <div class="w-25 m-2">
          <label for="ano" class="form-label">Ano</label>
          <input name="ano" id="ano" class="form-control form-control-lg p-2" type="number" min="1900" max="2099"
            step="1" value="<?= $anoCalendario ?>" onchange="this.form.submit()" />
          </select>
        </div>
        <div class="w-25 m-2">
          <label for="mes" class="form-label">Mês</label>
          <select name="mes" id="mes" class="form-select form-select-lg  p-2" onchange="this.form.submit()">
            <?php for ($i = 1; $i <= 12; $i++): ?>
              <option value="<?= $i ?>" <?= $i == $mesCalendario ? 'selected' : '' ?>>
                <?php echo $meses[$i]; ?>
              </option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="w-25 m-2">
          <label for="status" class="form-label">Status</label>
          <select name="status" id="status" class="form-select form-select-lg p-2" onchange="this.form.submit()">
            <option value="4" <?= $status === 4 ? 'selected' : '' ?>>Todos</option>
            <option value="1" <?= $status === 1 ? 'selected' : '' ?>>Pendente</option>
            <option value="2" <?= $status === 2 ? 'selected' : '' ?>>Concluída</option>
            <option value="3" <?= $status === 3 ? 'selected' : '' ?>>Arquivada</option>
          </select>
        </div>
      </div>
    </form>
  </div>
  <div class="container mt-5">
    <div class="calendar-container mb-5">
      <?php
      $diasSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
      foreach ($diasSemana as $dia) {
        echo "<div class='weekday-header'>$dia</div>";
      }
      ?>
      <?php foreach ($calendario as $dia): ?>
        <div class="calendar-card <?= $dia ? '' : 'empty-card' ?>">
          <?php if ($dia): ?>
            <div class="day-number"><?= $dia ?></div>
            <div class="tarefas-do-dia">
              <?php
              $dataAtual = (new DateTime("$anoCalendario-$mesCalendario-" . str_pad($dia, 2, '0', STR_PAD_LEFT)))->format('Y-m-d');
              $tarefasDoDia = array_filter($tarefas, function ($tarefa) use ($dataAtual) {
                $dataPrazo = (new DateTime($tarefa['prazo']))->format('Y-m-d');
                return $dataPrazo === $dataAtual;
              });


              if (count($tarefasDoDia) > 0):
                foreach ($tarefasDoDia as $tarefa):
                  $class = '';
                  $classComplemnt = '';
                  switch ((int) $tarefa['status']) {
                    case 1:
                      $class = 'warning';
                      $classComplemnt = 'text-dark';
                      break;
                    case 2:
                      $class = 'success';
                      break;
                    default:
                      $class = 'primary';
                      break;
                  }
                  echo "<a href='editar_tarefa.php?id={$tarefa['id']}' class='badge bg-$class $classComplemnt w-100 btnLink'>{$tarefa['titulo']}</a><br>";
                endforeach;
              else:
                echo "<span class='text-muted'>Sem tarefas</span>";
              endif;
              ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>