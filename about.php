<?php
session_start();
include 'config.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    echo "Erro: Usuário não está logado.";
    exit;
}

// Verifica se um serviço foi selecionado na página anterior
if (!isset($_GET['servico_id'])) {
    echo "Erro: Nenhum serviço selecionado.";
    exit;
}

// Obtém o ID do serviço selecionado
$id_servico = $_GET['servico_id'];
$mensagem_erro = "";

// Manipulação do formulário de agendamento
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = $_POST['data'];
    $horario = $_POST['horario'];

    try {
        // Insere o agendamento no banco
        $sql = "INSERT INTO agendamentos (id_servico, data, horario) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_servico, $data, $horario]);

        // Redireciona para a mesma página para evitar reenvio de formulário
        header("Location: about.php?servico_id=" . $id_servico);
        exit;
    } catch (PDOException $e) {
        // Verifica se o erro é devido ao índice único (conflito de horário)
        if ($e->getCode() == 23000) {
            $mensagem_erro = "<p>Erro: Já existe um agendamento neste horário para o serviço selecionado.</p>";
        } else {
            $mensagem_erro = "<p>Erro: Não foi possível realizar o agendamento.</p>";
        }
    }
}

// Exibe os agendamentos existentes para o serviço selecionado
$sql_agendados = "SELECT data, horario FROM agendamentos WHERE id_servico = ?";
$stmt_agendados = $pdo->prepare($sql_agendados);
$stmt_agendados->execute([$id_servico]);
$agendamentos = $stmt_agendados->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Agendar Serviço</title>
  <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="plugins/themify-icons/themify-icons.css">
  <link rel="stylesheet" href="plugins/slick/slick.css">
  <link rel="stylesheet" href="plugins/Venobox/venobox.css">
  <link rel="stylesheet" href="plugins/aos/aos.css">
  <link href="css/style.css" rel="stylesheet">
</head>
<body>
<!-- navigation -->
<section class="fixed-top navigation">
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light">
      <a class="navbar-brand" href="index.php"><img src="images/logo.png" alt="logo" class="logoHarstil"></a>
      <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse text-center" id="navbar">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="indexlogado.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link page-scroll" href="servicos.php">Serviços</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#pricing">Planos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact.php">Suporte</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="cadastro.php">Cadastro</a>
          </li>
        </ul>
        <a href="logout.php" class="btn btn-primary ml-lg-3 primary-shadow">Logout</a>
      </div>
    </nav>
  </div>
</section>
<!-- /navigation -->

<!-- Formulário de Agendamento -->
<div class="container mt-5">
  <h1>Agendar Serviço</h1>

  <?php if ($mensagem_erro): ?>
    <div class="alert alert-danger" role="alert">
      <?php echo $mensagem_erro; ?>
    </div>
  <?php endif; ?>

  <form method="post">
    <div class="form-group">
      <label for="data">Data:</label>
      <input type="date" id="data" name="data" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="horario">Horário:</label>
      <input type="time" id="horario" name="horario" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Agendar</button>
  </form>

  <!-- Horários Agendados -->
  <h2 class="mt-5">Horários Agendados para este Serviço</h2>
  <?php if ($agendamentos): ?>
    <ul class="list-group mt-3">
      <?php foreach ($agendamentos as $agendamento): ?>
        <li class="list-group-item">
          Data: <?php echo htmlspecialchars($agendamento['data']); ?> - 
          Horário: <?php echo htmlspecialchars($agendamento['horario']); ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p>Nenhum horário agendado para este serviço.</p>
  <?php endif; ?>
</div>

<!-- footer -->
<footer class="footer-section footer" style="background-image: url(images/backgrounds/footer-bg.png);">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 text-center text-lg-left mb-4 mb-lg-0">
        <a href="index.php">
          <img class="img-fluid" src="images/logo.png" alt="logo">
        </a>
      </div>
      <nav class="col-lg-8 align-self-center mb-5">
        <ul class="list-inline text-lg-right text-center footer-menu">
          <li class="list-inline-item"><a href="index.php">Home</a></li>
          <li class="list-inline-item"><a href="about.php">About</a></li>
          <li class="list-inline-item"><a href="team.php">Team</a></li>
          <li class="list-inline-item"><a href="contact.php">Contact</a></li>
        </ul>
      </nav>
    </div>
  </div>
</footer>
<!-- /footer -->

<script src="plugins/jQuery/jquery.min.js"></script>
<script src="plugins/bootstrap/bootstrap.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>
